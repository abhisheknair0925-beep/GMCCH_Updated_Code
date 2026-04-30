<?php
ob_flush();
session_start();
$email=$_SESSION['email'];
if(!$email)
{
    header('location:index.php');
}
?>


<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
<title>M C Chest Hospital<?php echo $email ?></title>
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
						<a class="navbar-brand" href="index.html">
							<h1>
								<img src="images/new_logo_2.png" style="height: 50px;width: 50px;">
								MCC HOSPITAL
								
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
							</li>
							<li class="dropdown">
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
	
<!-- /Header-->
