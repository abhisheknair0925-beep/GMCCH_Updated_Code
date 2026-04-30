<?php
include('connect.php'); 
$id=$_GET['id'];
$delete="DELETE FROM tbl_booking WHERE tbl_booking_id='$id'";
$exe=mysqli_query($dbconnect,$delete);
if($exe==1)
{
	echo "<script>alert('Booking rejected')</script>";
	echo "<script>window.location.href='View_users_bookings.php'</script>";
}
else
{
	echo "<script>alert('Rejection not working')</script>";
	echo "<script>window.location.href='View_users_bookings.php'</script>";
}
?>