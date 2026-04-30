<?php include('Login_Header.php'); ?>
<?php
$id=$_GET['id'];
include('connect.php');
 $view_users="SELECT * FROM tbl_doctor WHERE tbl_doctor_id='$id'";
 $users_view=mysqli_query($dbconnect,$view_users);
 $rows=mysqli_fetch_array($users_view);
?>
<div style="    margin-top: 50px;">
<center><h1 style="font-size: 50px;color: #ff0088;letter-spacing: 5px;font-weight: 400;font-family: 'Abel', sans-serif;">EDIT DOCTORS</h1></center>
<div style="margin-top: 73px;width: 51%;margin-left: 327px;">
<form method="POST">
  <div class="form-group">
    <label for="exampleInputPassword1">Name</label>
    <input type="text" name="name" style="color: blue" class="form-control" id="exampleInputPassword1" placeholder="Name" value="<?php echo $rows['tbl_doctor_name']; ?>" required="required">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Qualification</label>
    <input type="text" name="Qualification" style="color: blue" class="form-control" id="exampleInputPassword1" placeholder="Qualification" value="<?php echo $rows['tbl_doctor_qualification']; ?>" required="required">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Department</label>
    <input type="text" name="Department" value="<?php echo $rows['tbl_doctor_department']; ?>" style="color: blue" class="form-control" id="exampleInputPassword1" placeholder="Department" required="required">
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
    <label for="exampleInputPassword1">Phone</label>
    <input type="text" autocomplete="off" maxlength="10" name="Phone" style="color: blue" class="form-control" id="exampleInputPassword1" value="<?php echo $rows['tbl_doctor_phn']; ?>" placeholder="Phone" required="required">
  </div>
  
  <div class="form-group">
    <label for="exampleInputEmail1">Register number</label>
    <input type="text" name="regno" style="color: blue" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $rows['tbl_doctor_regno']; ?>" placeholder="Enter email" required="required">
  
  </div>
   <div class="form-group">
    <label for="exampleInputEmail1">Password</label>
    <input type="text" name="Password" style="color: blue" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $rows['tbl_doctor_pass']; ?>" placeholder="Enter email" required="required">
  
  </div>
  <button type="submit" name="btn" class="btn btn-primary">Update doctors</button>
</form>
</div>
</div><br><br><br><br><br><br>


<?php include('Footer.php'); ?>
<?php
include('connect.php');
if(isset($_POST['btn']))
{
  $name=$_POST['name'];
  $Department=$_POST['Department'];
  $Qualification=$_POST['Qualification'];
  $uid=$_POST['uid'];
  $Phone=$_POST['Phone'];
 // $gender=$_POST['gender'];
  $regno=$_POST['regno'];
 $Password=$_POST['Password'];
    echo $inst_adds="UPDATE tbl_doctor SET tbl_doctor_name='$name',tbl_doctor_qualification='$Qualification',tbl_doctor_unit_id='$uid',tbl_doctor_phn='$Phone',tbl_doctor_regno='$regno',tbl_doctor_pass='$Password',tbl_doctor_department='$Department' WHERE tbl_doctor_id='$id'";
  $add_ins=mysqli_query($dbconnect,$inst_adds);
  if($add_ins==1)
  {
    echo "<script>alert('Successfully updated doctors')</script>";
    echo "<script>window.location.href='View_doctors.php'</script>";
  }
  else
  {
    echo "<script>alert('Doctors not updated')</script>";
    echo "<script>window.location.href='View_doctors.php'</script>";
  }


}
?>