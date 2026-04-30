<?php
include("connect.php");
$unit_id = $_POST['unit_id'];

// $unit_id = '2';
$sel="SELECT * FROM tbl_doctor INNER JOIN tbl_units ON tbl_doctor.tbl_doctor_unit_id=tbl_units.tbl_unit_id WHERE tbl_units.tbl_unit_id='$unit_id'";
$result=mysqli_query($dbconnect,$sel);
if($result)
{
    $response["doctdetails"] = array();
    $reg_details = array();
    while ($row = mysqli_fetch_array($result))
    {
        array_push($reg_details,
            array('Id'=>$row[0],
               'Dr_name' => $row[1],
                'Dr_qualification' => $row[2],
               'discription' => $row[5],
               'image' => $row[9]
                
            ));
    }
    // $response["error"] = false;
    // $response["message"] = "success";
    $response["doctdetails"]=$reg_details;
    echo json_encode($response);

}
else
{
    // $response["error"] = true;
    // $response["message"] = "failed";
    echo json_encode($response);
}


?>