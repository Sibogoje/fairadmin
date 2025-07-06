<?php
$useid = $_GET['owner'];
echo $useid;
session_start();
require_once '../scripts/connection.php';
$gg = $_SESSION['user'];


if (!$_FILES["uploadingfile"]["tmp_name"]) {//No file chosen
    echo "ERROR: Please browse for a file before clicking the upload button.";
    exit();
} else {
    if (!file_exists("video/".$gg."/")) {
    mkdir("files/".$gg."/", 0777, true);
}
    $extension = pathinfo($_FILES['uploadingfile']['name'], PATHINFO_EXTENSION);//Gets the file extension
    if ((($_FILES["uploadingfile"]["type"] != "video/mp4")) ) {//Check if MP4 extension
        $folderPath = "files/".$gg."/";//Directory to put file into
        $original_file_name = $_FILES["uploadingfile"]["name"];//File name
        $size_raw = $_FILES["uploadingfile"]["size"];//File size in bytes
        $size_as_mb = number_format(($size_raw / 510048576), 2);//Convert bytes to Megabytes
        if (move_uploaded_file($_FILES["uploadingfile"]["tmp_name"], "$folderPath" . $_FILES["uploadingfile"]["name"] . "")) {//Move file
            echo "$original_file_name upload is complete";
            //echo "$original_file_name uploaded to $folderPath it is $size_as_mb Mb.";

$duration = "Unknonw";  
$location = "https://liquag.com/live/files/files/".$gg."/".$original_file_name;
$insertnew = $conn->prepare("insert into `files` (

`userid`, `link`, `name`
)

VALUES
  (

	?,
	?,
	?
  );");
$insertnew->bind_param("sss", 
$useid,
$location,
$original_file_name
);
$insertnew->execute();

        }
    } else {
        echo "File is not a MP4";
        exit;
    }
}