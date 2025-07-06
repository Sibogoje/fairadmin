<?php 
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $memberNo = $_POST['memberno'];
    $dob = $_POST['gdob'];
    $nationalID = $_POST['nationalid'];
    $newPassword = $_POST['password'];

    // Validate user input (you may add more validation as needed)
    if (empty($memberNo) || empty($dob) || empty($nationalID) || empty($newPassword)) {
        echo "Please fill in all the fields.";
    } else {
        // Perform database lookup to verify user details (replace 'users' with your table name)
        $stmt = $pdo->prepare("SELECT GuardianID, DateOfBirth FROM tblmembers WHERE MemberNo = :memberNo AND DateOfBirth = :dob ");
        $stmt->bindParam(':memberNo', $memberNo, PDO::PARAM_STR);
        $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
       // $stmt->bindParam(':nationalID', $nationalID, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->execute()) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);   

        if ($user) {

                    //select GuardianIDno from tblguardians where GuardianID = $user['GuardianID']
                    $stmt2 = $pdo->prepare("SELECT GuardianIDno FROM tblguardians WHERE GuardianID = :guardianid");
                    $stmt2->bindParam(':guardianid', $user['GuardianID'], PDO::PARAM_STR);
                    $stmt2->execute();

                    $guardian = $stmt2->fetch(PDO::FETCH_ASSOC);

                    if ($guardian && $guardian['GuardianIDno'] == $nationalID) {
                        // Update the user's password in the database (replace 'users' with your table name)
                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                        $updateStmt = $pdo->prepare("UPDATE MobUsers SET password = :password WHERE memberid = :memberNo");
                        $updateStmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                        $updateStmt->bindParam(':memberNo', $memberNo, PDO::PARAM_STR);

                        if ($updateStmt->execute()) {
                            echo '<script>alert("Password reset succesful. You can now Login"); window.location = "login.php";</script>';
                        } else {
                            echo '<script>alert("Failed to reset Password"); window.location = "forgot-password.php";</script>';
                        }
                    } else {
                        echo '<script>alert("Guardian Information Mismatch. Try again"); window.location = "forgot-password.php";</script>';
                    }

        } else {
            echo '<script>alert("Beneficiary Information Mismatch. Try again"); window.location = "forgot-password.php";</script>';
        }
        } else {
            echo '<script>alert("Failed to reset. Try again"); window.location = "forgot-password.php";</script>';
        }
        





    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Fairlife</title>
    
    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="assets/vendor/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="assets/vendor/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <link rel="stylesheet" id="primaryColor" href="assets/css/blue-color.css">
    <link rel="stylesheet" id="rtlStyle" href="#">
</head>
<body>
    <!-- preloader start -->
    <div class="preloader d-none">
        <div class="loader">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <!-- preloader end -->

    <!-- theme color hidden button -->
    <button class="header-btn theme-color-btn d-none"><i class="fa-light fa-sun-bright"></i></button>
    <!-- theme color hidden button -->

    <!-- main content start -->
    <div class="main-content login-panel">
        <div class="login-body">
            <div class="top d-flex justify-content-between align-items-center">
                <div class="logo">
                    <img src="NewAssets/header2.png" alt="Logo">
                </div>
                
            </div>
            <div class="bottom">
                <h3 class="panel-title">Forgot Password</h3>
                <form method="post">
                    <div class="input-group mb-25">
                        <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                        <input type="text" name="memberno" class="form-control" placeholder="Member No">
                    </div>
                    <div class="input-group mb-20">
                        <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                        <input type="text" name="gdob" class="form-control rounded-end" placeholder="Beneficiary Date of Birth" onfocus="(this.type='date')"
        onblur="(this.type='text')">
                       
                    </div>
                    <div class="input-group mb-25">
                        <span class="input-group-text"><i class="fa fa-id-card" aria-hidden="true"></i></span>
                        <input type="number" name="nationalid" class="form-control" placeholder="Guardian National ID">
                    </div>
                    <div class="input-group mb-20">
                        <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                        <input type="password" name="password" class="form-control rounded-end" placeholder="New Password">
                        <a role="button" class="password-show"><i class="fa-duotone fa-eye"></i></a>
                    </div>
                    <button type="submit" name="login22" class="btn btn-warning w-100 login-btn">Sign in</button>
                </form>

            </div>
        </div>

        <!-- footer start -->
        <div class="footer">
            <p>Copyright© <script>document.write(new Date().getFullYear())</script> All Rights Reserved By <span class="text-primary">Fairlife</span></p>
        </div>
        <!-- footer end -->
    </div>
    <!-- main content end -->
    
    <script src="assets/vendor/js/jquery-3.6.0.min.js"></script>
    <script src="assets/vendor/js/jquery.overlayScrollbars.min.js"></script>
    <script src="assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>