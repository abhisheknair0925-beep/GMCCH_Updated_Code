<?php
include("connect.php");
$date = date('y-m-d');
$id = $_POST['unitid'];

// $id='3';

$sel="SELECT * FROM tbl_booking  WHERE tbl_booking_date='$date' AND tbl_booking_unit='$id' AND tbl_booking_status='Approved'";
$result=mysqli_query($dbconnect,$sel);
$count=mysqli_num_rows($result);
if($result)
{
    // echo($date);
    $response["details"] = array();
    $reg_details = array();
    while ($row = mysqli_fetch_array($result))
    {
        array_push($reg_details,
            array('Id'=>$row[0],
               'Cr_number' => $row[1],
                'User_name' => $row[2],
                'Unit_name' => $row[3],
                'token_no' => $row[7],
                   'Booking_status' => $row[8],
                   
               
                
            ));
    }
    // $response["error"] = false;
    // $response["message"] = "success";
   $response['No_of_bookings']=$count; 

    $response['details']=$reg_details;
    echo json_encode($response);

 
 // echo json_encode($respons);
}
else
{
    // $response["error"] = true;
    // $response["message"] = "failed";
    echo json_encode($response);
}


?>