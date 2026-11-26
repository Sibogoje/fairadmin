<?php
// Keep-alive for the inactivity timer in header.php. Called while the user is
// active (typing, clicking) so a long stretch on one page does not let the
// server-side idle timeout (scripts/session_guard.php) log them out.
require_once __DIR__ . '/scripts/bootstrap.php';
session_start();
require_once __DIR__ . '/scripts/connection.php'; // runs the idle check / logs out if expired

header('Content-Type: application/json');
header('Cache-Control: no-store');
echo json_encode(['loggedIn' => !empty($_SESSION['zid'])]);
