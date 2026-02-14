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
require_once '../scripts/connection.php';

$id = $_POST['id'];
$file_path = $_POST['filepath'];

// Delete item from database
$sql = "DELETE FROM tblfiles WHERE id = '$id'";
if ($conn->query($sql) === TRUE) {
    
    echo $file_path;

if (file_exists($file_path)) {
  if (unlink($file_path)) {
    echo "File deleted Successfully.";
  } else {
    echo "Error deleting file.";
  }
} else {
  echo "File does not exist.";
}

    
   echo "File URL Deleted Succesfully";   
    

} else {
  echo "Error deleting item: " . $conn->error;
}

$conn->close();

?>