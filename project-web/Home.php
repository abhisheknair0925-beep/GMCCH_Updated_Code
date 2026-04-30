<?php
session_start();
$email=$_SESSION['email'];
if(!$email)
{
    header('location:index.php');
}
?>
<?php
include('connect.php');
$dat=date('Y-m-d');
        $due_date = date('Y-m-d', strtotime($dat .' -7 days'));
        $delete="DELETE FROM tbl_booking WHERE tbl_booking_date='$due_date'";
        $exe=mysqli_query($dbconnect,$delete);
?>
<!DOCTYPE html>
<html>
<head>
<title>M C Chest Hospital <?php echo $email ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<meta name="keywords" content="Curative a Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all">
<link rel="stylesheet" href="css/owl.carousel.css" type="text/css" media="all">
<link href="css/owl.theme.css" rel="stylesheet">
<link rel="stylesheet" href="css/jquery-ui.css" type="text/css" media="all" />
<link type="text/css" rel="stylesheet" href="css/cm-overlay.css" />
<link href="css/style.css" rel="stylesheet" type="text/css" media="all"/>
<link href="//fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Abel" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>
<body>
<!-- Header -->
	<div class="header-top">
		<div class="container">
			
			<h8 style="font-size: 16px;"> 
            Medical College Chest Hospital Thrissur</h8>

			<div class="bottom_header_right">
			

				
				
				<div class="clearfix"> </div>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
	<div class="header">
		<div class="content white">
			<nav class="navbar navbar-default">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="index.php">
							<h1>
								<img src="images/g11.png" style="height: 50px;width: 50px;">
								MCC HOSPITAL
								<label>For A Better Treatment</label>
							</h1>
						</a>
					</div>
					<!--/.navbar-header-->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<nav class="link-effect-2" id="link-effect-2">
							<ul class="nav navbar-nav navbar-right">
								<li>
									<a href="Home.php">Home</a>
								</li>
								<li class="dropdown">
								  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Units<span class="caret"></span></a>
								  <ul class="dropdown-menu">
								   <li>
									<a href="Add_unit.php">Add</a>
								</li>
								<li>
									<a href="View_unit.php">View</a>
								</li>
							
							  </ul>
							</li>
								
								
								<li class="dropdown">
								  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Doctors <span class="caret"></span></a>
								  <ul class="dropdown-menu">
								   <li>
									<a href="Add_doctors.php">Add</a>
								</li>
								<li>
									<a href="View_doctors.php">View</a>
								</li>
							
							  </ul>
							</li><li class="dropdown">
								  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Users <span class="caret"></span></a>
								  <ul class="dropdown-menu">
								   <li>
									<a href="Add_UsersCSV.php">Add</a>
								</li>
								<li>
									<a href="View_userss.php">View</a>
								</li>
							
							  </ul>
							</li>
							<li class="dropdown">
								  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Bookings <span class="caret"></span></a>
								  <ul class="dropdown-menu">
								  	
								<li>
									<a href="View_users_bookings.php">Approve Bookings</a>
								</li>
								   
								 <li>
									<a href="View_booking_all.php">View Bookings</a>
								</li>

							
							
							  </ul>
							</li>
								<li>
									<a href="Signout.php" >Sign Out</a>
								</li>
								
							</ul>
						</nav>
					</div>
					<!--/.navbar-collapse-->
					<!--/.navbar-->
				</div>
			</nav>
		</div>
	</div>
	<div class="headerdown">
	<div class="container">
	<div class="col-md-4 col-sm-4 w3_hl">
	<div class="w3l_l">
	<i class="fa fa-phone-square" aria-hidden="true"></i>
	</div>
	<div class="w3l_r">
	<h3>Call 0487-2200610</h3>
	<h5>Ask for Doctor</h5>
	</div>
	
	</div>
	
	<div class="col-md-4 col-sm-4 w3_hc">
	<div class="w3l_cl">
	<i class="fa fa-clock-o" aria-hidden="true"></i>
	</div>
	<div class="w3l_cr">
	<h3>Open Hours</h3>
	<h5>Mon-sun(24hrs)</h5>
	</div>
	
	</div>
	
	<div class="col-md-4 col-sm-4 w3_hr">
	<div class="w3l_rl">
	<i class="fa fa-book" aria-hidden="true"></i>
	</div>
	<div class="w3l_rr">
	<a href="#appointmentform" class="scroll">
	<h3>For an Appointment</h3>
	<h5>Book Now</h5>
	</a>
	</div>
	
	</div>
	<div class="clearfix"></div>
	</div>
	</div>
<!-- /Header-->
<!-- banner-->
<div class="agile_banner">
<div class="s1">
			<h3>MAKING</h3>
			<h4>YOUR LIFE EASY AND <span class="chng">HAPPY</span></h4>
			
		
		</div>


</div>
<!--/banner-->

<!-- About-->
<div class="about" id="about">
<div class="container">
<h3>ABOUT US</h3>
<div class="col-md-6 w3ls_al">
<img src="images/Chest-Hospital.jpg" class="img-responsive" alt="">
</div>
<div class="col-md-6 w3ls_ar">
<center><h4>Medical college chest hospital was inaugurated on 1 April 1982 by the Governor of Kerala Jothi Venkatachalam. G. M. C, Thrissur had its humble beginning at Mannuthy. By March 1983, the institution had moved to its permanent site at Mulakunnathukavu, where the old buildings of the T.B. sanatorium were modified to accommodate the pre-clinical and the para-clinical departments as well as the administrative block.</h4></center>
</div>
<div class="clearfix"></div>

         
			  <div class="clearfix"></div>
			<!-- member 3 ends here  -->
          </div>
		  
      
   </div>
</div>
<!-- /About-->
<div class="contact" id="contact">
	<p class="copyright">Copyright © 2021, MCCH Thrissur.</p><center><p> All Rights Reserved | Designed by Electronics and Communication Department, </p><p>Vidya Academy of Science & Technology Thrissur</p></center></div>

    <!-- //gallery -->


	
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/move-top.js"></script>
<script  src="js/easing.js"></script>
<script  src="js/SmoothScroll.min.js"></script>	
	<!-- for testimonials slider-js-file-->
			<script src="js/owl.carousel.js"></script>
	<!-- //for testimonials slider-js-file-->
		<script>
		$(document).ready(function() { 
		$("#owl-demo").owlCarousel({
 
			autoPlay: 3000, //Set AutoPlay to 3 seconds
			autoPlay:true,
			items : 3,
			itemsDesktop : [640,5],
			itemsDesktopSmall : [414,4]
		});
		}); 
</script>
<!-- for testimonials slider-js-script-->

 <!--script-->
<script src="js/easyResponsiveTabs.js" type="text/javascript"></script>
		    <script type="text/javascript">
			    $(document).ready(function () {
			        $('#horizontalTab').easyResponsiveTabs({
			            type: 'default', //Types: default, vertical, accordion           
			            width: 'auto', //auto or any width like 600px
			            fit: true   // 100% fit in a container
			        });
			    });
				
</script>
<!--script-->
<!-- Calendar -->
<script src="js/jquery-ui.js"></script>
	<script>
		$(function() {
		$( "#datepicker,#datepicker1" ).datepicker();
		});
	</script>
<!-- //Calendar -->
<!-- /gallery -->
    <script src="js/jquery.tools.min.js"></script>
    <script src="js/jquery.mobile.custom.min.js"></script>
    <script src="js/jquery.cm-overlay.js"></script>

    <script>
        $(document).ready(function () {
            $('.cm-overlay').cmOverlay();
        });
    </script>
    <!-- //gallery -->
<!-- start-smoth-scrolling -->
	<script type="text/javascript">
		$(document).ready(function() {
			/*
				var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
				};
			*/
								
			$().UItoTop({ easingType: 'easeOutQuart' });
								
			});
	</script>
<!-- scrolling script -->
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script> 
<!-- //scrolling script -->
<!--//start-smoth-scrolling -->

</body>
</html>