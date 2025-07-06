<?php
session_start(); // Start the session on the index page.
include('config.php');
$memberno = $_SESSION['memberno'];
if (isset($_SESSION['memberno'])) {
    // Check if the "online" status is set to 1 in the database.
    try {
        $memberno = $_SESSION['memberno'];

        // Check if the device is logged in (online).
        $stmt = $pdo->prepare("SELECT online FROM MobUsers WHERE memberid = :memberno");
        $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['online'] == 1) {
            // User is logged in, display the index page.
           // echo "Welcome to the index page!"; // You can include your index page content here.
        } else {
            // Device is not logged in, redirect to the login page.
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // No valid session, redirect to the login page.
    header("Location: login.php");
    exit();
}

// if (isset($_POST['request'])) {
//     // Handle form data
//     $reason = $_POST['reason'];
//     $amount = $_POST['amount'];
//     //$memberno = $_POST['memberno'];
//     $currentDate = date("Y-m-d"); // Get the current date in the desired format


//     //selct memberno from tblmembers where memberno = $memberno
//     $stmt = $pdo->prepare("SELECT MemberID, MemberNo, MemberFirstname, MemberSurname FROM tblmembers WHERE MemberNo = :memberno");
//     $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
//     $stmt->execute();

//     $member = $stmt->fetch(PDO::FETCH_ASSOC);
//     //$memberno = $member['MemberNo'];
//     $fullname = $memberno."-".$member['MemberFirstname'] . ' ' . $member['MemberSurname'];
//     $memberid = $member['MemberID'];
//     $memberno = $member['MemberNo'];

//     if ($member['MemberNo'] == $memberno) {
//         // Member number exists in tblmembers, continue with the request.

//             // Handle file upload
//     $uploadDirectory = 'uploads/' . $memberno . '/'; // Create a subfolder with memberno
//     if (!is_dir($uploadDirectory)) {
//         mkdir($uploadDirectory, 0777, true); // Create the subfolder if it doesn't exist
//     }
    
//     $uploadedFile = $_FILES['documents']['name'];
//     $fileExtension = pathinfo($uploadedFile, PATHINFO_EXTENSION);
//     $newFileName = $memberno.$reason . '_' . date("Ymd_His") . '.' . $fileExtension;
//     $fileURL = $uploadDirectory . $newFileName;

//     $tempFile = $_FILES['documents']['tmp_name'];

//     if (move_uploaded_file($tempFile, $fileURL)) {
//         // File uploaded successfully, now insert data into the database
        
//     $fileURL1 = 'https://liquag.com/dev/fairlife/mobile/'.$uploadDirectory . $newFileName;
        
//         $stmt = $pdo->prepare("INSERT INTO clientr (memberid, name, amount, reason, file) VALUES (:memberid, :name, :amount, :reason, :file, :reqdate)");
//                 $stmt = $pdo->prepare("INSERT INTO clientr (memberid, name, amount, reason, file, reqdate) VALUES (:memberid, :name, :amount, :reason, :file, :reqdate)");
//                 $stmt->bindParam(':memberid', $memberid, PDO::PARAM_INT);
//                 $stmt->bindParam(':name', $fullname, PDO::PARAM_STR);
//                 $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
//                 $stmt->bindParam(':reason', $reason, PDO::PARAM_STR);
//                 $stmt->bindParam(':reqdate', $currentDate, PDO::PARAM_STR);
//                 $stmt->bindParam(':file', $fileURL1, PDO::PARAM_STR);

//         if ($stmt->execute()) {
//               $stmt = $pdo->prepare("INSERT INTO tblfiles (member, name, dateupload, reason, url) 
//               VALUES (:memberno, :name, :dateupload, :reason, :file_url)");
//                 $stmt->bindParam(':name', $fullname, PDO::PARAM_STR);
//                 $stmt->bindParam(':dateupload', $currentDate, PDO::PARAM_STR);
//                 $stmt->bindParam(':reason', $reason, PDO::PARAM_STR);
//                 $stmt->bindParam(':memberno', $memberid, PDO::PARAM_STR);
//                 $stmt->bindParam(':file_url', $fileURL, PDO::PARAM_STR);

//                 if ($stmt->execute()) {
//                     echo '<script>alert("Success: Request Submitted."); window.location = "adhoc-pending.php";</script>';

//                 } else {
//                     echo '<script>alert("Error: Failed to save file."); window.location = "adhoc.php";</script>';
//                 }
//         } else {
//               echo '<script>alert("Error: Failed to submit request."); window.location = "adhoc.php";</script>';
//         }
//     } else {
//         echo '<script>alert("Error: Please upload supporting document."); window.location = "adhoc.php";</script>';
//     }

//     } else {
//         // Member number does not exist in tblmembers, show an alert and redirect to login.php.
//         echo '<script>alert("Member number does not exist. Please login again."); window.location = "login.php";</script>';
//         exit();
//     }


// }


// if (isset($_POST['request'])) {
//     // Handle form data
//     $reason = $_POST['reason'];
//     $amount = $_POST['amount'];
   
//         //selct memberid from tblmembers where memberno = $memberno
//     $stmt = $pdo->prepare("SELECT MemberID, MemberNo, MemberFirstname, MemberSurname FROM tblmembers WHERE MemberNo = :memberno");
//     $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
//     $stmt->execute();

//     $member = $stmt->fetch(PDO::FETCH_ASSOC);
//     //$memberno = $member['MemberNo'];
//     $fullname = $memberno."-".$member['MemberFirstname'] . ' ' . $member['MemberSurname'];
//     $memberid = $member['MemberID'];
//     $memberno = $member['MemberNo'];
//     $currentDate = date("Y-m-d"); // Get the current date in the desired format

//     // Handle file upload for the request letter (mandatory)
//     if (!empty($_FILES['request_letter']['name'])) {
//         $uploadDirectory = 'uploads/' . $memberno . '/';
//         if (!is_dir($uploadDirectory)) {
//             mkdir($uploadDirectory, 0777, true);
//         }
        
//         $randomNumber = mt_rand(1, 10000); // Random number between 1 and 100 (inclusive)
//         $fnumber = $memberno."-".$randomNumber;

//         $uploadedFile = $_FILES['request_letter']['name'];
//         $fileExtension = pathinfo($uploadedFile, PATHINFO_EXTENSION);
//         $newFileName = $fnumber . '_request_' . date("Ymd_His") . '.' . $fileExtension;
//         $fileURL = $uploadDirectory . $newFileName;
//         $tempFile = $_FILES['request_letter']['tmp_name'];
        
        

//         if (move_uploaded_file($tempFile, $fileURL)) {
//             // Request letter uploaded successfully, now insert data into the database
//             $fileURL1 = 'https://liquag.com/dev/fairlife/mobile/' . $fileURL;

//             $stmt = $pdo->prepare("INSERT INTO clientr (memberid, name, amount, reason, file, reqdate, fnumber) VALUES (:memberid, :name, :amount, :reason, :file, :reqdate, :fnumber)");
//             $stmt->bindParam(':memberid', $memberid, PDO::PARAM_INT);
//             $stmt->bindParam(':name', $fullname, PDO::PARAM_STR);
//             $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
//             $stmt->bindParam(':reason', $reason, PDO::PARAM_STR);
//             $stmt->bindParam(':reqdate', $currentDate, PDO::PARAM_STR);
//             $stmt->bindParam(':file', $fileURL1, PDO::PARAM_STR);
//             $stmt->bindParam(':fnumber', $fnumber, PDO::PARAM_STR);

//             if ($stmt->execute()) {
//                 // Insert supporting documents (if provided)
//                  echo '<script>alert("Success: Request Submitted. Click Ok to submit Documents"); window.location = "adhoc-pending.php";</script>';
//                 for ($i = 1; $i <= 3; $i++) {
//                     $supportDocKey = 'supportdoc' . $i;
//                     if (!empty($_FILES[$supportDocKey]['name'])) {
//                         // Handle and insert the supporting document as needed (similar to the request letter)
//                                 $supportDocKey = $_FILES[$supportDocKey]['name'];
//                                 $fileExtension = pathinfo($supportDocKey, PATHINFO_EXTENSION);
//                                 $newDocName = $memberno . $reason . '_request_' . date("Ymd_His") . '.' . $fileExtension;
//                                 $DocURL = $uploadDirectory . $newDocName;
//                                 $tempDoc = $_FILES[$supportDocKey]['tmp_name'];
                                
//                               if (move_uploaded_file($tempDoc, $DocURL)) {
                                  
//                                   $stmt = $pdo->prepare("UPDATE clientr SET support.'$i' = :supporti where fnumber = :fnumber");
//                                     $stmt->bindParam(':supporti', $supportDocKey, PDO::PARAM_STR);
//                                     $stmt->bindParam(':fnumber', $fnumber, PDO::PARAM_STR);
                                    
//                               } else {
//                                     echo '<script>alert("Error: Failed to upload Supporting Documents."); window.location = "adhoc.php";</script>';
//                                 }

//                     }
//                 }

               
//             } else {
//                 echo '<script>alert("Error: Failed to submit request."); window.location = "adhoc.php";</script>';
//             }
//         } else {
//             echo '<script>alert("Error: Failed to upload request letter."); window.location = "adhoc.php";</script>';
//         }
//     } else {
//         // Request letter is mandatory, so show an error message if it's not provided
//         echo '<script>alert("Error: Please upload the request letter."); window.location = "adhoc.php";</script>';
//     }
// }

if (isset($_POST['request'])) {
    // Handle form data
    $reason = $_POST['reason'];
    $amount = $_POST['amount'];
    
    // Select memberid from tblmembers where memberno = $memberno
    $stmt = $pdo->prepare("SELECT MemberID, MemberNo, MemberFirstname, MemberSurname FROM tblmembers WHERE MemberNo = :memberno");
    $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
    $stmt->execute();

    $member = $stmt->fetch(PDO::FETCH_ASSOC);
    $fullname = $memberno . "-" . $member['MemberFirstname'] . ' ' . $member['MemberSurname'];
    $memberid = $member['MemberID'];
    $memberno = $member['MemberNo'];
    $currentDate = date("Y-m-d"); // Get the current date in the desired format
    $randomNumber = mt_rand(1, 10000); // Random number between 1 and 10000 (inclusive)
    $fnumber = $memberno . "" . $randomNumber;

    // Handle file upload for the request letter (mandatory)
    if (!empty($_FILES['request_letter']['name'])) {
        $uploadDirectory = 'uploads/' . $memberno . '/';
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        $uploadedFile = $_FILES['request_letter']['name'];
        $fileExtension = pathinfo($uploadedFile, PATHINFO_EXTENSION);
        $newFileName = $fnumber . '_request_' . date("Ymd_His") . '.' . $fileExtension;
        $fileURL = $uploadDirectory . $newFileName;
        $tempFile = $_FILES['request_letter']['tmp_name'];
        
        

        if (move_uploaded_file($tempFile, $fileURL)) {
            // Request letter uploaded successfully, now insert data into the database
            $fileURL1 = 'https://fairlifeadmin.com/mobile/' . $fileURL;

            $stmt = $pdo->prepare("INSERT INTO clientr (memberid, name, amount, reason, file, reqdate, fnumber) VALUES (:memberid, :name, :amount, :reason, :file, :reqdate, :fnumber)");
            $stmt->bindParam(':memberid', $memberid, PDO::PARAM_INT);
            $stmt->bindParam(':name', $fullname, PDO::PARAM_STR);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
            $stmt->bindParam(':reason', $reason, PDO::PARAM_STR);
            $stmt->bindParam(':reqdate', $currentDate, PDO::PARAM_STR);
            $stmt->bindParam(':file', $fileURL1, PDO::PARAM_STR);
            $stmt->bindParam(':fnumber', $fnumber, PDO::PARAM_STR);

            if ($stmt->execute()) {
                // Handle and insert supporting documents (if provided)
                for ($i = 1; $i <= 3; $i++) {
                    $supportDocKey = 'supportdoc' . $i;
                    if (!empty($_FILES[$supportDocKey]['name'])) {
                        $uploadedSupportDoc = $_FILES[$supportDocKey]['name'];
                        $supportDocFileExtension = pathinfo($uploadedSupportDoc, PATHINFO_EXTENSION);
                        $newSupportDocName = $fnumber . '_support_doc_' . $i . '_' . date("Ymd_His") . '.' . $supportDocFileExtension;
                        $supportDocURL = $uploadDirectory . $newSupportDocName;
                        $tempSupportDoc = $_FILES[$supportDocKey]['tmp_name'];

                        if (move_uploaded_file($tempSupportDoc, $supportDocURL)) {
                            // Supporting document uploaded successfully, now update the database
                            $stmt = $pdo->prepare("UPDATE clientr SET support" . $i . " = :supportDocURL WHERE fnumber = :fnumber");
                            $stmt->bindParam(':supportDocURL', $supportDocURL, PDO::PARAM_STR);
                            $stmt->bindParam(':fnumber', $fnumber, PDO::PARAM_STR);

                            if (!$stmt->execute()) {
                                // Handle the case where the update failed
                                echo '<script>alert("Error: Failed to update supporting documents."); window.location = "adhoc.php";</script>';
                            }
                        } else {
                            echo '<script>alert("Error: Failed to upload supporting documents."); window.location = "adhoc.php";</script>';
                        }
                    }
                }

                echo '<script>alert("Success: Request Submitted. Click OK to submit Documents"); window.location = "adhoc-pending.php";</script>';
            } else {
                echo '<script>alert("Error: Failed to submit request."); window.location = "adhoc.php";</script>';
            }
        } else {
            echo '<script>alert("Error: Failed to upload request letter."); window.location = "adhoc.php";</script>';
        }
    } else {
        // Request letter is mandatory, so show an error message if it's not provided
        echo '<script>alert("Error: Please upload the request letter."); window.location = "adhoc.php";</script>';
    }
}



// if (isset($_POST['request'])) {
//     // Input validation (sanitize inputs)
//     $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING);
//     $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_INT);
//     $memberno = filter_input(INPUT_POST, 'memberno', FILTER_SANITIZE_STRING);
//     $currentDate = date("Y-m-d");
//     $memberno = $_SESSION['memberno'];

//     // Check if member number exists in tblmembers
//     $stmt = $pdo->prepare("SELECT MemberID, MemberNo, MemberFirstname, MemberSurname FROM tblmembers WHERE MemberNo = :memberno");
//     $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
//     $stmt->execute();

//     $member = $stmt->fetch(PDO::FETCH_ASSOC);

//     if ($member && $member['MemberNo'] == $memberno) {
//         // Member number exists in tblmembers, continue with the request.

//         // Handle file upload
//         $uploadDirectory = 'uploads/' . $memberno . '/';
//         if (!is_dir($uploadDirectory)) {
//             mkdir($uploadDirectory, 0777, true);
//         }

//         $fileURLs = [];

//         foreach ($_FILES['documents']['tmp_name'] as $key => $tmpFile) {
//             $uploadedFile = $_FILES['documents']['name'][$key];
//             $fileExtension = pathinfo($uploadedFile, PATHINFO_EXTENSION);
//             $newFileName = $memberno . $reason . '_' . date("Ymd_His") . '.' . $fileExtension;
//             $fileURL = $uploadDirectory . $newFileName;

//             if (move_uploaded_file($tmpFile, $fileURL)) {
//                 $fileURLs[] = 'https://liquag.com/dev/fairlife/mobile/' . $fileURL;
//             }
//         }

//         // Insert data into the database within a transaction
//         try {
//             $pdo->beginTransaction();

//             $stmt = $pdo->prepare("INSERT INTO clientr (memberid, name, amount, reason, file, reqdate) VALUES (:memberid, :name, :amount, :reason, :file, :reqdate)");
//             $stmt->bindParam(':memberid', $member['MemberID'], PDO::PARAM_INT);
//             $stmt->bindParam(':name', $memberno . "-" . $member['MemberFirstname'] . ' ' . $member['MemberSurname'], PDO::PARAM_STR);
//             $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
//             $stmt->bindParam(':reason', $reason, PDO::PARAM_STR);
//             $stmt->bindParam(':reqdate', $currentDate, PDO::PARAM_STR);

//             foreach ($fileURLs as $fileURL) {
//                 $stmt->bindParam(':file', $fileURL, PDO::PARAM_STR);
//                 $stmt->execute();
//             }

//             // Commit the transaction if all inserts were successful
//             $pdo->commit();

//             // Redirect to success page
//             header("Location: adhoc-pending.php");
//             exit();
//         } catch (PDOException $e) {
//             // Rollback the transaction if an error occurred
//             $pdo->rollback();
//             // Handle the error (e.g., log it or show an error message to the user)
//             echo '<script>alert("Error: ' . $e->getMessage() . '"); window.location = "adhoc.php";</script>';
//         }
//     } else {
//         // Member number does not exist in tblmembers, show an alert and redirect to login.php.
//         echo '<script>alert("Member number does not exist. Please login again."); window.location = "login.php";</script>';
//     }
// }


?>

<!DOCTYPE html>
<html lang="en" data-menu="vertical" data-nav-size="nav-default">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adhoc | Fairlife</title>

    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="assets/vendor/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/css/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/vendor/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="assets/vendor/css/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/vendor/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" id="primaryColor" href="assets/css/blue-color.css">
    <link rel="stylesheet" id="rtlStyle" href="#">
</head>

<body class="body-padding body-p-top">
    <!-- preloader start -->
    <div class="preloader d-none">
        <div class="loader">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <!-- preloader end -->
    <?php
    include 'header.php';
    ?>
    <!-- main content start -->
    <div class="main-content">
        <div class="dashboard-breadcrumb mb-25">

            <h2>Adhoc Request</h2>
        </div>
        <div class="row">
        <div class="col-lg-6">
            <div class="panel mb-25">
            <div class="panel-header">
                <h5>Make Request</h5>
            </div>
            <div class="panel-body">
                        <form method="post" enctype="multipart/form-data">
                        <div class="col-sm-6 mb-20">
                            <label for="basicInput" class="form-label">Reason for Adhoc Request</label>
                            <input type="text" name="reason" class="form-control" id="basicInput">
                        </div>
                        
                        <div class="col-sm-6 mb-20">
                            <label for="basicInput" class="form-label">Amount</label>
                            <input type="number" name="amount" class="form-control" id="basicInput">
                        </div>
                        
                        <div class="col-sm-6 mb-20">
                            <label for="formFileSingle" class="form-label">Select Request Letter *</label>
                            <input type="file" class="form-control" name="request_letter" id="formFileSingle" required>
                        </div>
                        
                        <div class="col-sm-6 mb-20">
                            <label for="formFileMultiple1" class="form-label">Supporting Document 1</label>
                            <input type="file" class="form-control" name="supportdoc1" id="formFileMultiple1">
                        </div>
                        
                        <div class="col-sm-6 mb-20">
                            <label for="formFileMultiple2" class="form-label">Supporting Document 2</label>
                            <input type="file" class="form-control" name="supportdoc2" id="formFileMultiple2">
                        </div>
                        
                        <div class="col-sm-6 mb-20">
                            <label for="formFileMultiple3" class="form-label">Supporting Document 3</label>
                            <input type="file" class="form-control" name="supportdoc3" id="formFileMultiple3">
                        </div>
                        
                        <button type="submit" name="request" class="btn btn-warning w-100">Request</button>
                        </form>


            </div>
        </div>
        </div>
        </div>
        </div>









    <!-- main content end -->
    
    
    <script src="assets/vendor/js/jquery-3.6.0.min.js"></script>
    <script src="assets/vendor/js/jquery.overlayScrollbars.min.js"></script>
    <script src="assets/vendor/js/sweetalert2.all.min.js"></script>
    <script src="assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/sweet-alert-init.js"></script>
</body>

</html>