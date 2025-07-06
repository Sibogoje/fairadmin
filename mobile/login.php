<?php 
session_start();
include('config.php');


if (isset($_POST['login22'])) {
    $memberno = $_POST["memberno"];
    $password = $_POST["password"];
    
    // You should hash the password before comparing it with the stored hash.
    // Replace 'your_password_hash_column' with the actual column name in your users table where the hashed password is stored.
    
    
    
    
    
    try {
        
        
        // Step 1: Search memberno and dateofbirth from tblmembers.
        $stmt = $pdo->prepare("SELECT `MemberNo`, `Terminated` FROM `tblmembers` WHERE `MemberNo` = :memberno");
        $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
        $stmt->execute();
        
        $member = $stmt->fetch(PDO::FETCH_ASSOC);
        
        
        if($member['Terminated'] == 0){
        
    
        $stmt = $pdo->prepare("SELECT * FROM MobUsers WHERE memberid = :memberno");
        $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Password is correct, set session variables and update the database.
            
            // Set the session variable for memberno.
            $_SESSION['memberno'] = $memberno;
            
            // Update MobUsers.deviceinfo with logged device info.
            $deviceInfo = $_SERVER['HTTP_USER_AGENT']; // You can use other information as needed.
            $stmt = $pdo->prepare("UPDATE MobUsers SET deviceinfo = :deviceinfo WHERE memberid = :memberno");
            $stmt->bindParam(':deviceinfo', $deviceInfo, PDO::PARAM_STR);
            $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
            $stmt->execute();
            
            // Update MobUsers.online to 1 (device logged in).
            $stmt = $pdo->prepare("UPDATE MobUsers SET online = 1 WHERE memberid = :memberno");
            $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
            $stmt->execute();

            // Redirect to the secure page.
            header("Location: index.php");
            exit();
        } else {
            // Password is incorrect. Display an error message.
           // echo "Invalid login credentials. Please try again.";
            echo '<script>alert("Invalid login credentials. Please try again."); window.location = "login.php";</script>';
        }
        
        }else{
            
             echo '<script>alert("Account is terminated, please contact the Office..."); window.location = "login.php";</script>';
            
            
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
                    <img src="https://fairlifeadmin.com/mobile/NewAssets/header2.png" alt="Logo">
                </div>
                
            </div>
            <div class="bottom">
                <h3 class="panel-title">Login</h3>
                <form method="post">
                    <div class="input-group mb-25">
                        <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                        <input type="text" name="memberno" class="form-control" placeholder="Member No">
                    </div>
                    <div class="input-group mb-20">
                        <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                        <input type="password" name="password" class="form-control rounded-end" placeholder="Password">
                        <a role="button" class="password-show"><i class="fa-duotone fa-eye"></i></a>
                    </div>
                    <div class="d-flex justify-content-between mb-25">
                        <div class="form-check">
                        <a href="reg2.php" class="text-white fs-14">First Time Login</a>
                        </div>
                        <a href="forgot-password.php" class="text-white fs-14">Forgot Password?</a>
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