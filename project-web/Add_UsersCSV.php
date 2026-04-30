<?php include('Login_Header.php'); ?>

<div style="    margin-top: 50px;">
<center><h1 style="font-size: 50px;color: #ff0088;letter-spacing: 5px;font-weight: 400;font-family: 'Abel', sans-serif;">ADD USERS</h1></center>
<div style="margin-top: 73px;width: 51%;margin-left: 327px;">
<form method="POST" enctype="multipart/form-data">
  <div class="form-group">
    <label for="exampleInputEmail1">Upload your File</label>
    <input type="file" name="csvfile" style="color: blue" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter the name" required="required">
    <br><br>
  <button type="submit" name="btn" class="btn btn-primary">Submit</button>
</form>
</div>
</div><br><br><br><br><br><br>
<br><br><br><br><br><br><br>

<?php include('Footer.php'); ?>
<?php
include('connect.php');
if(isset($_POST['btn']))
{
   
  if (!$dbconnect) {
    die("Connection failed: " . mysqli_connect_error());
     }
  
  $file = $_FILES['csvfile']['tmp_name'];
  $handle = fopen($file, "r");
  $i=0;
  while(($data = fgetcsv($handle,1000,","))!==false)
    {
    $i++;
    if($i==1)
      {
      continue;
      }
    $query = "INSERT INTO tbl_user(tbl_user_crno,	tbl_user_name, tbl_user_age, tbl_user_gender) VALUES('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."')";
    $dbconnect->query($query);
    // echo $query; 
      
    }
  // if($dbconnect==1)
	// {
		echo "<script>alert('Successfully saved users')</script>";
		echo "<script>window.location.href='Add_UsersCSV.php'</script>";
	// }
	// else
	// {
	// 	echo "<script>alert('Users not saved')</script>";
	// 	echo "<script>window.location.href='Add_UsersCSV.php'</script>";
	// }


}
?>
