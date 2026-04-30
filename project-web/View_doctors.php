<?php include('Login_Header.php'); ?>
<br>
<center><h1 style="font-size: 50px;color: #ff0088;letter-spacing: 5px;font-weight: 400;font-family: 'Abel', sans-serif;">VIEW DOCTORS</h1></center>
<br>
<div style="width: 90%;margin-left: 75px;">
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">SI NO</th>
      <th scope="col" style="text-transform: uppercase;">Name</th>
      <th scope="col" style="text-transform: uppercase;">Qualification</th>
       <th scope="col" style="text-transform: uppercase;">Designation & Department</th>
      <th scope="col" style="text-transform: uppercase;">Unit</th>
      <th scope="col" style="text-transform: uppercase;">Phone</th>
      <th scope="col" style="text-transform: uppercase;">Gender</th>
      <th scope="col" style="text-transform: uppercase;">T C M C number</th>
        <th scope="col" style="text-transform: uppercase;">APP Password</th>
       <th scope="col">EDIT</th>
        <th scope="col">DELETE</th>
    </tr>
  </thead>
  <tbody>
  	<?php 
      include('connect.php');
      $view_users="SELECT * FROM tbl_doctor";
      $users_view=mysqli_query($dbconnect,$view_users);
      $i=1;
      while($rows=mysqli_fetch_array($users_view))
      {
       ?>
    <tr>
      <td><?php echo $i; ?></td>
      <td><?php echo $rows['tbl_doctor_name']; ?></td>
      <td><?php echo $rows['tbl_doctor_qualification']; ?></td>
      <td><?php echo $rows['tbl_doctor_department']; ?></td>
      <td><?php echo $rows['tbl_doctor_unit_id']; ?></td>
      <td><?php echo $rows['tbl_doctor_phn']; ?></td>
      <td><?php echo $rows['tbl_doctor_gender']; ?></td>
      <td><?php echo $rows['tbl_doctor_regno']; ?></td>
       <td><?php echo $rows['tbl_doctor_pass']; ?></td>
      <td><a href="edit_doctors.php?id=<?php echo $rows['tbl_doctor_id']; ?>"><i class="fa fa-pencil" style="color: blue"></i></a></td>
      <td><a href="delete_doctors.php?id=<?php echo $rows['tbl_doctor_id']; ?>"><i class="fa fa-trash" style="color: red"></i></a></td>
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