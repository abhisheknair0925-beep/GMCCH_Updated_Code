<?php
include("connect.php");

$Cr_number = $_POST['Cr_number'];
$token = $_POST['token'];
$date = date('y-m-d');

// $Cr_number = '1400011';
// $token = '8';
// $date = date('d-m-yy');

$sel="UPDATE tbl_booking SET tbl_booking_status='Visited' WHERE tbl_booking_crno='$Cr_number' AND tbl_booking_date='$date' AND tbl_booking_token='$token'";
$result=mysqli_query($dbconnect,$sel);
if ($result) {
                $response['error'] = false;
                $response['message'] = 'Status changed';
                echo json_encode($response);

            } else {
                $response['error'] = true;
                $response['message'] = 'Unable change';
                echo json_encode($response);
            }

?>