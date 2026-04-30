<?php
$id=$_GET['id'];
include('connect.php');
  $inst_adds="UPDATE tbl_booking SET tbl_booking_status='Approved' WHERE tbl_booking_id='$id'";
  $add_ins=mysqli_query($dbconnect,$inst_adds);
  if($add_ins==1)
  {
    echo "<script>alert('Successfully approved bookings')</script>";
    echo "<script>window.location.href='View_users_bookings.php'</script>";
  }
  else
  {
    echo "<script>alert('Failed to approval')</script>";
    echo "<script>window.location.href='View_users_bookings.php'</script>";
  }



?>