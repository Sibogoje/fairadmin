<?php

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

if (!function_exists('access_control_is_allowed')) {
    function access_control_is_allowed($role, $routePath)
    {
        $routePath = strtolower($routePath);

        if (preg_match('#/dash\.php$#', $routePath)) {
            return true;
        }

        $rules = [
            [
                'roles' => ['admin', 'Operations', 'clerk'],
                'patterns' => [
                    '#/(membership/new\.php|membership/?|membership/benlist\.php|membership/dnew\.php|membership/deceased\.php|membership/newfile\.php)$#',
                    '#/(fund/fnew\.php|fund/?|fund/assets\.php|fund/enew\.php|fund/employers\.php)$#',
                    '#/transactions/clientr\.php$#',
                    '#/(files\.php|viewfiles\.php)$#',
                    '#/(membership/profile\.php|membership/membersummary\.php|membership/profileaccount\.php|reports/beneficiaries\.php|membership/existence\.php|membership/membermove\.php)$#',
                ],
            ],
            [
                'roles' => ['admin', 'Operations'],
                'patterns' => [
                    '#/(transactions/adhoc\.php|transactions/additionalcapital\.php|transactions/terminate\.php)$#',
                    '#/(membership/consolsummary\.php|reports/funds\.php|reports/initialfees\.php|reports/payments\.php|reports/balances\.php|reports/deceased\.php)$#',
                ],
            ],
            [
                'roles' => ['admin', 'Accounts'],
                'patterns' => [
                    '#/(transactions/interest\.php|transactions/monthlyfees\.php)$#',
                    '#/(fund/fundfeesreport\.php|reports/scheduledreport\.php)$#',
                ],
            ],
            [
                'roles' => ['admin', 'Operations', 'Accounts'],
                'patterns' => [
                    '#/(reports/transfees\.php|reports/termination\.php|reports/capitalintroductionreport\.php|reports/adhocreport\.php)$#',
                ],
            ],
            [
                'roles' => ['admin'],
                'patterns' => [
                    '#/(membership/pending\.php|transactions/scheduled\.php|transactions/othertransactions\.php)$#',
                    '#/(reports/employers\.php|reports/otherreport\.php|reports/interestreport\.php|reports/monthlyfeesreport\.php)$#',
                    '#/(settings/banks\.php|settings/post\.php|users/transaction\.php|users/fees\.php|users/local2\.php)$#',
                ],
            ],
            [
                'roles' => ['admin', 'Operations', 'clerk', 'Accounts'],
                'patterns' => [
                    '#/transactions/adjustment\.php$#',
                ],
            ],
        ];

        foreach ($rules as $rule) {
            if (access_control_matches($routePath, $rule['patterns'])) {
                return access_control_role_in($role, $rule['roles']);
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
