<?php

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