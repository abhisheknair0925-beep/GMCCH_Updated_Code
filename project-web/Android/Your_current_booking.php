<?php
include("connect.php");
$Cr_number=$_POST['Cr_number'];
// $Cr_number="1400011";

$sel="SELECT * FROM tbl_booking WHERE tbl_booking_crno='$Cr_number'ORDER BY tbl_booking_date DESC";
$result=mysqli_query($dbconnect,$sel);
if($result)
{
    $response["mybookingdetails"] = array();
    $reg_details = array();
    while ($row = mysqli_fetch_array($result))
    {
        array_push($reg_details,
            array('Id'=>$row[0],
                'Unit_id' => $row[3],
                'Date' => $row[6],
                'Token' => $row[7],
                 'Booking_status' => $row[8],
               'Cr_number' => $row[1],
                
            ));
    }
    // $response["error"] = false;
    // $response["message"] = "success";
    $response['mybookingdetails']=$reg_details;
    echo json_encode($response);

}
else
{
    $response["error"] = true;
    $response["message"] = "failed";
    echo json_encode($response);
}


?>