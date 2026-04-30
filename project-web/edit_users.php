<?php include('Login_Header.php'); ?>
<?php
$id=$_GET['id'];
include('connect.php');
 $view_users="SELECT * FROM tbl_user WHERE tbl_user_id='$id'";
 $users_view=mysqli_query($dbconnect,$view_users);
 $rows=mysqli_fetch_array($users_view);
?>
<div style="    margin-top: 50px;">
<center><h1 style="font-size: 50px;color: #ff0088;letter-spacing: 5px;font-weight: 400;font-family: 'Abel', sans-serif;">EDIT USERS</h1></center>
<div style="margin-top: 73px;width: 51%;margin-left: 327px;">
<form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Update the name</label>
    <input type="text" name="name" style="color: blue" class="form-control" id="exampleInputEmail1" value="<?php echo $rows['tbl_user_name']; ?>" aria-describedby="emailHelp" placeholder="Enter the name" required="required">
  
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Update the age</label>
    <input type="text" name="age" style="color: blue" class="form-control" id="exampleInputPassword1" value="<?php echo $rows['tbl_user_age']; ?>" placeholder="Enter the age" required="required">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Update gender</label>
 <input type="radio" checked="checked" name="gender" value="F" style="color: blue;width: 25px;" class="form-control" id="exampleInputPassword" required="required"><label>Female</label>
        <input type="radio" name="gender" style="color: blue;width: 25px;margin-top: -54px;margin-left: 146px;" class="form-control" id="exampleInputPassword1" value="M" required="required"><label style="margin-left: 149px;">Male</label></div>
  
  
  <div class="form-group">
    <label for="exampleInputPassword1">CR number</label>
    <input type="text" name="crnumber"   style="color: blue" class="form-control" value="<?php echo $rows['tbl_user_crno']; ?>" id="exampleInputPassword1" placeholder="CR number" required="required" readonly>
  </div>
  
  <button type="submit" name="btn" class="btn btn-primary">Update</button>
</form>
</div>
</div><br><br><br><br><br><br>
<br><br><br><br><br><br><br>

<?php include('Footer.php'); ?>
<?php
include('connect.php');
if(isset($_POST['btn']))
{
   
$name=$_POST['name'];
$age=$_POST['age'];
$gender=$_POST['gender'];

$crnumber=$_POST['crnumber'];


    $inst_update="UPDATE tbl_user SET tbl_user_name='$name',tbl_user_age='$age',tbl_user_gender='$gender',tbl_user_crno='$crnumber' WHERE tbl_user_id='$id'";
	$update_ins=mysqli_query($dbconnect,$inst_update);
	if($update_ins==1)
	{
		echo "<script>alert('Successfully updated users')</script>";
		echo "<script>window.location.href='View_users.php'</script>";
	}
	else
	{
		echo "<script>alert('Users not updated')</script>";
		echo "<script>window.location.href='View_users.php'</script>";
	}


}
?>