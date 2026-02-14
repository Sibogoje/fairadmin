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
$type = 'Core';
$name = 'Courier-Bold';
$up = -100;
$ut = 50;
for($i=0;$i<=255;$i++)
	$cw[chr($i)] = 600;
$enc = 'cp1252';
$uv = array(0=>array(0,128),128=>8364,130=>8218,131=>402,132=>8222,133=>8230,134=>array(8224,2),136=>710,137=>8240,138=>352,139=>8249,140=>338,142=>381,145=>array(8216,2),147=>array(8220,2),149=>8226,150=>array(8211,2),152=>732,153=>8482,154=>353,155=>8250,156=>339,158=>382,159=>376,160=>array(160,96));
?>
