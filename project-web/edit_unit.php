<?php include('Login_Header.php'); ?>
<?php
$id=$_GET['id'];
include('connect.php');
 $view_users="SELECT * FROM tbl_units WHERE tbl_unit_id='$id'";
 $users_view=mysqli_query($dbconnect,$view_users);
 $rows=mysqli_fetch_array($users_view);
?>
<div style="    margin-top: 50px;">
<center><h1 style="font-size: 50px;color: #ff0088;letter-spacing: 5px;font-weight: 400;font-family: 'Abel', sans-serif;">EDIT UNITS</h1></center>
<div style="margin-top: 73px;width: 51%;margin-left: 327px;">
<form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Update the name</label>
    <input type="text" name="name" style="color: blue" class="form-control" id="exampleInputEmail1" value="<?php echo $rows['tbl_unit_name']; ?>" aria-describedby="emailHelp" placeholder="Enter the name" required="required">
  
  </div>
  <button type="submit" name="btn" class="btn btn-primary">Update</button>
</form>
</div>
</div><br><br><br><br><br><br>


<?php include('Footer.php'); ?>
<?php
include('connect.php');
if(isset($_POST['btn']))
{
  $name=$_POST['name'];
  $inst_update="UPDATE tbl_units SET tbl_unit_name='$name' WHERE tbl_unit_id='$id'";
	$update_ins=mysqli_query($dbconnect,$inst_update);
	if($update_ins==1)
	{
		echo "<script>alert('Successfully updated unit')</script>";
		echo "<script>window.location.href='View_unit.php'</script>";
	}
	else
	{
		echo "<script>alert('Unit not updated')</script>";
		echo "<script>window.location.href='View_unit.php'</script>";
	}


}
?>