<?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
// The plain text password to be hashed
$plaintext_password = "sibo";

// The hash of the password that
// can be stored in the database
$hash = password_hash($plaintext_password,
		PASSWORD_DEFAULT);

// Print the generated hash
echo "Generated hash: ".$hash;
?>
