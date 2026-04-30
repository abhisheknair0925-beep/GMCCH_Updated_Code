<?php
include('democonnect.php');
$time=date_default_timezone_set("Asia/Kolkata");

 $Cr_number = $_POST['Cr_number'];
 $User_name = $_POST['User_name'];
// $Unit_id = $_POST['Unit_id'];
 $Type = $_POST['Type'];

 // $Cr_number = '1400012';
 // $User_name = 'abc';
 // $Unit_id = '2';
 //$day = $_POST['day'];
$date = date('Y-m-d H:i:s');
// $date = '2021-06-19';
$next_date = date('Y-m-d', strtotime($date .' +1 day'));
  
$day = date('D', strtotime($next_date));
//$day='sunday';
switch ($day) {
    case 'Mon':
        $Unit_id = '1';
        break;
    case 'Tue':
        $Unit_id = '2';
        break;
    case 'Wed':
        $Unit_id = '1';
        break;
    case 'Thu':
        $Unit_id = '2';
        break;
    case 'Fri':
        $Unit_id = '1';
        break;
    case 'Sat':
        $Unit_id = '2';
        break;
    default :
        $Unit_id = '0';

        break;
    
    
}
  $time=date('h:i A');
 //$time = '10:55 AM';
// echo '<br>';

 $start = $next_date. " 12:00 AM";
// echo '<br>';
 $end = $next_date. " 11:59 PM";
// echo '<br>';
 $date1 = date('Y-m-d').' ' .$time;
// echo '<br>';
  $date2 = $start;
// echo '<br>';
  $date3 = $end;

// die('fffff');

// SELECT * FROM tbl_booking WHERE tbl_booking_date >= date('d-m-yy') - INTERVAL 1 DAY
 // $date = date('y-m-d');;
 // $Cr_number ='1400005';
 // $User_name = 'aaa';
 // $Unit_id='1';
if($Unit_id==0)
{
    $response['error'] = true;
    $response['message'] = 'Sorry Booking not available';
    echo json_encode($response); 
}
else
{


if($Type=="chemo")
{

     if ($date1 < $date2 && $date1 < $date3 && $date)
        {
            //echo 'here';
        $sq1=mysqli_query($dbconnect,"SELECT max(tbl_booking_token) as t1 FROM tbl_booking where tbl_booking_date='$next_date' AND tbl_booking_from='User' AND tbl_booking_type='chemo'");
        $row=mysqli_fetch_array($sq1);
         $t1=$row['t1'];
        if($t1>88)
        {
            $response['error'] = true;
            $response['message'] = 'Booking full';
            echo json_encode($response);
        }
        else
        {
        $sq=mysqli_query($dbconnect,"SELECT * from tbl_booking where tbl_booking_crno='$Cr_number' AND tbl_booking_date='$next_date' AND (tbl_booking_type='chemo' OR tbl_booking_type='normal')");
        $check=mysqli_num_rows($sq);
        if($check>0)
        {
            $response['error'] = true;
            $response['message'] = 'Already booked';
            echo json_encode($response);
        }
            else
            {
                $input=arand($next_date);
                $token = calculate($input);
                $sql="INSERT INTO tbl_booking(tbl_booking_crno,tbl_booking_name,tbl_booking_unit,tbl_booking_type,tbl_booking_from,tbl_booking_date,tbl_booking_token,tbl_booking_status)VALUES('$Cr_number','$User_name','$Unit_id','$Type','User','$next_date','$token','Requested')";
                $res=mysqli_query($dbconnect,$sql);
                if ($res) {
                    $response['error'] = false;
                    $response['message'] = 'Booking successfull';
                    echo json_encode($response);

                } else {
                    $response['error'] = true;
                    $response['message'] = 'Unable to booking';
                    echo json_encode($response);
                }
            }
        }
    }
    else
    {
         $response['error'] = true;
         $response['message'] = 'Sorryy, Please wait 1 minute!!';
         echo json_encode($response);
    }
}

else
{

    if ($date1 < $date2 && $date1 < $date3 && $date)
        {
            //echo 'here';
        $sq1=mysqli_query($dbconnect,"SELECT max(tbl_booking_token) as t2 FROM tbl_booking where tbl_booking_date='$next_date' AND tbl_booking_from='User' AND tbl_booking_type='normal'");
        $row=mysqli_fetch_array($sq1);
         $t2=$row['t2'];
        if($t2>250)
        {
            $response['error'] = true;
            $response['message'] = 'Booking full';
            echo json_encode($response);
        }
        else
        {
        $sq=mysqli_query($dbconnect,"SELECT * from tbl_booking where tbl_booking_crno='$Cr_number' AND tbl_booking_date='$next_date' AND tbl_booking_from='User' AND (tbl_booking_type='normal' OR tbl_booking_type='chemo')");
        $check=mysqli_num_rows($sq);
        if($check>0)
        {
            $response['error'] = true;
            $response['message'] = 'Already booked';
            echo json_encode($response);
        }
            else
            {
                $input=arand1($next_date);
                $token = calculate1($input);
                $sql="INSERT INTO tbl_booking(tbl_booking_crno,tbl_booking_name,tbl_booking_unit,tbl_booking_type,tbl_booking_from,tbl_booking_date,tbl_booking_token,tbl_booking_status)VALUES('$Cr_number','$User_name','$Unit_id','$Type','User','$next_date','$token','Requested')";
                $res=mysqli_query($dbconnect,$sql);
                if ($res) {
                    $response['error'] = false;
                    $response['message'] = 'Booking successfull';
                    echo json_encode($response);

                } else {
                    $response['error'] = true;
                    $response['message'] = 'Unable to booking';
                    echo json_encode($response);
                }
            }
        }
    }
    
    else
    {
         $response['error'] = true;
         $response['message'] = 'Sorryy, Please wait 1 minute!!';
         echo json_encode($response);
    }
}
}
function arand($next_date)
{
 include('democonnect.php');
 $sql = "SELECT max(tbl_booking_id) as id FROM tbl_booking WHERE tbl_booking_from='User' AND tbl_booking_type='chemo'";
 $result = mysqli_fetch_array(mysqli_query($dbconnect,$sql));
 $id = $result['id'];
 $sq = "SELECT tbl_booking_date FROM tbl_booking WHERE tbl_booking_id='$id' AND tbl_booking_from='User' AND tbl_booking_type='chemo'";
 $res = mysqli_fetch_array(mysqli_query($dbconnect,$sq));
 $dat=$res['tbl_booking_date'];
 if($dat==$next_date)
 {
    $s = "SELECT tbl_booking_token FROM tbl_booking WHERE tbl_booking_id='$id' AND tbl_booking_from='User' AND tbl_booking_type='chemo'";
    $r = mysqli_fetch_array(mysqli_query($dbconnect,$s));
    $token=$r['tbl_booking_token'];
   if($token==null)
   return 1; 
   else 
   return ++$token;  
 }
 else
 {
    return 1;
 }
 
 }
 

function calculate($input) 
{
    
    $slot = $input/10;
     //echo $slot;
    //println(slot%2)
    //println(slot%2 == 0)
    if($slot%2 == 0) 
    {
        //println(input%5)
        //println(input%5 != 0)
        if($input%5 != 0) 
        {
            
          //echo "pass";
          return $input;
          
        } 
        else 
        {
            //echo "fail";
            return ++$input;
            

        }
    }  
    else 
    {
        //echo "failed";
        return $input=$input+11;
    }
}


//calculate(5)
function arand1($next_date)
{
 include('democonnect.php');
 $sql = "SELECT max(tbl_booking_id) as id FROM tbl_booking WHERE tbl_booking_from='User' AND tbl_booking_type='normal'";
 $result = mysqli_fetch_array(mysqli_query($dbconnect,$sql));
 $id = $result['id'];
 $sq = "SELECT tbl_booking_date FROM tbl_booking WHERE tbl_booking_id='$id' AND tbl_booking_from='User' AND tbl_booking_type='normal'";
 $res = mysqli_fetch_array(mysqli_query($dbconnect,$sq));
 $dat=$res['tbl_booking_date'];
 if($dat==$next_date)
 {
    $s = "SELECT tbl_booking_token FROM tbl_booking WHERE tbl_booking_id='$id' AND tbl_booking_from='User' AND tbl_booking_type='normal'";
    $r = mysqli_fetch_array(mysqli_query($dbconnect,$s));
    $token=$r['tbl_booking_token'];
   if($token==null)
   return 101; 
   else 
   return ++$token;  
 }
 else
 {
    return 101;
 }
 
 }

function calculate1($input) 
{
    
   return +$input;
}


?>