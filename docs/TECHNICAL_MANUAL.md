# Fairlife Admin — Technical Manual

**Version**: 1.0  
**Last Updated**: February 15, 2026  
**Environment**: PHP 7.4.33, MySQLi, Bootstrap 5, LiteSpeed Server

---

## Table of Contents
1. [System Architecture](#system-architecture)
2. [Technology Stack](#technology-stack)
3. [Directory Structure](#directory-structure)
4. [Authentication & Session Management](#authentication--session-management)
5. [Access Control Engine (ACL)](#access-control-engine-acl)
6. [Database Schema](#database-schema)
7. [Key Modules](#key-modules)
8. [Menu System & Dynamic Rendering](#menu-system--dynamic-rendering)
9. [User Management System](#user-management-system)
10. [File Upload System](#file-upload-system)
11. [Configuration & Deployment](#configuration--deployment)
12. [Development Guidelines](#development-guidelines)
13. [Troubleshooting](#troubleshooting)

---

## System Architecture

### Overview
Fairlife Admin is a **role-based access control (RBAC)** system built on PHP, MySQL, and Bootstrap 5. It manages beneficiary records, fund accounts, transactions, and user permissions with a centralized ACL engine.

### Request Flow
```
User Browser
    ↓
HTTP Request (index.php / page.php)
    ↓
Session Check (session_start())
    ↓
ACL Authorization Check (access_control_is_allowed)
    ↓
Route Handler (specific module)
    ↓
Database Query (MySQLi)
    ↓
Dynamic Menu Rendering (access_control_can_show_menu_item)
    ↓
HTML Response + Bootstrap UI
    ↓
Browser Renders Page
```

### Core Principles
- **Session-based authentication**: Credentials stored in `$_SESSION` upon login
- **Centralized ACL**: All menu visibility and route access controlled by `scripts/access_control.php`
- **Role-based menu rendering**: Sidebar dynamically shows only permitted sections per role
- **Prepared statements**: All database queries use MySQLi prepared statements to prevent SQL injection
- **Client-side cache busting**: JavaScript/CSS files include timestamp to force fresh loads

---

## Technology Stack

| Component | Technology | Version |
|-----------|-----------|---------|
| **Language** | PHP | 7.4.33 |
| **Database** | MySQL | InnoDB |
| **Database Driver** | MySQLi (procedural) | Built-in |
| **Frontend** | HTML5 + CSS3 | - |
| **CSS Framework** | Bootstrap | 5.x |
| **JavaScript** | Vanilla JS + jQuery | 3.2.1+ |
| **Icons** | Bootstrap Icons | 1.x |
| **Data Tables** | Simple DataTables | - |
| **Form Validation** | HTML5 + JS | - |
| **Server** | LiteSpeed | - |

---

## Directory Structure

```
fairadmin/
├── docs/
│   ├── USER_MANUAL.md          # End-user documentation
│   └── TECHNICAL_MANUAL.md     # This file
├── scripts/
│   ├── bootstrap.php           # Global initialization (DB, constants, utilities)
│   ├── connection.php          # MySQLi connection handler
│   ├── access_control.php      # ACL engine (core authorization logic)
│   └── access_rules.json       # Dynamic ACL override rules (persisted)
├── assets/
│   ├── css/
│   │   ├── style.css          # Custom styles
│   ├── js/
│   │   ├── main.js            # Global JS
│   │   ├── login.js           # Login form handler
│   └── vendor/                # Bootstrap & third-party JS/CSS
├── membership/                # Beneficiary management module
│   ├── index.php              # List beneficiaries
│   ├── new.php                # Create new beneficiary
│   ├── edit.php               # Edit beneficiary
│   ├── profile.php            # View beneficiary profile
│   └── ...
├── fund/                      # Fund management module
│   ├── index.php              # List funds
│   ├── fnew.php               # Create new fund
│   ├── fedit.php              # Edit fund
│   └── ...
├── reports/                   # Reports module
│   ├── index.php              # Report home
│   ├── employers.php          # Employer report
│   └── ...
├── users/                     # User management module
│   ├── local2.php             # User management UI
│   ├── access_control_manager.php  # ACL rule manager
│   └── select2/               # Select2 plugin
├── files/                     # File upload module
│   ├── index.php              # File list
│   ├── up.php                 # Upload handler
│   └── uploads/               # Uploaded files storage
├── header.php                 # Shared sidebar/header (included on all pages)
├── index.php                  # Login page
├── parse.php                  # Login form processor
├── dash.php                   # Dashboard / home
├── default.php                # Default catch-all page
├── privacy.php                # Privacy policy
├── logout.php                 # Logout handler
└── logo.png                   # Application logo
```

---

## Authentication & Session Management

### Login Flow

1. **User visits** `index.php`
   - Static HTML form with `username` and `password` fields
   - Form action points to `parse.php`, method POST

2. **Form submitted to** `parse.php`
   - Validates POST input
   - Queries `realuzer` table for matching username
   - Compares submitted password hash (MD5) against stored hash
   - **On valid credentials**:
     - Sets session variables:
       - `$_SESSION['user']` = username
       - `$_SESSION['role']` = assigned role (admin, clerk, Operations, Accounts)
       - `$_SESSION['zid']` = session ID
       - `$_SESSION['xid']` = user ID
     - Calls `session_write_close()` to persist session to disk
     - Issues `header('Location: dash.php')` redirect
   - **On invalid credentials**:
     - Redirects to login with no output

3. **Browser redirects to** `dash.php`
   - Session check: `if (!isset($_SESSION['zid'])) header('Location: index.php')`
   - ACL check: Verifies user's role can access /dash.php
   - Renders dashboard with authenticated user's data

### Session Persistence

- **Session files stored** in system temp directory (configured in `php.ini`)
- **Session ID** passed via PHPSESSID cookie
- **Critical**: `session_write_close()` in `parse.php` ensures session data is written to disk before redirect
- On each page load, `session_start()` reads session from storage

### Logout
- `logout.php` calls `session_destroy()` to clear all session data
- Redirects to login page
- Browser back button cannot restore authenticated session

### Troubleshooting Sessions
- Check PHP error log for session write errors
- Verify server allows session file creation (permissions)
- Test cookie handling: browser must accept PHPSESSID cookie
- For HTTPS: verify `session.cookie_secure` and `session.cookie_httponly` settings

---

## Access Control Engine (ACL)

### Overview
The ACL engine (`scripts/access_control.php`) is the **single source of truth** for authorization. It determines:
- Which routes (pages) are recognized by the system
- Which roles can access each route
- Whether a menu item should be visible to a user

### Core Functions

#### `access_control_is_allowed($role, $routePath): bool`
Checks if a role has **any** permission to a route. Used for initial page access control.

```php
if (!access_control_is_allowed($_SESSION['role'], '/membership/new.php')) {
    access_control_forbidden(); // Show 403 error
}
```

#### `access_control_can_show_menu_item($role, $routePath): bool`
Stricter check used for **menu visibility**. Route must be:
1. Recognized by the system (in permission groups)
2. User's role has explicit permission

```php
<?php if ($canRoute('membership/new.php')): ?>
    <a href="membership/new.php">New Beneficiary</a>
<?php endif; ?>
```

#### `access_control_matching_permission_key($routePath): ?string`
Returns the permission group (e.g., 'beneficiary_core') for a given route, or null if not recognized.

### Permission Groups

Defined in `$permissions` array in `access_control.php`:

```php
$permissions = [
    'beneficiary_core' => [
        'routes' => [
            '#/membership/(new\.php|index\.php|benlist\.php|dnew\.php|deceased\.php|newfile\.php)$#',
        ],
        'default_allowed' => ['admin', 'clerk'],
    ],
    'beneficiary_files' => [
        'routes' => ['#/membership/file\.php$#'],
        'default_allowed' => ['admin', 'clerk'],
    ],
    'fund_management' => [
        'routes' => ['#/fund/[a-z0-9_\-]+\.php$#'],
        'default_allowed' => ['admin', 'Operations'],
    ],
    'transactions' => [
        'routes' => ['#/Transactions/[a-z0-9_\-]+\.php$#'],
        'default_allowed' => ['admin', 'Accounts'],
    ],
    'reports' => [
        'routes' => ['#/reports/[a-z0-9_\-]+\.php$#'],
        'default_allowed' => ['admin', 'Operations', 'clerk'],
    ],
    'users' => [
        'routes' => ['#/users/[a-z0-9_\-]+\.php$#'],
        'default_allowed' => ['admin'],
    ],
];
```

### Dynamic Overrides

Admins can modify permissions at runtime via `users/access_control_manager.php`. Overrides are persisted in `scripts/access_rules.json`:

```json
{
    "add_roles": {
        "beneficiary_core": ["Operations"]
    },
    "remove_roles": {
        "fund_management": ["Operations"]
    }
}
```

The ACL engine merges default permissions with these overrides when evaluating access.

### Route Normalization
Routes are normalized before comparison:
- Strip `/fix/` prefix (if present)
- Force `.php` extension
- Lowercase all characters
- Example: `/Fix/Membership/New` → `/membership/new.php`

---

## Database Schema

### Main Tables

#### `realuzer` (Users)
```sql
CREATE TABLE realuzer (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE,
    password VARCHAR(255),          -- MD5 hash (consider bcrypt upgrade)
    role VARCHAR(50),               -- admin, clerk, Operations, Accounts
    session VARCHAR(255),           -- Last active session ID
    last_login DATETIME,            -- Last login timestamp
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### Example Roles & Access
| Role | Beneficiary Core | Funds | Transactions | Reports | Users |
|------|------------------|-------|--------------|---------|-------|
| admin | ✅ | ✅ | ✅ | ✅ | ✅ |
| clerk | ✅ | ✅ | ❌ | ✅ | ❌ |
| Operations | ❌ | ✅ | ❌ | ✅ | ❌ |
| Accounts | ❌ | ❌ | ✅ | ✅ | ❌ |

#### Related Tables
- `beneficiary` — Beneficiary/member records
- `fund` — Fund accounts
- `contributions` — Payment records
- `transactions` — Transfer/withdrawal records
- `files` — Uploaded document metadata

---

## Key Modules

### 1. Membership Module (`membership/`)
**Purpose**: Manage beneficiary records, benefits, certifications, and files.

**Key Pages**:
- `index.php` — List all beneficiaries
- `new.php` — Create new beneficiary
- `edit.php` — Edit beneficiary details
- `profile.php` — View complete beneficiary profile
- `benlist.php` — Beneficiary list (filtered)
- `file.php` — Manage beneficiary files
- `deceased.php` — Manage deceased records
- `newfile.php` — Upload new file for beneficiary

**Permissions**: `beneficiary_core`, `beneficiary_files`

### 2. Fund Module (`fund/`)
**Purpose**: Manage fund accounts, fees, and balances.

**Key Pages**:
- `index.php` — List funds
- `fnew.php` — Create new fund
- `fedit.php` — Edit fund
- `assets.php` — View fund assets
- `fees.php` — Configure fund fees
- `employers.php` — Manage employer records
- `profiledata.php` — Fund profile & balances

**Permissions**: `fund_management`

### 3. Reports Module (`reports/`)
**Purpose**: Generate and export member, fund, and transaction reports.

**Key Pages**:
- `index.php` — Report home
- `employers.php` — Employer summary report
- `members.php` — Member list/summary
- `contributions.php` — Contribution history

**Permissions**: `reports`

### 4. Transactions Module (`Transactions/`)
**Purpose**: Record and track fund transfers and withdrawals.

**Key Pages**:
- `index.php` — Transaction list
- `create.php` — New transaction
- `print.php` — Transaction receipt

**Permissions**: `transactions` (typically Accounts role)

### 5. Files Module (`files/`)
**Purpose**: Upload and manage system documents.

**Key Pages**:
- `index.php` — File list
- `up.php` — File upload handler
- `view.php` — View uploaded file

**Upload Validation**: File type and size restrictions enforced in `up.php`

### 6. Users Module (`users/`)
**Purpose**: Manage system users and ACL rules.

**Key Pages**:
- `local2.php` — User management (create, edit, delete users; set roles)
- `access_control_manager.php` — Override ACL rules per role

**Features**:
- Create new users with role assignment
- Edit existing users: change role without mandatory password change
- Optional password update (checkbox toggle)
- Modify role-to-permission mappings at runtime

**Permissions**: `users` (admin only)

---

## Menu System & Dynamic Rendering

### Header Structure
The shared header (`header.php`) is included on every authenticated page:

```php
<?php
require_once __DIR__ . '/header.php';
?>
```

### Dynamic Menu Rendering
Menu items are conditionally rendered based on `access_control_can_show_menu_item()`:

```php
<?php if ($canRoute('membership/new.php')): ?>
    <li class="nav-item">
        <a class="nav-link" href="membership/new.php">
            <i class="bi bi-person-plus"></i> New Beneficiary
        </a>
    </li>
<?php endif; ?>
```

### Menu Sections
Header defines sections by wrapping related items:

```php
<?php if ($showBeneficiary): ?>
    <li class="nav-heading">Membership</li>
    <?php if ($canRoute('membership/index.php')): ?>...<?php endif; ?>
    <?php if ($canRoute('membership/new.php')): ?>...<?php endif; ?>
<?php endif; ?>
```

### How It Works
1. Page loads, retrieves `$_SESSION['role']`
2. Header evaluates `$canRoute()` for each menu item
3. Items with `$canRoute() === true` are rendered
4. Items with `$canRoute() === false` are hidden (no HTML)
5. User sees only permitted menu sections

---

## User Management System

### Creating Users (`users/local2.php`)

**Form Fields**:
- Username (required, unique)
- Password (required)
- Role (dropdown: admin, clerk, Operations, Accounts)

**Backend** (`local2.php` POST handler):
```php
if (isset($_POST['savenew'])) {
    $username = mysqli_real_escape_string($conn, $_POST['addusername']);
    $password = md5($_POST['addpassword']); // ⚠ Consider bcrypt upgrade
    $role = mysqli_real_escape_string($conn, $_POST['addrole']);
    
    $stmt = $conn->prepare("INSERT INTO realuzer (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();
}
```

### Editing Users

**Form Fields**:
- Username (read-only)
- Change Password (checkbox) — reveals password field if checked
- Role (dropdown, pre-selected with current role)

**Backend Logic**:
- If "Change Password" is NOT checked: only update username and role
- If "Change Password" IS checked: update all three fields

```php
if (isset($_POST['edit'])) {
    $sql = "UPDATE realuzer SET username = ?, role = ?";
    $params = [$username, $role];
    
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $sql .= ", password = ?";
        $params[] = $password;
    }
    
    $sql .= " WHERE id = ?";
    $params[] = $id;
    
    // Prepare and execute...
}
```

### Frontend Features
- **Current role pre-selected** in dropdown (not the first option)
- **Password field hidden by default**
- **Checkbox toggles visibility** of password input
- **Current role displayed** when modal opens

---

## File Upload System

### Upload Workflow (`files/up.php`)

1. **User selects file** in upload form
2. **Validation**:
   - File type whitelist (e.g., PDF, DOC, XLS)
   - File size limit (configured in up.php)
3. **Storage**:
   - Files stored in `files/uploads/` directory
   - Filename sanitized to prevent path traversal
4. **Metadata**:
   - File record inserted into database with:
     - Original filename
     - Stored filename
     - Upload timestamp
     - Associated beneficiary/fund (if applicable)

### Security Considerations
- **No magic bytes check** (should add MIME type validation)
- **Filename sanitized** to alphanumeric + underscore/dash
- **Upload path outside web root** preferred for sensitive docs
- **Access control** enforced: only authorized users can view/download

---

## Configuration & Deployment

### Key Configuration Files

#### `scripts/bootstrap.php`
Global initialization file included by all pages:

```php
<?php
require_once __DIR__ . '/connection.php';

// Define app URL and paths
define('APP_URL', 'https://fairlife.grinpath.com/');
define('APP_PATH', __DIR__ . '/../');

// Database connection
global $conn;
// ... mysqli connection setup

// Utility functions

function app_url($path) {
    return APP_URL . ltrim($path, '/');
}
```

#### `scripts/connection.php`
MySQLi database connection:

```php
<?php
$servername = "localhost";
$username = "db_user";
$password = "db_pass";
$dbname = "fairlife_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
```

### Environment Variables
Currently hardcoded in `bootstrap.php`. Consider migrating to:
- `.env` file (use `vlucas/phpdotenv`)
- Server environment variables
- Separate config for dev/staging/production

### PHP Configuration
**Recommended `php.ini` settings**:
```ini
display_errors = Off
log_errors = On
error_log = /var/log/php/error.log
session.gc_maxlifetime = 1800        # 30 minutes
session.cookie_httponly = 1          # Prevent JS access to cookie
session.cookie_secure = 1            # HTTPS only
session.cookie_samesite = Strict     # CSRF protection
max_upload_filesize = 50M
post_max_size = 50M
```

### Deployment Checklist
- [ ] Copy all files to production server
- [ ] Set file permissions: `755` for dirs, `644` for files
- [ ] Create `files/uploads/` with write permissions
- [ ] Database created and tables populated
- [ ] `scripts/connection.php` configured for production DB
- [ ] `scripts/bootstrap.php` APP_URL updated
- [ ] HTTPS enabled (certificate installed)
- [ ] PHP error logging configured
- [ ] Session temp directory has write permissions
- [ ] Test login flow end-to-end
- [ ] Verify ACL rules load correctly

---

## Development Guidelines

### Code Standards
- **PHP**: PSR-2 style (4-space indent, camelCase for functions)
- **MySQL**: Prepared statements always (no string concatenation in queries)
- **Sessions**: Always use `session_write_close()` after session modifications before redirects
- **HTML/CSS**: Bootstrap 5 classes; vanilla JS; jQuery 3.x

### Adding New Routes/Permissions
1. Add route pattern to `scripts/access_control.php` in `$permissions` array
2. Define which roles get default access
3. Update menu in `header.php` to show/hide the item conditionally
4. Create the route handler page
5. Include auth check at top of page:

```php
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
require_once __DIR__ . '/../scripts/access_control.php';
session_start();

if (!isset($_SESSION['zid'])) {
    header('Location: ' . app_url('index.php'));
    exit();
}

$routePath = access_control_route_path();
if (!access_control_is_allowed($_SESSION['role'] ?? '', $routePath)) {
    access_control_forbidden();
}
?>
```

### Adding New Modules
1. Create directory under root (e.g., `/newmodule/`)
2. Create `index.php` as module entry point
3. Define routes and permissions in ACL
4. Update `header.php` menu with links to module
5. Follow existing module structure (index, create, edit, view, list)

### Security Practices
- **Always validate/sanitize user input** before DB queries (prepared statements)
- **Use `htmlspecialchars()` or `htmlentities()`** when outputting user data in HTML
- **Test ACL changes** before deploying to production
- **Never expose password hashes** in error messages or logs
- **Use HTTPS** in production (cert on server)
- **Keep PHP updated** to latest 7.4.x version

### Database Best Practices
- Use prepared statements for all queries
- Index frequently queried columns (username, role, member ID, etc.)
- Regular backups (daily recommended)
- Monitor slow query log for performance issues
- Use transactions for multi-table operations

---

## Troubleshooting

### Login Issues

**Problem**: Redirect loop on login (302 Found)
- **Cause**: Session not persisting across `parse.php` → `dash.php` redirect
- **Solution**: Ensure `session_write_close()` called in parse.php before `header()` redirect
- **Check**: PHP error log for session write failures; verify session directory permissions

**Problem**: "Unexpected server response" error on login form
- **Cause**: Browser cache holding old JavaScript file expecting JSON response
- **Solution**: Hard refresh (Ctrl+Shift+R or Cmd+Shift+R) on login page
- **Permanent fix**: Cache-busting added via `?v=<?php echo time(); ?>` in script tags

### Menu Not Showing Items

**Problem**: Menu sections/items not rendering even for admin user
- **Cause**: ACL permission group not defined or route pattern incorrect
- **Check**: 
  1. Route exists in `scripts/access_control.php` $permissions array
  2. Current role is in the 'default_allowed' or dynamic overrides
  3. Execute ACL test: `php scripts/test_acl.php`

**Problem**: User can access page but menu item hidden
- **Cause**: Permission check is stricter for menu visibility than page access
- **Solution**: Ensure `access_control_can_show_menu_item()` evaluates true for the route

### Database Connection Issues

**Problem**: "Connection failed" error on load
- **Check**:
  1. MySQL server running
  2. Credentials in `scripts/connection.php` correct
  3. Database name exists and is accessible
  4. User has CREATE/SELECT/INSERT/UPDATE/DELETE grants

**Problem**: Queries failing with SQL errors
- **Check**: Prepared statement syntax; parameter binding types match data
- **Common**: Binding type mismatch (e.g., "ss" for 2 strings but 3 params passed)

### Performance Issues

**Problem**: Pages loading slowly / database queries slow
- **Solutions**:
  1. Enable slow query log in MySQL
  2. Add indexes to frequently filtered columns
  3. Optimize N+1 queries (loop inside DB query)
  4. Use caching for static data (e.g., role list)
  5. Consider pagination for large result sets

### Session Timeout

**Problem**: User logged out unexpectedly
- **Cause**: `session.gc_maxlifetime` (default 1440 = 24 min)
- **Solution**: Increase in `php.ini` or implement manual session refresh
- **Considerations**: Balance security vs. usability

---

## Appendix: Quick References

### File Locations Summary
| Item | Location |
|------|----------|
| ACL Engine | `scripts/access_control.php` |
| Database Connection | `scripts/connection.php` |
| Global Init | `scripts/bootstrap.php` |
| Login Page | `index.php` |
| Login Processor | `parse.php` |
| Dashboard | `dash.php` |
| Shared Header/Menu | `header.php` |
| User Management | `users/local2.php` |
| ACL Manager | `users/access_control_manager.php` |

### MySQL Commands (Development)
```sql
-- Check current users
SELECT id, username, role, last_login FROM realuzer;

-- Reset user password (MD5 hash)
UPDATE realuzer SET password = MD5('newpassword') WHERE username = 'admin';

-- Check all beneficiary records
SELECT * FROM beneficiary LIMIT 10;

-- View fund balances
SELECT * FROM fund WHERE active = 1;
```

### PHP Testing Commands
```bash
# Test ACL logic
php scripts/test_acl.php

# Test database connection
php -r "require 'scripts/connection.php'; echo 'Connected!'; "

# Check PHP version
php -v

# Test file permissions
ls -la files/uploads/
```

---

**End of Technical Manual**  
For questions or updates, contact your system administrator.
