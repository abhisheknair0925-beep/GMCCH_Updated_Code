<?php
include('connect.php');

// $Op_number = $_POST['Op_number'];
// $User_name = $_POST['User_name'];
// $Dr_name = $_POST['Dr_name'];
// $date =date('dd-mm-yyyy');
// $Booking_status ='Requested';

// SELECT * FROM tbl_booking WHERE tbl_booking_date >= date('d-m-yy') - INTERVAL 1 DAY
$date =date('d-m-yy');
$Op_number ='6344099877';
$User_name = 'Arun';
$Dr_name='Abhishek';
$Booking_status ='Requested';
	$sq1=mysqli_query($dbconnect,"SELECT * FROM tbl_booking where tbl_booking_date>='$date'  - INTERVAL 1 DAY AND tbl_booking_date>='$date' + INTERVAL 1 DAY");
	$check2=mysqli_num_rows($sq1);
	if($check2>197)
	{
		$response['error'] = true;
		$response['message'] = 'Booking full';
	    echo json_encode($response);
	}
	else
	{
	$sq=mysqli_query($dbconnect,"SELECT * from tbl_booking where tbl_booking_opno='$Op_number' AND tbl_booking_date='$date'");
	$check=mysqli_num_rows($sq);
	if($check>0)
	{
		$response['error'] = true;
		$response['message'] = 'Already booked';
	    echo json_encode($response);
	}
		else
		{
			$sql="INSERT into tbl_booking(tbl_booking_opno,tbl_booking_name,tbl_booking_dr_name,tbl_booking_date,tbl_booking_status)VALUES('$Op_number','$User_name','$Dr_name','$date','$Booking_status')";
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
			
	
	

?>




