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
?>





<!DOCTYPE html>
<html lang="en" data-menu="vertical" data-nav-size="nav-default">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deceased Profile | Fairlife</title>
    
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
            <h2>Deceased Information</h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="panel">
                    <div class="panel-header">
                        <nav>
                            <div class="btn-box d-flex flex-wrap gap-1" id="nav-tab" role="tablist">
                               

                            </div>
                        </nav>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content profile-edit-tab" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-edit-profile" role="tabpanel" aria-labelledby="nav-edit-profile-tab" tabindex="0">

<?php
try {
    // Establish a database connection and execute a query to get the member's information.
    $stmt = $pdo->prepare("SELECT DeceasedID FROM tblmembers WHERE MemberNo = :memberno");
    $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
    $stmt->execute();

    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt2 = $pdo->prepare("SELECT * FROM tbldeceased WHERE DeceasedID = :deceasedid");
    $stmt2->bindParam(':deceasedid', $member['DeceasedID'], PDO::PARAM_STR);
    $stmt2->execute();
    $deceased = $stmt2->fetch(PDO::FETCH_ASSOC);
?>
                                <form method="post">

                                   <div class="profile-edit-tab-title">
                                        <h6>View Information</h6>
                                    </div>
                                    <div class="private-information mb-25">
                                        <div class="row g-3">



                                            <div class="col-md-4 col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa-light fa-user"></i></span>
                                                    <input type="email" name="" class="form-control" placeholder="text" value="<?php echo $deceased['DeceasedSurname']; ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa-light fa-user"></i></span>
                                                    <input type="text" name="" class="form-control" placeholder="Primary Phone" value="<?php echo $deceased['DeceasedFirstnames']; ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa-light fa-fingerprint"></i></span>
                                                    <input type="text" name="" class="form-control" placeholder="Home Telephone" value="<?php echo $deceased['DeceasedIDnumber']; ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa-light fa-address-card"></i></span>
                                                    <input type="text" name="" class="form-control" placeholder="Postal Address" value="<?php echo $deceased['DateOfDeath']; ?>">
                                                </div>
                                            </div>

                                           
                                            </div>

                                            <!-- write code for save button -->
                                            
                                                
                                    </div>
                                    </div>


                                </form>

<?php
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
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