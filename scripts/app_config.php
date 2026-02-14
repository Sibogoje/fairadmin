<?php

if (!defined('APP_URL')) {
    $configuredAppUrl = getenv('APP_URL');

    if (!$configuredAppUrl) {
        $isHttps = (
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
        );
        $scheme = $isHttps ? 'https' : 'http';
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $configuredAppUrl = $scheme . '://' . $host . '/';
    }

    define('APP_URL', rtrim($configuredAppUrl, '/') . '/');
}

if (!defined('LEGACY_APP_URL')) {
    define('LEGACY_APP_URL', 'https://fairlife.grinpath.com/');
}

if (!function_exists('app_url')) {
    function app_url($path = '')
    {
        return APP_URL . ltrim($path, '/');
    }
}
