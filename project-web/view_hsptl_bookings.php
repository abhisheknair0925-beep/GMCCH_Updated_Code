
<?php include('Login_Header.php'); ?>

<center><h1 style="font-size: 50px;color: #ff0088;letter-spacing: 5px;font-weight: 400;font-family: 'Abel', sans-serif;">VIEW HOSPITAL BOOKINGS</h1></center>

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
      
    </tr>
  </thead>
  <tbody>
  	<?php 
      include('connect.php');
      if(isset($_POST['btn']))
      {
        //$date=$_POST['Date'];
      }
     
      $view_users="SELECT * FROM tbl_booking_hospital ORDER BY tbl_book_date";
      $users_view=mysqli_query($dbconnect,$view_users);
      $i=1;
      while($rows=mysqli_fetch_array($users_view))
      {
       ?>
    <tr>
      <td><?php echo $i; ?></td>
      <td><?php echo $rows['tbl_book_crno']; ?></td>
      <td><?php echo $rows['tbl_book_name']; ?></td>
      <td><?php echo $rows['tbl_book_unit']; ?></td>
      <td><?php echo $rows['tbl_book_token']; ?></td>
      <td><?php echo $rows['tbl_book_date']; ?></td>
     
    
      
       
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