<?php
include('connect.php');

$crnumber=$_POST['crnumber'];


// $crnumber='1400001';


    $result=mysqli_query($dbconnect,"SELECT * from tbl_user where tbl_user_crno='$crnumber'");
    $count=mysqli_num_rows($result);
    if($count>0)
    {
        while ($row = mysqli_fetch_array($result))
        {
            $obj=array('id' => $row[0],
                'Name' => $row[2],
                'CR_number' => $row[1],
                'Age' => $row[3],
                'Gender'=>$row[4],
                
            );

        }
    // echo $obj = json_encode($obj); 

        $response["error"] = false;
        $response["message"] = "Login Success";
        $response['details']=$obj;
        echo json_encode($response);        
    }
    else
    {
        $response["error"] = true;
        $response["message"] = "Incorrect CR number";
        echo json_encode($response);
    }
?>