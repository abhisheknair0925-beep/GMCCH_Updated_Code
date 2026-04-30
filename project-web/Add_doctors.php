<?php include('Login_Header.php'); ?>

<div style="    margin-top: 50px;">
<center><h1 style="font-size: 50px;color: #ff0088;letter-spacing: 4px;font-weight: 400;font-family: 'Abel', sans-serif;">ADD DOCTORS</h1></center>
<div style="margin-top: 73px;width: 51%;margin-left: 327px;">
<form method="POST">
	<div class="form-group">
    <label for="exampleInputPassword1">Name</label>
    <input type="text" name="name" style="display: block;width: 100%;height: 34px;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;border-radius: 4px;" class="form-control" id="exampleInputPassword1" placeholder="Name" required="required">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Qualification</label>
    <input type="text" name="Qualification" style="display: block;width: 100%;height: 34px;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;border-radius: 4px;" class="form-control" id="exampleInputPassword1" placeholder="Qualification" required="required">
  </div>
   <div class="form-group">
    <label for="exampleInputPassword1">Select Unit</label>
    <select name="uid" style="display: block;width: 100%;height: 34px;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;border-radius: 4px;">
      <?php
      include('connect.php');
      $view="SELECT * FROM tbl_units";
      $exe=mysqli_query($dbconnect,$view);
      while($row=mysqli_fetch_array($exe))
      {


      ?>
      <option value="<?php echo $row['tbl_unit_id'];?>">
        <?php echo $row['tbl_unit_name'];?>
      </option>
      <?php
    }
      ?>
    </select>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Department</label>
    <input type="text" name="Department" style="display: block;width: 100%;height: 34px;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;border-radius: 4px;" class="form-control" id="exampleInputPassword1" placeholder="Department" required="required">
  </div>
  
  <div class="form-group">
    <label for="exampleInputPassword1">Phone</label>
    <input type="text" autocomplete="off" maxlength="10" name="Phone" style="display: block;
width: 100%;height: 34px;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;border-radius: 4px;" class="form-control" id="exampleInputPassword1" placeholder="Phone" required="required">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Choose gender</label>
    <input type="radio" name="gender" value="Female" style="color: blue;width: 25px;" class="form-control" id="exampleInputPassword" required="required" required="required"><label>Female</label>
     <input type="radio" name="gender" style="color: blue;width: 25px;margin-top: -54px;margin-left: 146px;" class="form-control" id="exampleInputPassword1" value="Male" required="required" required="required"><label style="margin-left: 149px;">Male</label>
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Regno</label>
    <input type="text" name="regno" style="display: block;width: 100%;height: 34px;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;border-radius: 4px;" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter register number" required="required">
  
  </div>
   <div class="form-group">
    <label for="exampleInputEmail1">Password</label>
    <input type="password" name="pass" style="color: blue" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter password" required="required">
  
  </div>
  <button type="submit" name="btn" class="btn btn-primary">Add doctors</button>
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
  $Department=$_POST['Department'];
  $uid=$_POST['uid'];
	$Qualification=$_POST['Qualification'];
	$Phone=$_POST['Phone'];
	$gender=$_POST['gender'];
	$regno=$_POST['regno'];
  $pass=$_POST['pass'];

    $inst_adds="INSERT INTO tbl_doctor(tbl_doctor_name,tbl_doctor_qualification,tbl_doctor_unit_id,tbl_doctor_phn,tbl_doctor_department,tbl_doctor_gender,tbl_doctor_regno,tbl_doctor_pass)VALUES('$name','$Qualification','$uid','$Phone','$Department','$gender','$regno','$pass')";
	$add_ins=mysqli_query($dbconnect,$inst_adds);
	if($add_ins==1)
	{
		echo "<script>alert('Successfully saved doctors')</script>";
		echo "<script>window.location.href='Add_doctors.php'</script>";
	}
	else
	{
		echo "<script>alert('Doctors not saved')</script>";
		echo "<script>window.location.href='Add_doctors.php'</script>";
	}


}
?>