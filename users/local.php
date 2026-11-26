<?php
// Retired. User management lives in users/local2.php, which is admin-only.
// This page used to create, overwrite and delete users without a role check,
// so it now only redirects and ignores any submitted form.
require_once __DIR__ . '/../scripts/bootstrap.php';
header('Location: ' . app_url('users/local2.php'), true, 301);
exit();
