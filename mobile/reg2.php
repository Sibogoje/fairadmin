<?php 
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $memberno = $_POST["memberno"];
    $bendate = $_POST["bendate"];
    $guardian_idnumber = $_POST["guardian_idnumber"];
    $password1 = $_POST["password1"];

    try {
        // Check if member is already registered in MobUsers.
        $stmt = $pdo->prepare("SELECT * FROM MobUsers WHERE memberid = :memberno");
        $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
        $stmt->execute();

        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            // Member is already registered, show an alert and redirect to login.php.
            echo '<script>alert("Member is already registered. Please login instead.");</script>';
            header("Location: login.php"); // Change this to the actual login page URL.
            exit();
        }

        // Step 1: Search memberno and dateofbirth from tblmembers.
        $stmt = $pdo->prepare("SELECT `MemberNo`, `DateOfBirth`, `GuardianID`, `Terminated` FROM `tblmembers` WHERE `MemberNo` = :memberno");
        $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
        $stmt->execute();
        
        $member = $stmt->fetch(PDO::FETCH_ASSOC);
        
        
        if($member['Terminated'] == 0){

        if ($member && $member['DateOfBirth'] == $bendate) {
            // Step 2: Check if guardian_idnumber matches in tblguardian.
            $guardian_idnumber = strtoupper($guardian_idnumber); // Convert to uppercase for case-insensitive comparison.

            $stmt = $pdo->prepare("SELECT GuardianIDno FROM tblguardians WHERE GuardianID = :guardianid");
            $stmt->bindParam(':guardianid', $member['GuardianID'], PDO::PARAM_STR);
            $stmt->execute();
            
            $guardian = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($guardian && $guardian['GuardianIDno'] == $guardian_idnumber) {
                // Step 3: Save memberno in MobUsers with a hashed password.
                $hashedPassword = password_hash($password1, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO MobUsers (memberid, password) VALUES (:memberno, :password)");
                $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $stmt->execute();

                echo '<script>alert("Registration successful!"); window.location = "index.php";</script>';
            } else {
                echo '<script>alert("Guardian ID number does not match."); window.location = "reg2.php";</script>';
            }
        } else {
            echo '<script>alert("Member number or date of birth does not match."); window.location = "reg2.php";</script>';
        }
        
        } else{
             echo '<script>alert("Account is Terminated.."); window.location = "reg2.php";</script>';
            
        }
        
        
        
        
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration | Fairlife</title>
          
    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="assets/vendor/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="assets/vendor/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css?version=2">
    <link rel="stylesheet" id="primaryColor" href="assets/css/blue-color.css">
    <link rel="stylesheet" id="rtlStyle" href="#">
</head>
<body>
    <!-- preloader start -->
    <!-- preloader end -->

    <!-- main content start -->
    <div class="main-content login-panel" style="margin-bottom: 40;">
        <div class="login-body">
            <div class="top d-flex justify-content-between align-items-center">
                <div class="logo" class="top d-flex justify-content-between align-items-center">
                    <img src="NewAssets/header2.png" alt="Logo">
                </div>
                
            </div>
            <div class="bottom">
                <h3 class="panel-title">Registration</h3>
                <form method="post">
                    <div class="input-group mb-25">
                        <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                        <input type="text" name="memberno" class="form-control" placeholder="Member No">
                    </div>
                    <div class="input-group mb-25">
                        <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                        <input type="text" name="bendate" class="form-control" placeholder="Beneficiary DOB - DD/MM/YYY" onfocus="(this.type='date')" onblur="(this.type='text')">
                    </div>
                    <div class="input-group mb-25">
                        <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                        <input type="text" name="guardian_idnumber" class="form-control rounded-end" placeholder="Guardian ID Number">
                    </div>
                    <div class="input-group mb-25">
                        <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                        <input type="password" name="password1" class="form-control rounded-end" placeholder="Password">
                        <a role="button" class="password-show"><i class="fa-duotone fa-eye"></i></a>
                    </div>
                    
                    <button class="btn btn-warning w-100 login-btn">Register Now</button>

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