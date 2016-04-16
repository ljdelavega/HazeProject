<?php
$page_title = "Your Haze profile";
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
      <h1>{{username}}</h1>
      <h2>Information</h2>
      <p><strong>First Name: </strong>{{first_name}}</p>
      <p><strong>Last Name: </strong>{{last_name}}</p>
      <p><strong>Email: </strong>{{email_address}}</p>

      <a class="btn btn-success" href="useredit.php" role="button">Edit User Details</a>
    </div>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
