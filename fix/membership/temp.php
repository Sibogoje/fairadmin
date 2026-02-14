<?php
require_once __DIR__ . '/../../scripts/bootstrap.php';
session_start();
$ids=$_POST['id'];
$_SESSION['varname'] = $ids;




//
?>