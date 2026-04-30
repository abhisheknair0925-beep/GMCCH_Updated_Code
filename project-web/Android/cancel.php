<?php
include('connect.php');

$crnumber = $_POST['crnumber'];
$token = $_POST['token'];
$date = $_POST['date'];

// $crnumber='1400001';


    $delete=mysqli_query($dbconnect,"DELETE FROM tbl_booking  where tbl_booking_crno='$crnumber' AND tbl_booking_token='$token' AND tbl_booking_date='$date'");
    //$count=mysqli_num_rows($delete);
    if($delete)
    {
       
    // echo $obj = json_encode($obj); 

        $response['error'] = false;
        $response['message'] = "Booking canceled";
        //$response['details']=$obj;
        echo json_encode($response);        
    }
    else
    {
        $response['error'] = true;
        $response['message'] = "Sorry!!";
        echo json_encode($response);
    }
?>