<?php
session_start();
require_once 'scripts/connection.php';
set_time_limit(0);

if (isset($_POST['username'], $_POST['password'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM `realuzer` WHERE `username`=?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];
        $id = $row['id'];
        $role = $row['role'];

        // Verify the password using a secure hashing algorithm
        if (password_verify($pass, $storedPassword)) {
            // Sanitize the session data
            $sessionid = session_id();
            $xid = $id;

            // Encrypt the session data
            $encryptedSessionid = openssl_encrypt($sessionid, 'AES-256-CBC', 'secret');
            $encryptedXid = openssl_encrypt($xid, 'AES-256-CBC', 'secret');

            // Update the session data in the database
            $updatesession = $conn->prepare("UPDATE realuzer SET sessionid=?, xid=? WHERE id=?");
            $updatesession->bind_param("sss", $encryptedSessionid, $encryptedXid, $id);
            $updatesession->execute();

            $_SESSION['user'] = $user;
            $_SESSION['role'] = $role;
            $_SESSION['zid'] = $sessionid;
            $_SESSION['xid'] = $id;

            $response = array(
                'statusCode' => 200,
                'success' => "Allowed"
            );
            echo json_encode($response);
            exit;
        }
    }

    $response = array(
        'statusCode' => 201,
        'exception' => "No user with this username exists or invalid password"
    );
    echo json_encode($response);
    exit;
}

$response = array(
    'statusCode' => 202,
    'error' => "Problem"
);
echo json_encode($response);
exit;
?>