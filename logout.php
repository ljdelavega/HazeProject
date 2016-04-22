<?php
$page_title = "Logged out of Haze!";
$page_description = "Start tracking your progress with your video game collection.";
$page_keywords = array("game collection", "video game tracker", "track games", "track game", "video games", "computer games", "free", "steam", "origin", "ea origin", "valve games", "half-life", "backloggery", "grouvee");

/* Note: Always load the config file for each page */
require_once("resources/config.php");
// log the current user out.
$site->LogOut();
require_once(TEMPLATES_PATH . "/header.php");
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h2>You have logged out.</h2>
      <p>
        <a class="btn btn-success" href='login.php'>Login Again</a>
      </p>
    </div>
  </div>
</div>

<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
