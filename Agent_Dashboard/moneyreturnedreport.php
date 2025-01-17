<?php
session_start();
Require '../Functions/connect.php';
Require 'checksession.php';
if (isset($_SESSION['user'])){
    $var_session=$_SESSION["user"];
    $user_query = mysqli_query($conn,"select * from agent_reg where email='$var_session'");
$user_data = mysqli_fetch_assoc($user_query);
$lender_id=$user_data['id'];
$acc_no=$user_data['account_number'];

//include connection file
include_once("./db_config.php");
include_once('fpdf/fpdf.php');
 
class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
    // $this->Image('./images/address-book/png',10,-1,70);
    $this->SetFont('Arial','B',16);
    // Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(80,10,'Money Returned Transactions',1,1,'C');
    // Line break
    $this->Ln(20);
}
 
// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}
 
$db = new dbObj();
$connString =  $db->getConnstring();
 
$result =
mysqli_query($connString, 
"SELECT  lender_id,total_amount, expected_commision , unique_code FROM agent_returns where agent_account_number='$acc_no'") or die("database error:". mysqli_error($connString));

$header = mysqli_query($connString, "SHOW columns FROM agent_returns ");
 
$pdf = new PDF();
//header
$pdf->AddPage("L");
//foter page
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',14);
// foreach($header as $heading) {
// $pdf->Cell(40,12,$display_heading[$heading['Field']],1);
// }
$pdf->Ln();
$pdf->Cell(40,12,'Lender_ID' );
$pdf->Cell(40,12,'Total Amount');
$pdf->Cell(40,12,'Commision ');
$pdf->Cell(40,12,'Unique_Code');
// $pdf->Cell(40,12,'marital');
// $pdf->Cell(40,12,'Email');
$pdf->Ln();
if(mysqli_num_rows($result) > 0){
    $pdf->SetFont('Arial','',12);
    $pdf->SetFont('');
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(40,12,$row['lender_id'] ,1 ,0);
        $pdf->Cell(40,12,$row['total_amount'],1 ,0);
        $pdf->Cell(40,12,$row['expected_commision'] ,1 ,0);
        $pdf->Cell(40,12,$row['unique_code'] ,1 ,0);
        // $pdf->Cell(40,12,$row['marital'] ,1 ,0);
        // $pdf->Cell(70,12,$row['email'] ,1 ,0);
        $pdf->Ln();
    
    }
}
// foreach($result as $row) {
// $pdf->Ln();
// foreach($row as $column)
// $pdf->Cell(50,12,$column,1);
// }
$pdf->Output();
 
    }
    else {
        echo "<script>
                location.replace('login.php');
            </script>";
    }
?>