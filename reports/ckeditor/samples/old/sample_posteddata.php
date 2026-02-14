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
/* <body><pre>

-------------------------------------------------------------------------------------------
  CKEditor - Posted Data

  We are sorry, but your Web server does not support the PHP language used in this script.

  Please note that CKEditor can be used with any other server-side language than just PHP.
  To save the content created with CKEditor you need to read the POST data on the server
  side and write it to a file or the database.

  Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
  For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
-------------------------------------------------------------------------------------------

</pre><div style="display:none"></body> */ include "assets/posteddata.php"; ?>
