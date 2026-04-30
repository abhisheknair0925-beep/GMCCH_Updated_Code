<?php
include('connect.php'); 
$id=$_GET['id'];
$delete="DELETE FROM tbl_doctor WHERE tbl_doctor_id='$id'";
$exe=mysqli_query($dbconnect,$delete);
if($exe==1)
{
	echo "<script>alert('Doctors deleted')</script>";
	echo "<script>window.location.href='View_doctors.php'</script>";
}
else
{
	echo "<script>alert('Not deleted')</script>";
	echo "<script>window.location.href='View_doctors.php'</script>";
}
?>