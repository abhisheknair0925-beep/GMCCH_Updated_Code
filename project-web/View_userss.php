
<?php include('Login_Header.php'); ?>
<br>
<center><h1 style="font-size: 50px;color: #ff0088;letter-spacing: 5px;font-weight: 400;font-family: 'Abel', sans-serif;">VIEW USERS</h1></center>
<br>
<div style="margin-top: 73px;width: 51%;margin-left: 327px;">
  <form method="POST" action="users_view.php">
  <div class="form-group">
    <label for="exampleInputEmail1">Cr number from</label>
    <input type="text" name="from" style="color: blue" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
  
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Cr number to</label>
    <input type="text" name="to" style="color: blue" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
  
  </div>
 
  
  <button type="submit" name="btn" class="btn btn-primary">Search</button>
</form>

</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php include('Footer.php'); ?>
