# Fairlife Admin — User Manual

## ✅ Quick Start
- Open the application URL in your browser, e.g. `https://<your-domain>/fairadmin/`.
- Log in using your assigned username and password on the **Login** page.
- On successful login you are taken to the **Fairlife Dashboard** which provides top-level stats and quick actions.

---

## 1. Logging In & Out 🔐
- Navigate to `index.php` and enter **Username** and **Password**.
- If you forget your password, contact your system administrator to reset it (no self-service reset in this version).
- To log out, use the **Logout** option in the profile menu. Logout clears your session; pressing Back will not re-open your authenticated session.

---

## 2. Dashboard Overview 🧭
Top stat cards show key metrics at a glance:
- **Funds (Today)** — Total eFunds and average balance per member.
- **Beneficiaries (This Month)** — Total beneficiaries, with counts and percentages for active and terminated records.
- **Total Balances (Today)** — Sum of current balances and number of accounts below the low-balance threshold.
- **Total Adhoc Fees (Today)** — Sum of adhoc fees for the current month.

Notes:
- Hovering over a card will apply a subtle highlight. Values load with a skeleton placeholder for fast perceived performance.
- Click a menu/links in the header to navigate to modules (Members, Funds, Reports, Files, etc.).

---

## 3. Common Tasks

### View Members
- Go to `membership/` (via the navigation menu) → **List** or **Search**.
- Use the search and filter controls to find members by name, member number, or status.
- Click a member to view profile, account balances, and transaction history.

### Manage Funds
- Go to `fund/` → **Funds** to see fund records.
- Create, edit, or delete fund entries as allowed by your role.

### Upload Files
- Go to `files/` → **Upload**.
- Use the upload form to add documents. Accepted file types and size limits are shown on the page.

### Generate Reports
- Go to `reports/` and choose a report type (members, funds, contributions).
- Use the date filters and export buttons to generate CSV or printable views.

---

## 4. UI Tips & Accessibility 💡
- The site uses Bootstrap — it is responsive on mobile and desktop.
- Keyboard navigation: use `Tab` to cycle through inputs and `Enter` to activate buttons.
- For long texts or truncated values, hover or open the full view to read the complete value.

---

## 5. Error Handling & Troubleshooting 🛠️
- If the page displays an error, note the message and time, then contact support with a screenshot.
- If data looks missing (e.g., dashboards showing 0), try refreshing the page. If still missing, contact admin to check database connectivity.
- If you get logged out unexpectedly, check your network and re-login. If persistent, notify your system admin.

---

## 6. Security & Best Practices 🔒
- Never share your password. Use the official channels to request changes.
- Log out after using shared machines.
- Report suspicious activity to the security contact.

---

## 7. Support & Contact
- For operational issues, contact: support@yourorg.example (change to your organisation's contact).
- For access or account changes, contact the system administrator.

---

## 8. FAQ
Q: I cannot log in. What do I do?
A: Verify username/password, ensure CapsLock is off, then contact admin to reset your credentials.

Q: How frequently is the dashboard updated?
A: Dashboard pulls live data on page load. For near-real-time updates, refresh the page.

---

If you want this exported as PDF or want screenshots embedded into sections, I can add them and/or generate a `docs/USER_MANUAL.pdf`. Let me know how you'd like the manual tailored (brand, tone, or company contacts).