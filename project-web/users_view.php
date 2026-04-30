<?php
$from=$_POST['from'];
$to=$_POST['to'];
?>
<?php include('Login_Header.php'); ?>
<br>
<center><h1 style="font-size: 50px;color: #ff0088;letter-spacing: 5px;font-weight: 400;font-family: 'Abel', sans-serif;">VIEW USERS</h1></center>
<br>
<div style="width: 90%;margin-left: 75px;">
<form method="POST" action="users_view.php">
  <div class="form-group">
    <label for="exampleInputEmail1">Cr number from</label>
    <input type="text" name="from" style="color: blue" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" style="width: 50%;
    border: 0;
    background-color: transparent;
    border-bottom: 1px solid #blue;
    box-sizing: border-box;
    font-size: 15px;
    letter-spacing: 2px;
    color: #fff;
    outline: black;
    padding: 10px;
    padding-right: 100px;"
    >
  
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Cr number to</label>
    <input type="text" name="to" style="color: blue" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
  
  </div>
 
  
  <button type="submit" name="btn" class="btn btn-primary">Search</button>
</form>
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
      $view_users="SELECT * FROM tbl_user WHERE tbl_user_crno BETWEEN $from AND $to";
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
      <td><a href="edit_users.php?id=<?php echo $rows['tbl_user_id']; ?>">EDIT</a></td>
      <td><a href="delete_users.php?id=<?php echo $rows['tbl_user_id']; ?>">DELETE</a></td>
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
