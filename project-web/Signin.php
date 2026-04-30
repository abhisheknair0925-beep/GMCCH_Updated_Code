<?php 
session_start();
?>
<?php
include('Header.php');
?>
<div class="appointmentform" id="appointmentform">
<div class="container">
    <div class="register-full">
        <div class="register-right">
            <div class="register-in">
        
                <h3 align="left">SIGN IN HERE</h3>
                <div class=" w3_abp">
                
                </div>
                <div class=" register-form">
                    <form action="Signin.php" method="post" align="center">
                        <div class="fields-grid">
                            
                            <div class="styled-input">
                                <input type="email" name="email" required="">
                                <label>Email</label>
                                <span></span>
                            </div>
                            <div class="styled-input">
                                <input type="Password" name="password" required="" style="    width: 100%;border: 0;background-color: transparent;border-bottom: 1px solid #fff;box-sizing: border-box;font-size: 15px;letter-spacing: 2px;color: #fff;outline: none;padding: 10px;"> 
                                <label>Password</label>
                                <span></span>
                            </div> 
                            <input type="submit" name="btn" value="Sign in">
                            
                        </div>
                        
                    </form>
                    
                        
                </div>
                <div class="clearfix"></div>
             </div>
             
        </div>
        </div>
    </div>
    </div>
    <?php
    include('Footer.php');
    ?>

<?php

include('connect.php');
if(isset($_POST['btn']))
{
    $email=$_POST['email'];
    $password=$_POST['password'];
    $admin_login="SELECT * FROM tbl_hospital WHERE tbl_hospital_email='$email' AND tbl_hospital_password='$password'";
    $login_admin=mysqli_query($dbconnect,$admin_login);
    $count=mysqli_num_rows($login_admin);
    if($count>0)
    {
        echo "<script>alert('Your login has been success')</script>";
        $_SESSION['email']=$email;
        echo "<script>window.location.href='Home.php'</script>";
    }
    else
    {
        echo "<script>alert('Your login failed')</script>";
        echo "<script>window.locaton.href='index.php'</script>";
    }
}
?>
