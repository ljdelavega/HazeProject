<?php
$page_title = "Deleted Your Haze Account";
$page_description = "You have deleted your Haze account.";
$page_keywords = array("game collection", "video game tracker", "track games", "track game", "video games", "computer games", "free", "steam", "origin", "ea origin", "valve games", "half-life", "backloggery", "grouvee");

/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");

// TODO: for some reason the user isn't actually being deleted.
if($site->DeleteUser())
{
	// log the current user out.
	$site->LogOut();
}
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
