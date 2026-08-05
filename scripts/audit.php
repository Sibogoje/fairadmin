<?php

if (!function_exists('audit_trail_username')) {
    function audit_trail_username(): string
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            if (!empty($_SESSION['user'])) {
                return (string) $_SESSION['user'];
            }

            if (!empty($_SESSION['username'])) {
                return (string) $_SESSION['username'];
            }

            if (!empty($_SESSION['memberno'])) {
                return (string) $_SESSION['memberno'];
            }
        }

        return 'guest';
    }
}

if (!function_exists('audit_trail_ensure_table')) {
    function audit_trail_ensure_table(mysqli $conn): void
    {
        static $initialized = false;

        if ($initialized) {
            return;
        }

        $conn->query(
            "CREATE TABLE IF NOT EXISTS `audit_trail` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `username` VARCHAR(255) NOT NULL,
                `action` VARCHAR(255) NOT NULL,
                `details` TEXT NULL,
                `route` VARCHAR(255) NULL,
                `ip_address` VARCHAR(45) NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_audit_trail_username_created_at` (`username`, `created_at`),
                KEY `idx_audit_trail_created_at` (`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $initialized = true;
    }
}

if (!function_exists('audit_trail_log')) {
    function audit_trail_log(mysqli $conn, string $action, string $details = ''): void
    {
        try {
            audit_trail_ensure_table($conn);

            $username = audit_trail_username();
            $route = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';

            $stmt = $conn->prepare("INSERT INTO `audit_trail` (`username`, `action`, `details`, `route`, `ip_address`) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('sssss', $username, $action, $details, $route, $ipAddress);
            $stmt->execute();
            $stmt->close();
        } catch (Throwable $e) {
            error_log('Audit trail write failed: ' . $e->getMessage());
        }
    }
}
