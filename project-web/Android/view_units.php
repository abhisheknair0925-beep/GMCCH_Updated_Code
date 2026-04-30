<?php
include("connect.php");


$sel="SELECT * FROM tbl_units";
$result=mysqli_query($dbconnect,$sel);
if($result)
{
    $response["unitdetails"] = array();
    $reg_details = array();
    while ($row = mysqli_fetch_array($result))
    {
        array_push($reg_details,
            array('Id'=>$row[0],
               'Unit_name' => $row[1],
                'Unit_time' => $row[2]
               
                
            ));
    }
    // $response["error"] = false;
   // $response["message"] = "success";
    $response['unitdetails']=$reg_details;
    echo json_encode($response);

}
else
{
    // $response["error"] = true;
    // $response["message"] = "failed";
    echo json_encode($response);
}


?>