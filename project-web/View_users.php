<?php include('Login_Header.php'); ?>
<br>
<center><h1 style="font-size: 50px;color: #ff0088;letter-spacing: 5px;font-weight: 400;font-family: 'Abel', sans-serif;">VIEW USERS</h1></center>
<br>
<div style="width: 90%;margin-left: 75px;">
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">SI NO</th>
      <th scope="col">NAME</th>      
      <th scope="col">CR NUMBER</th>
      <th scope="col">AGE</th>
      <th scope="col">GENDER</th>
       <th scope="col">EDIT</th>
        <th scope="col">DELETE</th>
    </tr>
  </thead>
  <tbody>
  	<?php 
      include('connect.php');
      $view_users="SELECT * FROM tbl_user";
      $users_view=mysqli_query($dbconnect,$view_users);
      $i=1;
      while($rows=mysqli_fetch_array($users_view))
      {
       ?>
    <tr>
      <td><?php echo $i; ?></td>
      <td><?php echo $rows['tbl_user_name']; ?></td>
      <td><?php echo $rows['tbl_user_crno']; ?></td>
      <td><?php echo $rows['tbl_user_age']; ?></td>
      <td><?php echo $rows['tbl_user_gender']; ?></td>
      <td><a href="edit_users.php?id=<?php echo $rows['tbl_user_id']; ?>"><i class="fa fa-pencil" style="color: blue"></i></a></td>
      <td><a href="delete_users.php?id=<?php echo $rows['tbl_user_id']; ?>"><i class="fa fa-trash" style="color: red"></i></a></td>
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