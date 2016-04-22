<?php
$page_title = "Your Haze profile";
$page_description = "Get started tracking the progress of your video game collection.";
$page_keywords = array("game collection", "video game tracker", "track games", "track game", "video games", "computer games", "free", "steam", "origin", "ea origin", "valve games", "half-life", "backloggery", "grouvee");

/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");

// ensure the user is logged in
if(!$site->CheckLogin())
{
    $site->RedirectToURL("login.php");
    exit;
}

//get variables from session
$username = $_SESSION['username'];
$firstname = $_SESSION['firstname'];
$lastname = $_SESSION['lastname'];
$email = $_SESSION['email'];
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
    <div class="col-xs-12">
      <h1>Profile for <?php echo $username;?></h1>
      <h2>Information</h2>
      <p><strong>First Name: </strong><?php echo $firstname;?></p>
      <p><strong>Last Name: </strong><?php echo $lastname;?></p>
      <p><strong>Email: </strong><?php echo $email;?></p>

      <a class="btn btn-success" href="useredit.php" role="button">Edit User Details</a>
    </div>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
