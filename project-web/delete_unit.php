<?php
include('connect.php'); 
$id=$_GET['id'];
$delete="DELETE FROM tbl_units WHERE tbl_unit_id='$id'";
$exe=mysqli_query($dbconnect,$delete);
if($exe==1)
{
	echo "<script>alert('Unit deleted')</script>";
	echo "<script>window.location.href='View_unit.php'</script>";
}
else
{
	echo "<script>alert('Not deleted')</script>";
	echo "<script>window.location.href='View_unit.php'</script>";
}
?>