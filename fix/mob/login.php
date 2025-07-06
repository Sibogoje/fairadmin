<?php
session_start();

// Database connection configuration

$host = "194.5.156.43";
$username = "u747325399_fair2";
$password = "Fairline@151022";
$dbname   = 'u747325399_fair2';

// Create a PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_POST['loginbtn'])) {
    $memberno = $_POST['memberno'];
    $password = $_POST['password'];

    // Validate user input here (e.g., check for empty fields)

    // Hash the password for comparison
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the query to retrieve user data
    $stmt = $pdo->prepare("SELECT * FROM MobUsers WHERE memberid = :memberno");
    $stmt->bindParam(':memberno', $memberno);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    //if ($user && password_verify($password, $user['password'])) {
        if ($user && $password) {
        // Password is correct, create a session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // Redirect to a secure page after login
        header("Location: secure_page.php");
        exit();
    } else {
        // Invalid login credentials
        $error = "Invalid username or password";
    }
}
?>





<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html>
    
<head>
	<title>My - FairLife</title>
	<link rel="icon" type="image/x-icon" href="logo1.png">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
	<link rel="stylesheet" href="sty.css?version=5">
</head>
<!--Coded with love by Mutiullah Samim-->
<body>
	<div class="container h-100">
		<div class="d-flex justify-content-center h-100">
			<div class="user_card">
				<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
						<img src="logo1.png" class="brand_logo" alt="Logo">
					</div>
				</div>
				<div class="d-flex justify-content-center form_container">
					<form method="POST" action="" >
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" name="memberno" class="form-control input_user" value="" placeholder="Member No">
						</div>
						<div class="input-group mb-2">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input type="password" name="password" class="form-control input_pass" value="" placeholder="password">
						</div>
						<div class="form-group">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="customControlInline">
								<label class="custom-control-label" for="customControlInline">Remember me</label>
							</div>
						</div>
							<div class="d-flex justify-content-center mt-3 login_container">
				 	<button type="submit" name="loginbtn" class="btn login_btn">Login</button>
				   </div>
					</form>
				
				</div>
		
				<div class="mt-4">
					<div class="d-flex justify-content-center links">
						First Time Sign-In <a href="#" class="ml-2">Register</a>
					</div>
					<div class="d-flex justify-content-center links">
						<a href="#">Forgot your password?</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
