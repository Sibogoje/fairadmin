<?php

if (!function_exists('access_control_known_roles')) {
    function access_control_known_roles()
    {
        return ['admin', 'Operations', 'clerk', 'Accounts'];
    }
}

if (!function_exists('access_control_permission_definitions')) {
    function access_control_permission_definitions()
    {
        return [
            'beneficiary_core' => [
                'label' => 'Beneficiary: Core Pages',
                'patterns' => [
                    '#/(membership/new\.php|membership/?|membership/benlist\.php|membership/dnew\.php|membership/deceased\.php|membership/newfile\.php)$#',
                ],
                'default_roles' => ['admin', 'Operations', 'clerk'],
            ],
            'fund_core' => [
                'label' => 'Fund: Core Pages',
                'patterns' => [
                    '#/(fund/fnew\.php|fund/?|fund/assets\.php|fund/enew\.php|fund/employers\.php)$#',
                ],
                'default_roles' => ['admin', 'Operations', 'clerk'],
            ],
            'files_module' => [
                'label' => 'Files Module',
                'patterns' => [
                    '#/(files\.php|viewfiles\.php)$#',
                ],
                'default_roles' => ['admin', 'Operations', 'clerk'],
            ],
            'reports_core' => [
                'label' => 'Reports: Core Statements',
                'patterns' => [
                    '#/(membership/profile\.php|membership/membersummary\.php|membership/profileaccount\.php|reports/beneficiaries\.php|membership/existence\.php|membership/membermove\.php)$#',
                ],
                'default_roles' => ['admin', 'Operations', 'clerk'],
            ],
            'transactions_client_requests' => [
                'label' => 'Transactions: Client Requests',
                'patterns' => [
                    '#/transactions/clientr\.php$#',
                ],
                'default_roles' => ['admin', 'Operations', 'clerk'],
            ],
            'transactions_ops' => [
                'label' => 'Transactions: Operations',
                'patterns' => [
                    '#/(transactions/adhoc\.php|transactions/additionalcapital\.php|transactions/terminate\.php)$#',
                ],
                'default_roles' => ['admin', 'Operations'],
            ],
            'reports_ops' => [
                'label' => 'Reports: Operations',
                'patterns' => [
                    '#/(membership/consolsummary\.php|reports/funds\.php|reports/initialfees\.php|reports/payments\.php|reports/balances\.php|reports/deceased\.php)$#',
                ],
                'default_roles' => ['admin', 'Operations'],
            ],
            'transactions_accounts' => [
                'label' => 'Transactions: Accounts',
                'patterns' => [
                    '#/(transactions/interest\.php|transactions/monthlyfees\.php)$#',
                ],
                'default_roles' => ['admin', 'Accounts'],
            ],
            'reports_accounts' => [
                'label' => 'Reports: Accounts',
                'patterns' => [
                    '#/(fund/fundfeesreport\.php|reports/scheduledreport\.php)$#',
                ],
                'default_roles' => ['admin', 'Accounts'],
            ],
            'reports_cross_role' => [
                'label' => 'Reports: Cross Role',
                'patterns' => [
                    '#/(reports/transfees\.php|reports/termination\.php|reports/capitalintroductionreport\.php|reports/adhocreport\.php)$#',
                ],
                'default_roles' => ['admin', 'Operations', 'Accounts'],
            ],
            'admin_only_core' => [
                'label' => 'Admin Only: Core',
                'patterns' => [
                    '#/(membership/pending\.php|transactions/scheduled\.php|transactions/othertransactions\.php)$#',
                ],
                'default_roles' => ['admin'],
            ],
            'admin_only_reports' => [
                'label' => 'Admin Only: Reports',
                'patterns' => [
                    '#/(reports/employers\.php|reports/otherreport\.php|reports/interestreport\.php|reports/monthlyfeesreport\.php)$#',
                ],
                'default_roles' => ['admin'],
            ],
            'admin_only_settings' => [
                'label' => 'Admin Only: Settings & Users',
                'patterns' => [
                    '#/(settings/banks\.php|settings/post\.php|users/transaction\.php|users/fees\.php|users/local2\.php|users/access_control_manager\.php)$#',
                ],
                'default_roles' => ['admin'],
            ],
            'transactions_adjustment' => [
                'label' => 'Transactions: Adjustment',
                'patterns' => [
                    '#/transactions/adjustment\.php$#',
                ],
                'default_roles' => ['admin', 'Operations', 'clerk', 'Accounts'],
            ],
        ];
    }
}

if (!function_exists('access_control_rules_file')) {
    function access_control_rules_file()
    {
        return __DIR__ . '/access_rules.json';
    }
}

if (!function_exists('access_control_role_in')) {
    function access_control_role_in($role, array $allowedRoles)
    {
        return in_array($role, $allowedRoles, true);
    }
}

if (!function_exists('access_control_route_path')) {
    function access_control_route_path()
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
        if (!is_string($path)) {
            return '/';
        }

        $path = preg_replace('#/+#', '/', $path);
        return strtolower($path ?: '/');
    }
}

if (!function_exists('access_control_matches')) {
    function access_control_matches($routePath, array $patterns)
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $routePath)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('access_control_normalize_route')) {
    function access_control_normalize_route($routePath)
    {
        $routePath = strtolower((string)$routePath);
        $routePath = preg_replace('#/+#', '/', $routePath);
        $routePath = preg_replace('#/fix/#', '/', $routePath, 1);
        return $routePath;
    }
}

if (!function_exists('access_control_load_overrides')) {
    function access_control_load_overrides()
    {
        $file = access_control_rules_file();
        if (!is_file($file)) {
            return [];
        }

        $raw = @file_get_contents($file);
        if ($raw === false) {
            return [];
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded) || !isset($decoded['permissions']) || !is_array($decoded['permissions'])) {
            return [];
        }

        $knownRoles = access_control_known_roles();
        $definitions = access_control_permission_definitions();
        $sanitized = [];

        foreach ($decoded['permissions'] as $key => $roles) {
            if (!isset($definitions[$key]) || !is_array($roles)) {
                continue;
            }

            $allowed = [];
            foreach ($roles as $role) {
                if (in_array($role, $knownRoles, true) && !in_array($role, $allowed, true)) {
                    $allowed[] = $role;
                }
            }

            $sanitized[$key] = $allowed;
        }

        return $sanitized;
    }
}

if (!function_exists('access_control_effective_role_map')) {
    function access_control_effective_role_map()
    {
        $definitions = access_control_permission_definitions();
        $overrides = access_control_load_overrides();

        $effective = [];
        foreach ($definitions as $key => $definition) {
            $effective[$key] = isset($overrides[$key])
                ? $overrides[$key]
                : $definition['default_roles'];
        }

        return $effective;
    }
}

if (!function_exists('access_control_save_overrides')) {
    function access_control_save_overrides(array $overrideMap, $updatedBy = '')
    {
        $definitions = access_control_permission_definitions();
        $knownRoles = access_control_known_roles();
        $clean = [];

        foreach ($definitions as $key => $definition) {
            $submittedRoles = $overrideMap[$key] ?? [];
            if (!is_array($submittedRoles)) {
                $submittedRoles = [];
            }

            $roles = [];
            foreach ($submittedRoles as $role) {
                if (in_array($role, $knownRoles, true) && !in_array($role, $roles, true)) {
                    $roles[] = $role;
                }
            }

            $clean[$key] = $roles;
        }

        $payload = [
            'updated_at' => date('c'),
            'updated_by' => (string)$updatedBy,
            'permissions' => $clean,
        ];

        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return [false, 'Failed to encode access rules.'];
        }

        $result = @file_put_contents(access_control_rules_file(), $json . PHP_EOL, LOCK_EX);
        if ($result === false) {
            return [false, 'Unable to write access rules file. Check file permissions for scripts/access_rules.json.'];
        }

        return [true, 'Access rules updated successfully.'];
    }
}

if (!function_exists('access_control_is_allowed')) {
    function access_control_is_allowed($role, $routePath)
    {
        $routePath = access_control_normalize_route($routePath);

        if (preg_match('#/dash\.php$#', $routePath)) {
            return true;
        }

        $definitions = access_control_permission_definitions();
        $effectiveRoles = access_control_effective_role_map();

        foreach ($definitions as $key => $definition) {
            if (access_control_matches($routePath, $definition['patterns'])) {
                $allowedRoles = $effectiveRoles[$key] ?? $definition['default_roles'];
                return access_control_role_in($role, $allowedRoles);
            }
        }

        return true;
    }
}

if (!function_exists('access_control_forbidden')) {
    function access_control_forbidden()
    {
        http_response_code(403);
        echo '<!doctype html><html><head><meta charset="utf-8"><title>Access denied</title></head><body style="font-family:Arial,sans-serif;padding:24px;"><h2>Access denied</h2><p>You do not have permission to access this page.</p><p><a href="' . htmlspecialchars(app_url('dash.php')) . '">Return to dashboard</a></p></body></html>';
        exit;
    }
}
