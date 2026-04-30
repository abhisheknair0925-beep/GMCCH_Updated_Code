
<?php include('Login_Header.php'); ?>
<br>
<center><h1 style="font-size: 50px;color: #ff0088;letter-spacing: 5px;font-weight: 400;font-family: 'Abel', sans-serif;">VIEW BOOKING</h1></center>
</div>


<div style="margin-top: 73px;width: 51%;margin-left: 0px;">
<form method="POST">
  <div class="form-group">
    <label for="exampleInputPassword1">Enter date</label>
    <input type="text" name="date" style="color: #ff0088" class="form-control" id="exampleInputPassword1" placeholder="yyyy-mm-dd">
  </div>
  
  <button type="submit" name="btn" class="btn btn-primary">Search</button>
</form>

 
</div>
<?php
if(isset($_POST['btn']))
      {
        $date=$_POST['date'];

      }
?>

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
       
    </tr>
  </thead>
  <tbody>
    <?php 
      include('connect.php');
      $view_users="SELECT * FROM tbl_booking WHERE tbl_booking_status='Approved' AND tbl_booking_date='$date'";
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
      <td><?php echo $rows['tbl_booking_status'];?></td>
    </tr>
    <?php
    $i++;
}
?>
  </tbody>
</table>
<br>
<span align="center">
<a target="_blank" href="export.php?date=<?php echo $date?>">
  

  <button type="submit" name="btn" class="btn btn-primary" align="right">Export</button>
</a>
</span>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php include('Footer.php'); ?>

