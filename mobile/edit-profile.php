<?php
session_start(); // Start the session on the index page.
include('config.php');

$memberno = $_SESSION['memberno'];


if (isset($_POST['updateguardiantbn'])) {
    try {
        // Establish a database connection and execute an UPDATE query to update the guardian's information.
        $stmt = $pdo->prepare("UPDATE tblguardians SET GuardianEmail = :email, GuardianCell = :phone, GuardianTelHome = :home_phone, GuardianPostalAddress = :postal, GuardianPhysicalAddress = :residential WHERE GuardianID = :guardian_id");
        
        // Replace these placeholders with the actual field names from your form.
        $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $stmt->bindParam(':phone', $_POST['phone'], PDO::PARAM_STR);
        $stmt->bindParam(':home_phone', $_POST['home_phone'], PDO::PARAM_STR);
        $stmt->bindParam(':postal', $_POST['postal'], PDO::PARAM_STR);
        $stmt->bindParam(':residential', $_POST['residential'], PDO::PARAM_STR);
        $stmt->bindParam(':guardian_id', $_POST['id'], PDO::PARAM_INT); // Replace with the actual guardian ID

        // Execute the update query
        $stmt->execute();

        // Redirect to a success page or display a success message
       // echo "Guardian information updated successfully.";
        echo '<script>alert("Guardian information updated successfully."); window.location = "edit-profile.php";</script>';
     //   header("Location: edit-profile.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>




<?php
// Assuming you have a valid $user_id and $pdo (database connection).

if (isset($_POST['updatepwd'])) {
    $currentPassword = $_POST['currentpwd'];
    $newPassword = $_POST['newpwd'];
    $confirmPassword = $_POST['newpwd1'];

    // Validate input (e.g., check if the new password matches the confirmation).
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        echo '<script>alert("All fields are required.");</script>';
    } elseif ($newPassword !== $confirmPassword) {
        echo '<script>alert("New passwords do not match.");</script>';
    } else {
        // Verify the current password.
        $stmt = $pdo->prepare("SELECT password FROM MobUsers WHERE memberid = :user_id");
        $stmt->bindParam(':user_id', $memberno, PDO::PARAM_INT);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            $passwordHash = $userData['password']; // This is the hashed password from the database.

            if (password_verify($currentPassword, $passwordHash)) {
                // Hash the new password.
                $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

                // Update the password in the database.
                $updateStmt = $pdo->prepare("UPDATE MobUsers SET password = :new_password WHERE memberid = :user_id");
                $updateStmt->bindParam(':new_password', $newPasswordHash, PDO::PARAM_STR);
                $updateStmt->bindParam(':user_id', $memberno, PDO::PARAM_INT);

                if ($updateStmt->execute()) {
                    echo '<script>alert("Password updated successfully."); window.location = "edit-profile.php";</script>';
                } else {
                    echo '<script>alert("Failed to update password."); window.location = "edit-profile.php";</script>';
                }
            } else {
                echo '<script>alert("Current password is incorrect."); window.location = "edit-profile.php";</script>';
            }
        } else {
            echo '<script>alert("User not found."); window.location = "edit-profile.php";</script>';
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en" data-menu="vertical" data-nav-size="nav-default">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Fairlife</title>
    
    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="assets/vendor/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="assets/vendor/css/select2.min.css">
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
<?php include 'header.php';?>



    <!-- main content start -->
    <div class="main-content">
        <div class="dashboard-breadcrumb mb-25">
            <h2>Edit Profile</h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="panel">
                    <div class="panel-header">
                        <nav>
                            <div class="btn-box d-flex flex-wrap gap-1" id="nav-tab" role="tablist">
                                <button class="btn btn-sm btn-outline-primary active" id="nav-edit-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-edit-profile" type="button" role="tab" aria-controls="nav-edit-profile" aria-selected="true">Edit Guardian Profile</button>
                                <button class="btn btn-sm btn-outline-primary" id="nav-change-password-tab" data-bs-toggle="tab" data-bs-target="#nav-change-password" type="button" role="tab" aria-controls="nav-change-password" aria-selected="false">Change Password</button>

                            </div>
                        </nav>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content profile-edit-tab" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-edit-profile" role="tabpanel" aria-labelledby="nav-edit-profile-tab" tabindex="0">

<?php
try {
    // Establish a database connection and execute a query to get the member's information.
    $stmt = $pdo->prepare("SELECT GuardianID FROM tblmembers WHERE MemberNo = :memberno");
    $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
    $stmt->execute();

    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt2 = $pdo->prepare("SELECT * FROM tblguardians WHERE GuardianID = :gaurdianid");
    $stmt2->bindParam(':gaurdianid', $member['GuardianID'], PDO::PARAM_STR);
    $stmt2->execute();
    $guardian = $stmt2->fetch(PDO::FETCH_ASSOC);
?>
                                <form method="post">

                                   <div class="profile-edit-tab-title">
                                        <h6>Guardian Contact Information</h6>
                                    </div>
                                    <div class="private-information mb-25">
                                        <div class="row g-3">

                                        <div class="col-md-4 col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa-light"></i></span>
                                                    <input type="ematextil" name="id" class="form-control" placeholder="GuardianID" value="<?php echo $guardian['GuardianID']; ?>" hidden>
                                                </div>
                                            </div>


                                            <div class="col-md-4 col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa-light fa-envelope"></i></span>
                                                    <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $guardian['GuardianEmail']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa-light fa-mobile"></i></span>
                                                    <input type="tel" name="phone" class="form-control" placeholder="Primary Phone" value="<?php echo $guardian['GuardianCell']; ?>" >
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa-light fa-phone"></i></span>
                                                    <input type="tel" name="home_phone" class="form-control" placeholder="Home Telephone" value="<?php echo $guardian['GuardianTelHome']; ?>" >
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa-light fa-address-card"></i></span>
                                                    <input type="text" name="postal" class="form-control" placeholder="Postal Address" value="<?php echo $guardian['GuardianPostalAddress']; ?>">
                                                </div>
                                            </div>

                                            <!-- write code for residential address usind text area -->
                                            <div class="col-md-4 col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa-light fa-location-arrow"></i></span>
                                                    <textarea name="residential" class="form-control" placeholder="Residential Address" value=""><?php echo $guardian['GuardianPhysicalAddress']; ?></textarea>
                                                </div>
                                            </div>

                                            <!-- write code for save button -->
                                            <div class="col-12">
                                                <button type="submit" name="updateguardiantbn" class="btn btn-warning w-100">Save Changes</button>
                                            </div>  
                                                
                                    </div>
                                    </div>


                                </form>

<?php
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
                            </div>
                            
                            
                            <div class="tab-pane fade" id="nav-change-password" role="tabpanel" aria-labelledby="nav-change-password-tab" tabindex="0">
                                <form method="post">
                                    <div class="profile-edit-tab-title">
                                        <h6>Change Password</h6>
                                    </div>
                                    <div class="social-information">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa-light fa-lock"></i></span>
                                                    <input type="password" name="currentpwd" class="form-control" placeholder="Current Password">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa-light fa-lock"></i></span>
                                                    <input type="password" name="newpwd" class="form-control" placeholder="New Password">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa-light fa-lock"></i></span>
                                                    <input type="text" name="newpwd1" class="form-control" placeholder="Confirm Password">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" name="updatepwd" class="btn btn-warning w-100">Update Pasword</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>




                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer start -->
        <div class="footer">
            <p>Copyright© <script>document.write(new Date().getFullYear())</script> All Rights Reserved By <span class="text-primary">FairLife</span></p>
        </div>
        <!-- footer end -->
    </div>
    <!-- main content end -->
    
    <script src="assets/vendor/js/jquery-3.6.0.min.js"></script>
    <script src="assets/vendor/js/jquery.overlayScrollbars.min.js"></script>
    <script src="assets/vendor/js/select2.min.js"></script>
    <script src="assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/select2-init.js"></script>
</body>
</html>