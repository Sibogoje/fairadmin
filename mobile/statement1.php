<?php

if (isset($_GET['from']) && isset($_GET['to'])) {
    $from = $_GET['from'];
    $to = $_GET['to'];
    $memberno = $_GET['memberno'];
    // Use $from and $to in your SQL query or other processing.
} else {
    // Handle the case where the parameters are not set.
    echo "Missing 'from' or 'to' parameters.";
}


require('fpdf/fpdf.php'); // Include the FPDF library
session_start(); // Start the session on the index page.
include('config.php');
//$memberno = $_SESSION['memberno'];

//$from = $_POST['from'];
//$to = $_POST['to'];
//$from = '2017-06-30';
//$to = '2023-09-20';

//select MemberID from tblmembers where memberno = $memberno
$stmt = $pdo->prepare("SELECT MemberID, MemberNo, MemberFirstname, MemberSurname FROM tblmembers WHERE MemberNo = :memberno");
$stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
$stmt->execute();

$member = $stmt->fetch(PDO::FETCH_ASSOC);
$fullname = "     ".$memberno. " - " .$member['MemberFirstname'] . ' ' . $member['MemberSurname'];
$memberid = $member['MemberID'];

try {
    // Fetch data from tblmemberaccounts
    $stmt = $pdo->prepare("SELECT TransactionDate, Details, StartingBalance, Amount, NewBalance, Credit FROM tblmemberaccounts WHERE memberID = :membernos AND TransactionDate BETWEEN :fromDate AND :toDate");
    $stmt->bindParam(':membernos', $memberid, PDO::PARAM_STR);
    $stmt->bindParam(':fromDate', $from, PDO::PARAM_STR); // Bind the start date
    $stmt->bindParam(':toDate', $to, PDO::PARAM_STR);     // Bind the end date
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

    // Create a new PDF document
    class PDF extends FPDF
    {
        function Header()
        {
            global $fullname; // Use the global variable for the member's full name

            
            // Add your header content here if needed
            $this->Image('NewAssets/header.PNG', 0, 0, 210); // Make the header image span the full width of the page
            
            // Add an underline below the header
            $this->SetY(30); // Adjust the Y position as needed
            $this->SetDrawColor(0, 0, 0); // Set the line color to black
            $this->Line(10, $this->GetY(), 200, $this->GetY()); // Draw a line spanning the full width
            
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(80);
        
            // Add the "Member Statement" text
            $this->SetTextColor(0, 0, 0); // Reset text color to black
            $this->Cell(3, 10, '      '.$fullname, 0, 0, 'C'); // Make it span the full width
            $this->Ln(10); // Add some space after the text
        
            // Add row headers and center-align them with smaller font size
            $this->SetFont('Arial', 'B', 10); // Reduce font size to 10
            $this->Cell(30, 10, 'Transaction Date', 1, 0, 'C');
            $this->Cell(78, 10, 'Details', 1, 0, 'C');
            $this->Cell(30, 10, 'Starting Balance', 1, 0, 'C');
            $this->Cell(20, 10, 'Amount', 1, 0, 'C');
            $this->Cell(30, 10, 'New Balance', 1, 0, 'C');
            $this->Ln(); // Move to the next line for data
        }
        

        function Footer()
        {
            // Add your footer content here if needed
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();

    // Set font and size
    $pdf->SetFont('Arial', '', 12);

    // Loop through the data and create the PDF content
    foreach ($data as $row) {
        $transactionDate = $row['TransactionDate'];
        $details = $row['Details'];
        $startingBalance = $row['StartingBalance'];
        $amount = $row['Amount'];
        $newBalance = $row['NewBalance'];
        $credit = $row['Credit'];

        // Determine whether to add "+" or "-" based on the "Credit" column
        $amountFormatted = ($credit == 1) ? '+' . $amount : '-' . $amount;
        if ($credit == 1) {
            $pdf->SetTextColor(0, 128, 0); // Green for positive amounts
        } else {
            $pdf->SetTextColor(255, 0, 0); // Red for negative amounts
        }

        //set font to 10 pt
        $pdf->SetFont('Arial', '', 10); 


    
                // Add data to the PDF
        $pdf->Cell(30, 10, $transactionDate, 1, 0, 'C');
        $pdf->Cell(78, 10, $details, 1, 0, 'L');
        $pdf->Cell(30, 10, $startingBalance, 1, 0, 'C');
        $pdf->Cell(20, 10, $amountFormatted, 1, 0, 'C');
        $pdf->Cell(30, 10, $newBalance, 1, 0, 'C');

        

        $pdf->SetTextColor(0, 0, 0); // Reset text color to black

        $pdf->Ln(); // Move to the next line for the next row
    }

    // Output the PDF for download
    $pdf->Output('member_statement.pdf', 'D'); // 'D' forces download
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
