<?php
include('connect.php'); 
$id=$_GET['id'];
$delete="DELETE FROM tbl_user WHERE tbl_user_id='$id'";
$exe=mysqli_query($dbconnect,$delete);
if($exe==1)
{
	echo "<script>alert('Users deleted')</script>";
	echo "<script>window.location.href='View_users.php'</script>";
}
else
{
	echo "<script>alert('Not deleted')</script>";
	echo "<script>window.location.href='View_users.php'</script>";
}
?>