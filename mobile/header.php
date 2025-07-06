    <!-- header start -->
    <div class="header">
        <div class="row g-0 align-items-center">
            <div class="col-xxl-6 col-xl-5 col-4 d-flex align-items-center gap-20">

                <div class="nav-close-btn">
                    <button id="navClose"><i class="fa-light fa-bars-sort"></i></button>
                </div>
               
            </div>
            <div class="col-4 d-lg-none">
                <div class="mobile-logo">
                    
                    <span class="logo-txt text-white" style="">Fairlife</span> 
                    </a>
                </div>
            </div>
            <div class="col-xxl-6 col-xl-7 col-lg-8 col-4">
                <div class="header-right-btns d-flex justify-content-end align-items-center">

                 
                      <div class="header-btn-box profile-btn-box">
                        <button class="profile-btn" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="assets/images/admin.png" alt="image">
                        </button>
                        <ul class="dropdown-menu profile-dropdown-menu">

<?php
include('config.php');
// Assuming you have a valid $memberno
//$memberno = 1236; // Replace with the actual memberno you want to query.
$memberno = $_SESSION['memberno'];
$stmt = $pdo->prepare("SELECT CONCAT(MemberSurname, ' ', MemberFirstname) AS FullName FROM tblmembers WHERE MemberNo = :memberno");

if ($stmt) {
    $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $fullName = $result['FullName'];

            // Display the full name.
            echo '<li>
                    <div class="dropdown-txt text-center">
                        <p class="mb-0">' . $fullName . '</p>
                        <span class="d-block">Beneficiary</span>
                        <div class="d-flex justify-content-center">
                        </div>
                    </div>
                  </li>';
        } else {
            // Handle the case where the memberno does not exist in the table.
            echo "Member not found";
        }
    } else {
        // Handle the execute() failure here.
        die("Execute failed: " . implode(" ", $stmt->errorInfo()));
    }
} else {
    // Handle the prepare() failure here.
    die("Prepare failed: " . implode(" ", $pdo->errorInfo()));
}

?>

                            <li><a class="dropdown-item" href="messages.php"><span class="dropdown-icon"><i class="fa-regular fa-message-lines"></i></span> Message</a></li>

                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="deceased.php"><span class="dropdown-icon"><i class="fa-solid fa-id-card-clip"></i></span>Deceased Information</a></li>
                            <li><a class="dropdown-item" href="edit-profile.php"><span class="dropdown-icon"><i class="fa-regular fa-gear"></i></span>Profile Settings</a></li>
                            <li><a class="dropdown-item" href="logout.php"><span class="dropdown-icon"><i class="fa-regular fa-arrow-right-from-bracket"></i></span> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
                        <div class="col-xxl-6 col-xl-7 col-lg-8 col-4">
                <div class="header-right-btns d-flex justify-content-end align-items-center">
                    <div class="header-collapse-group">
                        <div class="header-right-btns d-flex justify-content-end align-items-center p-0">
     
                            <div class="header-right-btns d-flex justify-content-end align-items-center p-0">



                                <div class="header-btn-box">
                                    <div class="dropdown">
                                        <button class="header-btn" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                            <i class="fa-light fa-calculator"></i>
                                        </button>
                                        <ul class="dropdown-menu calculator-dropdown">
                                            <div class="dgb-calc-box">
                                                <div>
                                                    <input type="text" id="dgbCalcResult" placeholder="0" autocomplete="off" readonly>
                                                </div>
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td class="bg-danger">C</td>
                                                            <td class="bg-secondary">CE</td>
                                                            <td class="dgb-calc-oprator bg-primary">/</td>
                                                            <td class="dgb-calc-oprator bg-primary">*</td>
                                                        </tr>
                                                        <tr>
                                                            <td>7</td>
                                                            <td>8</td>
                                                            <td>9</td>
                                                            <td class="dgb-calc-oprator bg-primary">-</td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>5</td>
                                                            <td>6</td>
                                                            <td class="dgb-calc-oprator bg-primary">+</td>
                                                        </tr>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>2</td>
                                                            <td>3</td>
                                                            <td rowspan="2" class="dgb-calc-sum bg-primary">=</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">0</td>
                                                            <td>.</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                                <button class="header-btn fullscreen-btn" id="btnFullscreen"><i class="fa-light fa-expand"></i></button>
                                <button class="header-btn theme-color-btn"><i class="fa-light fa-sun-bright"></i></button>
                            </div>
                        </div>
                    </div>
                    <!--<button class="header-btn header-collapse-group-btn d-lg-none"><i class="fa-light fa-ellipsis-vertical"></i></button>-->

                </div>
            </div>
        </div>
    </div>
    <!-- header end -->

    <!-- main sidebar start -->
    <div class="main-sidebar">
        <div class="main-menu">
            <ul class="sidebar-menu scrollable">
                <li class="sidebar-item open">
                    <a role="button" href="index.php" class="sidebar-link-group-title">Dashboard</a>
                </li>
                <li class="sidebar-item">
                    <a role="button" class="sidebar-link-group-title has-sub">Transactions</a>
                    <ul class="sidebar-link-group">

                        <li class="sidebar-dropdown-item">
                            <a href="adhoc.php" class="sidebar-link"><span class="nav-icon"><i class="fa-light fa-plus"></i></span> <span class="sidebar-txt">New Adhoc Request </span></a>
                        </li>
                        <li class="sidebar-dropdown-item">
                            <a href="adhoc-pending.php" class="sidebar-link"><span class="nav-icon"><i class="fa-light fa-spinner"></i></span> <span class="sidebar-txt">Pending Adhoc Requests</span></a>
                        </li>
                        <li class="sidebar-dropdown-item">
                            <a href="adhoc-completed.php" class="sidebar-link"><span class="nav-icon"><i class="fa-light fa-list"></i></span> <span class="sidebar-txt">Completed Adhoc Request</span></a>
                        </li>
                        <li class="sidebar-dropdown-item">
                            <a href="regular.php" class="sidebar-link"><span class="nav-icon"><i class="fa-light fa-list"></i></span> <span class="sidebar-txt">Regular Payments</span></a>
                        </li>
    
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a role="button" class="sidebar-link-group-title has-sub">Statements</a>
                    <ul class="sidebar-link-group">

                        <li class="sidebar-dropdown-item">
                            <a href="statement.php" class="sidebar-link"><span class="nav-icon"><i class="fa-light fa-plus"></i></span> <span class="sidebar-txt">Beneficiary Statement</span></a>
                        </li>
                        
    
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- main sidebar end -->
    
    <script>
// Get references to the button and sidebar elements
const navCloseButton = document.getElementById('navClose');
const mainSidebar = document.querySelector('.main-sidebar');

// Add a click event listener to the button
navCloseButton.addEventListener('click', function() {
  // Toggle the 'sidebar-min' class on the mainSidebar element
  mainSidebar.classList.toggle('sidebar-mini');
});

    </script>
    

