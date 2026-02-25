<!DOCTYPE html>
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
session_start();
if(isset($_SESSION['zid']))
{
$gg = $_SESSION['user'];
require_once '../scripts/connection.php';

// Check if viewing a specific member profile
$viewingProfile = false;
$memberData = null;
$deceasedData = null;
$guardianData = null;
$nextOfKinData = null;
$relatedMembers = null;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $memberId = (int)$_GET['id'];
    $viewingProfile = true;
    
    // Fetch member data
    $memberQuery = "
        SELECT m.*, b.NewBalance
        FROM tblmembers m
        LEFT JOIN balances b ON m.MemberID = b.memberID AND b.Term = 0
        WHERE m.MemberID = $memberId
    ";
    $memberResult = mysqli_query($conn, $memberQuery);
    if ($memberResult && mysqli_num_rows($memberResult) > 0) {
        $memberData = mysqli_fetch_assoc($memberResult);
        
        // Fetch deceased data
        if ($memberData['DeceasedID']) {
            $deceasedQuery = "SELECT * FROM tbldeceased WHERE DeceasedID = " . (int)$memberData['DeceasedID'];
            $deceasedResult = mysqli_query($conn, $deceasedQuery);
            if ($deceasedResult) {
                $deceasedData = mysqli_fetch_assoc($deceasedResult);
            }
        }
        
        // Fetch guardian data
        if ($memberData['GuardianID']) {
            $guardianQuery = "SELECT * FROM tblguardians WHERE GuardianID = " . (int)$memberData['GuardianID'];
            $guardianResult = mysqli_query($conn, $guardianQuery);
            if ($guardianResult) {
                $guardianData = mysqli_fetch_assoc($guardianResult);
            }
        }
        
        // Fetch next of kin data
        if ($memberData['NextOfKinID']) {
            $kinQuery = "SELECT * FROM tblnextofkin WHERE NextOfKinID = " . (int)$memberData['NextOfKinID'];
            $kinResult = mysqli_query($conn, $kinQuery);
            if ($kinResult) {
                $nextOfKinData = mysqli_fetch_assoc($kinResult);
            }
        }
        
        // Fetch related members (others linked to the same deceased)
        if ($memberData['DeceasedID']) {
            $relatedQuery = "
                SELECT m.MemberID, m.MemberNo, m.MemberSurname, m.MemberFirstname, 
                       b.NewBalance, m.Terminated
                FROM tblmembers m
                LEFT JOIN balances b ON m.MemberID = b.memberID AND b.Term = 0
                WHERE m.DeceasedID = " . (int)$memberData['DeceasedID'] . "
                AND m.MemberID != $memberId
                ORDER BY m.MemberNo
            ";
            $relatedResult = mysqli_query($conn, $relatedQuery);
            if ($relatedResult) {
                $relatedMembers = [];
                while ($row = mysqli_fetch_assoc($relatedResult)) {
                    $relatedMembers[] = $row;
                }
            }
        }
    }
} 

?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Member Balance Profile</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
<script src='../jquery-3.2.1.min.js' type='text/javascript'></script>

        <link href='../select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

  <!-- Favicons -->
  <link href="<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo APP_URL; ?>logo.png" rel="icon">
  <link href="<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo APP_URL; ?>logo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <script src='../select2/dist/js/select2.min.js' type='text/javascript'></script>
  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.2.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <?php
require_once __DIR__ . '/../scripts/bootstrap.php';
include '../header.php'; ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Member Balance Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dash.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Balance Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <?php if ($viewingProfile && $memberData): ?>
        <!-- PROFILE VIEW MODE -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><?php echo htmlspecialchars($memberData['MemberSurname'] . ' ' . $memberData['MemberFirstname']); ?></h2>
                    <a href="../dash.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
                </div>
            </div>
        </div>

        <!-- Member Information Card -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-person"></i> Member Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Member Number</label>
                                <p class="mb-3"><strong><?php echo htmlspecialchars($memberData['MemberNo']); ?></strong></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">National ID</label>
                                <p class="mb-3"><strong><?php echo htmlspecialchars($memberData['MemberIDnumber'] ?? 'N/A'); ?></strong></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Full Name</label>
                                <p class="mb-3"><strong><?php echo htmlspecialchars($memberData['MemberSurname'] . ' ' . $memberData['MemberFirstname']); ?></strong></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Gender</label>
                                <p class="mb-3"><strong><?php echo htmlspecialchars($memberData['Gender'] ?? 'N/A'); ?></strong></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Date of Birth</label>
                                <p class="mb-3">
                                    <strong>
                                        <?php 
                                        if ($memberData['DateOfBirth']) {
                                            echo date('d M Y', strtotime($memberData['DateOfBirth']));
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </strong>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Date Joined</label>
                                <p class="mb-3">
                                    <strong>
                                        <?php 
                                        if ($memberData['DateAccountOpened']) {
                                            echo date('d M Y', strtotime($memberData['DateAccountOpened']));
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </strong>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label class="text-muted small">Current Balance</label>
                                <p>
                                    <span class="badge bg-<?php echo ($memberData['NewBalance'] < 10000) ? 'danger' : 'success'; ?> fs-6">
                                        E <?php echo number_format($memberData['NewBalance'] ?? 0, 2); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deceased Information Card -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-person-fill-slash"></i> Deceased Information</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($deceasedData): ?>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="text-muted small">Full Name</label>
                                    <p class="mb-3"><strong><?php echo htmlspecialchars($deceasedData['DeceasedSurname'] . ' ' . $deceasedData['DeceasedFirstnames']); ?></strong></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">ID Number</label>
                                    <p class="mb-3"><strong><?php echo htmlspecialchars($deceasedData['DeceasedIDnumber'] ?? 'N/A'); ?></strong></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Date of Death</label>
                                    <p class="mb-3">
                                        <strong>
                                            <?php 
                                            if ($deceasedData['DateOfDeath']) {
                                                echo date('d M Y', strtotime($deceasedData['DateOfDeath']));
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </strong>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label class="text-muted small">Total Funds</label>
                                    <p><strong>E <?php echo number_format($deceasedData['TotalFunds'] ?? 0, 2); ?></strong></p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">No deceased information available</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guardian & Next of Kin Cards -->
        <div class="row mb-4">
            <!-- Guardian Information -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-shield-check"></i> Guardian Information</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($guardianData): ?>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="text-muted small">Full Name</label>
                                    <p class="mb-3"><strong><?php echo htmlspecialchars($guardianData['GuardianSurname'] . ' ' . $guardianData['GuardianFirstNames']); ?></strong></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="text-muted small">ID Number</label>
                                    <p class="mb-3"><strong><?php echo htmlspecialchars($guardianData['GuardianIDno'] ?? 'N/A'); ?></strong></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="text-muted small">Postal Address</label>
                                    <p class="mb-3"><strong><?php echo htmlspecialchars($guardianData['GuardianPostalAddress'] ?? 'N/A'); ?></strong></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">Work Phone</label>
                                    <p class="mb-3"><strong><?php echo htmlspecialchars($guardianData['GuardianTelWork'] ?? 'N/A'); ?></strong></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Home Phone</label>
                                    <p class="mb-3"><strong><?php echo htmlspecialchars($guardianData['GuardianTelHome'] ?? 'N/A'); ?></strong></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">Cell Phone</label>
                                    <p class="mb-3"><strong><?php echo htmlspecialchars($guardianData['GuardianCell'] ?? 'N/A'); ?></strong></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Email</label>
                                    <p class="mb-3"><strong><?php echo htmlspecialchars($guardianData['GuardianEmail'] ?? 'N/A'); ?></strong></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label class="text-muted small">Relationship</label>
                                    <p><strong><?php echo htmlspecialchars($memberData['RelationshipGuardian'] ?? 'N/A'); ?></strong></p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">No guardian information available</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Next of Kin Information -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0"><i class="bi bi-people"></i> Next of Kin Information</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($nextOfKinData): ?>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="text-muted small">Full Name</label>
                                    <p class="mb-3"><strong><?php echo htmlspecialchars($nextOfKinData['KinSurname'] . ' ' . $nextOfKinData['KinFirstNames']); ?></strong></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="text-muted small">Postal Address</label>
                                    <p class="mb-3"><strong><?php echo htmlspecialchars($nextOfKinData['KinPostalAddress'] ?? 'N/A'); ?></strong></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">Work Phone</label>
                                    <p class="mb-3"><strong><?php echo htmlspecialchars($nextOfKinData['KinTelWork'] ?? 'N/A'); ?></strong></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Home Phone</label>
                                    <p class="mb-3"><strong><?php echo htmlspecialchars($nextOfKinData['KinTelHome'] ?? 'N/A'); ?></strong></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">Cell Phone</label>
                                    <p class="mb-3"><strong><?php echo htmlspecialchars($nextOfKinData['KinCell'] ?? 'N/A'); ?></strong></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Email</label>
                                    <p class="mb-3"><strong><?php echo htmlspecialchars($nextOfKinData['KinEmail'] ?? 'N/A'); ?></strong></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label class="text-muted small">Relationship</label>
                                    <p><strong><?php echo htmlspecialchars($memberData['RelationshipNextOfKin'] ?? 'N/A'); ?></strong></p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">No next of kin information available</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Members Card -->
        <?php if ($relatedMembers && count($relatedMembers) > 0): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0"><i class="bi bi-diagram-3"></i> Other Members of <?php echo htmlspecialchars(($deceasedData['DeceasedSurname'] ?? '') . ' ' . ($deceasedData['DeceasedFirstnames'] ?? '')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Member No</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Balance</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($relatedMembers as $relMember): ?>
                                            <tr>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <?php echo htmlspecialchars($relMember['MemberNo']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($relMember['MemberSurname'] . ' ' . $relMember['MemberFirstname']); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $relMember['Terminated'] == 0 ? 'bg-success' : 'bg-danger'; ?>">
                                                        <?php echo $relMember['Terminated'] == 0 ? 'Active' : 'Terminated'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo ($relMember['NewBalance'] < 10000) ? 'danger' : 'success'; ?>">
                                                        E <?php echo number_format($relMember['NewBalance'] ?? 0, 2); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="balanceprofile.php?id=<?php echo $relMember['MemberID']; ?>" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="View Member Profile">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- NO PROFILE SELECTED -->
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Please select a member from the dashboard low balance list to view their detailed profile.
        </div>
        <a href="../dash.php" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    <?php endif; ?>
 
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.min.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

</body>

</html>
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
}else{
    header('Location: ' . APP_URL . '');
}

?>
