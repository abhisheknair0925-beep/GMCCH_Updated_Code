<?php include('Login_Header.php'); ?>

<div style="    margin-top: 50px;">
<center><h1 style="font-size: 50px;color: #ff0088;letter-spacing: 5px;font-weight: 400;font-family: 'Abel', sans-serif;">ADD USERS</h1></center>
<div style="margin-top: 73px;width: 51%;margin-left: 327px;">
<form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Enter the name</label>
    <input type="text" name="name" style="color: blue" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter the name" required="required">
  
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Enter the age</label>
    <input type="text" name="age" style="color: blue" class="form-control" id="exampleInputPassword1" placeholder="Enter the age" required="required">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Choose gender</label>
    <input type="radio" name="gender" value="F" style="color: blue;width: 25px;" class="form-control" id="exampleInputPassword" required="required"><label>Female</label>
     <input type="radio" name="gender" style="color: blue;width: 25px;margin-top: -54px;margin-left: 146px;" class="form-control" id="exampleInputPassword1" value="M" required="required"><label style="margin-left: 149px;">Male</label>
  </div>
 
  <div class="form-group">
    <label for="exampleInputPassword1">CR number</label>
    <input type="text" name="crnumber" value="<?php echo arand();?>" readonly style="color: blue" class="form-control" id="exampleInputPassword1" placeholder="crnumber" required="required">
  </div>
  
  <button type="submit" name="btn" class="btn btn-primary">Add users</button>
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


    echo $inst_adds="INSERT INTO tbl_user1(tbl_user_crno,tbl_user_name,tbl_user_age,tbl_user_gender)VALUES('$crnumber','$name','$age','$gender')";
	$add_ins=mysqli_query($dbconnect,$inst_adds);
	if($add_ins==1)
	{
		echo "<script>alert('Successfully saved users')</script>";
		echo "<script>window.location.href='Add_usersss.php'</script>";
	}
	else
	{
		echo "<script>alert('Users not saved')</script>";
		echo "<script>window.location.href='Add_usersss.php'</script>";
	}


}
?>
<?php
function arand()
{
 include('connect.php');
 $year = date('Y');
 $y = substr($year,2);
 $s= "SELECT max(tbl_user_crno) as yr FROM tbl_user";
 $r = mysqli_fetch_array(mysqli_query($dbconnect,$s));
 $newyr=substr($r['yr'],0,2);
 if($y==$newyr)
 {
    return ++$r['yr'];
 }
 //$sql = "SELECT max(tbl_user_crno) as id FROM tbl_user1";
 //$result = mysqli_fetch_array(mysqli_query($dbconnect,$sql));
 else
 {
    return $y.'00001';
 }
 }

?>