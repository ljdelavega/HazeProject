<?php 
$page_title = "Haze - Video Game Completion Tracker";
$page_description = "Haze is a web-based video game collection system that allows gamers to track their progress.";
$page_keywords = array("game collection", "video game tracker", "track games", "track game", "video games", "computer games");

/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class = "jumbotron">
		<h1>Track your game progress with Haze.</h1>
		<p>Haze makes it easy to keep tabs on how far you're progressing with your video and computer game collection.</p>
		<p>
			<a href="signup.php"  class = "btn btn-primary btn-lg" role = "button">Sign up now</a>
		</p>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
