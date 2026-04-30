<?php include('Login_Header.php'); ?>

<div style="    margin-top: 50px;">
<center><h1 style="font-size: 50px;color: #ff0088;letter-spacing: 5px;font-weight: 400;font-family: 'Abel', sans-serif;">ADD BOOKING</h1></center>
<div style="margin-top: 73px;width: 51%;margin-left: 327px;">
<form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Enter the Cr number</label>
    <input type="text" name="crnumber" style="color: blue" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter the name" required="required" style="display: block;width: 100%;height: 34px;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;border-radius: 4px;">
    <label for="exampleInputEmail1">Enter the Unit number</label>
    <input type="text" name="Unit_id" style="color: blue" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter the name" required="required" style="display: block;width: 100%;height: 34px;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;border-radius: 4px;">
     <label for="exampleInputEmail1">Booking for:</label>
      <select name="type" style="display: block;width: 100%;height: 34px;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;border-radius: 4px;">
        <option>Select</option>
        <option value="chemo">Chemotherapy</option>
        <option value="normal">Follow up</option>
      </select>
  </div>
 <button type="submit" name="btn" class="btn btn-primary">Add</button>
</form>
</div>
</div><br><br><br><br><br><br>
<br><br><br><br><br><br><br>

<?php include('Footer.php'); ?>
<?php
include('connect.php');
if(isset($_POST['btn']))
{
  $Unit_id = $_POST['Unit_id'];
  $date = date('Y-m-d');
  //$date = '2021-02-04';
  //$next_date = date('Y-m-d', strtotime($date .' +1 day'));
  $Cr_number=$_POST['crnumber'];
  $Type=$_POST['type'];
  $s=mysqli_query($dbconnect,"SELECT * from tbl_user where tbl_user_crno='$Cr_number'");
  $r=mysqli_fetch_array($s);
  $User_name=$r['tbl_user_name'];
  if($Type=="chemo")
  {

  
  $sq1=mysqli_query($dbconnect,"SELECT * FROM tbl_booking where tbl_booking_date='$date' AND tbl_booking_type='chemo'");
    echo $check2=mysqli_num_rows($sq1);
    if($check2>29)
    {
        echo "<script>alert('Booking full')</script>";
        echo "<script>window.location.href='home.php'</script>";
    }
    else
    {
    $sq=mysqli_query($dbconnect,"SELECT * from tbl_booking where tbl_booking_crno='$Cr_number' AND tbl_booking_date='$date' AND (tbl_booking_type='normal' OR tbl_booking_type='chemo')");
    $check=mysqli_num_rows($sq);
    if($check>0)
    {
        echo "<script>alert('Already booked')</script>";
        echo "<script>window.location.href='Add_bookingggg.php'</script>";
    }
        else
        {
            
            $input=arand1($date);
            $token = calculate1($input);
            $sql="INSERT INTO tbl_booking(tbl_booking_crno,tbl_booking_name,tbl_booking_unit,tbl_booking_type,tbl_booking_from,tbl_booking_date,tbl_booking_token,tbl_booking_status)VALUES('$Cr_number','$User_name','$Unit_id','$Type','Hospital','$date','$token','Approved')";
            $res=mysqli_query($dbconnect,$sql);
            if ($res) 
            {
                echo "<script>alert('Booking successfull; Token number : $token')</script>";
                echo "<script>window.location.href='Add_bookingggg.php'</script>";

            } else 
            {
              echo "<script>alert('Unable to book')</script>";
              echo "<script>window.location.href='Add_bookingggg.php'</script>";
            }
        }
    }
  
}
else
{
  $sq1=mysqli_query($dbconnect,"SELECT * FROM tbl_booking where tbl_booking_date='$date' AND tbl_booking_type='normal'");
    echo $check2=mysqli_num_rows($sq1);
    if($check2>69)
    {
        echo "<script>alert('Booking full')</script>";
        echo "<script>window.location.href='home.php'</script>";
    }
    else
    {
    $sq=mysqli_query($dbconnect,"SELECT * from tbl_booking where tbl_booking_crno='$Cr_number' AND tbl_booking_date='$date' AND (tbl_booking_type='normal' OR tbl_booking_type='chemo')");
    $check=mysqli_num_rows($sq);
    if($check>0)
    {
        echo "<script>alert('Already booked')</script>";
        echo "<script>window.location.href='Add_bookingggg.php'</script>";
    }
        else
        {
            
            $input=arand2($date);
            $token = calculate2($input);
            $sql="INSERT INTO tbl_booking(tbl_booking_crno,tbl_booking_name,tbl_booking_unit,tbl_booking_type,tbl_booking_from,tbl_booking_date,tbl_booking_token,tbl_booking_status)VALUES('$Cr_number','$User_name','$Unit_id','$Type','Hospital','$date','$token','Approved')";
            $res=mysqli_query($dbconnect,$sql);
            if ($res) 
            {
                echo "<script>alert('Booking successfull; Token number : $token')</script>";
                echo "<script>window.location.href='Add_bookingggg.php'</script>";

            } else 
            {
              echo "<script>alert('Unable to book')</script>";
              echo "<script>window.location.href='Add_bookingggg.php'</script>";
            }
        }
    
  
}

}
}
?>
<?php
function arand1($date)
{
   include('connect.php');
   $sql = "SELECT max(tbl_booking_id) as id FROM tbl_booking WHERE tbl_booking_from='Hospital' AND tbl_booking_type='chemo'";
   $result = mysqli_fetch_array(mysqli_query($dbconnect,$sql));
   $id = $result['id'];
   $sq = "SELECT tbl_booking_date FROM tbl_booking WHERE tbl_booking_id='$id' AND tbl_booking_from='Hospital'";
   $res = mysqli_fetch_array(mysqli_query($dbconnect,$sq));
   $dat=$res['tbl_booking_date'];
   if($dat==$date)
   {
      $s = "SELECT tbl_booking_token FROM tbl_booking WHERE tbl_booking_id='$id' AND tbl_booking_from='Hospital'";
      $r = mysqli_fetch_array(mysqli_query($dbconnect,$s));
      $token=$r['tbl_booking_token'];
     if($token==null)
     return 11; 
     else 
     return ++$token;  
   }
   else
   {
      return 11;
   }
   
 }
 

function calculate1($input) 
{
  
    
    echo $slot = $input/10;
     //echo $slot;
    //println(slot%2)
    //println(slot%2 == 0)
    
     if($slot%2==0) 
      {
        //println(input%5)
        //println(input%5 != 0)
        if($input%5!=0) 
        { 
          echo "pass";
          return $input+10;
          
        } 
        else 
        {
            echo "fail";
            return $input;

        }

      }  
      else
      {
        echo "failed";
        return $input;
      }
      
    
    
}

//calculate(5)


?>
<?php
function arand2($date)
{
 include('connect.php');
 $sql = "SELECT max(tbl_booking_id) as id FROM tbl_booking WHERE tbl_booking_from='Hospital' AND tbl_booking_type='normal'";
 $result = mysqli_fetch_array(mysqli_query($dbconnect,$sql));
 $id = $result['id'];
 $sq = "SELECT tbl_booking_date FROM tbl_booking WHERE tbl_booking_id='$id' AND tbl_booking_from='Hospital'";
 $res = mysqli_fetch_array(mysqli_query($dbconnect,$sq));
 $dat=$res['tbl_booking_date'];
 if($dat==$date)
 {
    $s = "SELECT tbl_booking_token FROM tbl_booking WHERE tbl_booking_id='$id' AND tbl_booking_from='Hospital'";
    $r = mysqli_fetch_array(mysqli_query($dbconnect,$s));
    $token=$r['tbl_booking_token'];
   if($token==null)
   return 71; 
   else 
   return ++$token;  
 }
 else
 {
    return 71;
 }
 
 }
 

function calculate2($input) 
{
    
    echo $slot = $input/10;
     //echo $slot;
    //println(slot%2)
    //println(slot%2 == 0)
    
      if($slot%2==0) 
      {
        //println(input%5)
        //println(input%5 != 0)
        if($input%5!=0) 
        { 
          echo "pass";
          return $input+10;
          
        } 
        else 
        {
            echo "fail";
            return $input;

        }

      }  
      else
      {
        echo "failed";
        return $input;
      }
      
    
    
}

//calculate(5)


?>
