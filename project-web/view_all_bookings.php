
<?php include('Login_Header.php'); ?>

<center><h1 style="font-size: 50px;color: #ff0088;letter-spacing: 5px;font-weight: 400;font-family: 'Abel', sans-serif;">VIEW ALL BOOKINGS</h1></center>

<div style="    margin-top: 93px;">

<div style="margin-top: 73px;width: 51%;margin-left: 327px;">

</div>
</div>
<div style="width: 90%;margin-left: 75px;">
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">SI NO</th>
      <th scope="col" style="text-transform: uppercase;">Cr number</th>
      <th scope="col" style="text-transform: uppercase;">User name</th>
      <th scope="col" style="text-transform: uppercase;">Unit</th>
      <th scope="col" style="text-transform: uppercase;">Token</th>
      <th scope="col" style="text-transform: uppercase;">Date</th>
      <th scope="col" style="text-transform: uppercase;">Booking status</th>
       <th scope="col" style="text-transform: uppercase;">Reject</th>
    </tr>
  </thead>
  <tbody>
  	<?php 
      include('connect.php');
      if(isset($_POST['btn']))
      {
        //$date=$_POST['Date'];
      }

      $view_users="SELECT * FROM tbl_booking  WHERE tbl_booking_status='Visited' ORDER BY tbl_booking_date DESC";
      
      $users_view=mysqli_query($dbconnect,$view_users);
      $i=1;
      while($rows=mysqli_fetch_array($users_view))
      {
       ?>
    <tr>
      <td><?php echo $i; ?></td>
      <td><?php echo $rows['tbl_booking_crno']; ?></td>
      <td><?php echo $rows['tbl_booking_name']; ?></td>
      <td><?php echo $rows['tbl_booking_unit']; ?></td>
      <td><?php echo $rows['tbl_booking_token']; ?></td>
      <td><?php echo $rows['tbl_booking_date']; ?></td>
     
    
      <td>
      	<?php 
      	if($rows['tbl_booking_status']=="Requested")
      	{
      	?>
      	<a href="Approve_bookings.php?id=<?php echo $rows['tbl_booking_id']; ?>"><?php echo $rows['tbl_booking_status']; ?></a>
      	<?php
      }
      else
      {
      	?>
      	<?php echo $rows['tbl_booking_status']; ?>
      	<?php
      }

      	?>

      </td>
       <td>
       		<?php 
      	if($rows['tbl_booking_status']=="Requested")
      	{
      	?>
       	<a href="Reject_bookings.php?id=<?php echo $rows['tbl_booking_id']; ?>">Reject</a>
       <?php
      }
      elseif($rows['tbl_booking_status']=="Approved")
      {
      	?>
      	Rejection not available
      	<?php
      }
      else
      {
        ?>
        <?php echo $rows['tbl_booking_status']; ?>
        <?php
      }

      	?></td>
    </tr>
    <?php
    $i++;
}
?>
  </tbody>
</table>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php include('Footer.php'); ?>