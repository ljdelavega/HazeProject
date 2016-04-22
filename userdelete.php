<?php
$page_title = "Edit Your Haze Profile";
$page_description = "Get started tracking the progress of your video game collection.";
$page_keywords = array("game collection", "video game tracker", "track games", "track game", "video games", "computer games", "free", "steam", "origin", "ea origin", "valve games", "half-life", "backloggery", "grouvee");

/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
    <div class="col-xs-12">
      <h1>Your user account has been deleted.</h1>
      <p>Thank you for using Haze!</p>
      <p>(To our Japanese users: さいて頂き有り難うございました。)</p>
      <a class="btn btn-default" href="index.php" role="button">Go to Home Page</a>
    </div>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
