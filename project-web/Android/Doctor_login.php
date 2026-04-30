<?php
include('connect.php');

$regno=$_POST['regno'];
$pass=$_POST['pass'];

// $email='abhishek@gmail.com';
// $pass ='Doctors';

    $result=mysqli_query($dbconnect,"SELECT * from tbl_doctor where tbl_doctor_regno='$regno' and tbl_doctor_pass='$pass'");
    $count=mysqli_num_rows($result);
    if($count>0)
    {
        while ($row = mysqli_fetch_array($result))
        {
            $obj=array('id' => $row[0],
                'Name' => $row[1],
                'Qualification' => $row[2],
                'Unitid' => $row[3],
                'Phone'=>$row[4],
                'Gender'=>$row[6],
                 'Department'=>$row[5],
                  'regno'=>$row[7],
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
        $response["message"] = "Incorrect id or password";
        echo json_encode($response);
    }
?>