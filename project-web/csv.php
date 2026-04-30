<?php
include('connect.php');
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
	// $dbconnect->query($query);
	echo $query; 
	  
	}
echo "<h1>data entered successfully</h1>";
?>