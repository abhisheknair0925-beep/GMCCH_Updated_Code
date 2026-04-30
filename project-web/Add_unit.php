<?php include('Login_Header.php'); ?>

<div style="    margin-top: 50px;">
<center><h1 style="font-size: 50px;color: #ff0088;letter-spacing: 5px;font-weight: 400;font-family: 'Abel', sans-serif;">ADD UNITS</h1></center>
<div style="margin-top: 73px;width: 51%;margin-left: 327px;">
<form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Enter the unit name</label>
    <input type="text" name="name" style="color: blue" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter the name" required="required">
  
  </div>
  
  
  <button type="submit" name="btn" class="btn btn-primary">Add</button>
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
  echo $inst_adds="INSERT INTO tbl_units(tbl_unit_name)VALUES('$name')";
	$add_ins=mysqli_query($dbconnect,$inst_adds);
	if($add_ins==1)
	{
		echo "<script>alert('Successfully saved units')</script>";
		echo "<script>window.location.href='Add_unit.php'</script>";
	}
	else
	{
		echo "<script>alert('Unit not saved')</script>";
		echo "<script>window.location.href='Add_unit.php'</script>";
	}


}
?>
<?php
function arand()
{
 include('connect.php');
 $sql = "SELECT max(tbl_user_crno) as id FROM tbl_user";
 $result = mysqli_fetch_array(mysqli_query($dbconnect,$sql));
 if($result['id']==null)
 return 1; 
 else 
 return ++$result['id']; 
 }

?>