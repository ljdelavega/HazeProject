<?php
require_once("resources/config.php");
$logged_in = $site->CheckLogin();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title><?php echo $page_title;?></title>

		<meta name="description" content="<?php echo $page_description;?>" />
		<meta name="keywords" content="<?php echo implode(", " , $page_keywords);?>">

		<!-- Bootstrap -->
		<link href="/resources/library/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="/css/style.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<nav role="navigation" class="navbar navbar-inverse">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="index.php" class="navbar-brand">Haze</a>
			</div>
			<!-- Collection of nav links and other content for toggling -->
			<div id="navbarCollapse" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li class="active"><a href="main.php">Your Game List</a></li>
					<li><a href="analytics.php">Analytics</a></li>
					<li><a href="reviews.php">Reviews</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<?php if ($logged_in) { ?>
						<li><a href="user.php">Your Profile</a></li>
						<li>
							<p class="navbar-btn">
	            <a class="btn btn-primary" href="logout.php" title="">Logout</a>
							</p>
						</li>
	        <?php } else { ?>
							<li><a href="login.php">Log in</a></li>
							<li>
								<p class="navbar-btn">
								<a class="btn btn-success" href="signup.php">Sign up</a>
								</p>
							</li>
	        <?php } ?>
				</ul>
			</div>
		</nav>
