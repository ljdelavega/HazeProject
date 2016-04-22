<?php
$page_title = "Welcome to Haze - Track your video game collection!";
$page_description = "Start tracking your progress with your video game collection.";
$page_keywords = array("game collection", "video game tracker", "track games", "track game", "video games", "computer games", "free", "steam", "origin", "ea origin", "valve games", "half-life", "backloggery", "grouvee");

/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");

// log the current user out.
$site->LogOut();

?>

<body>
<div class="container-fluid">

  <h2>You have logged out.</h2>
  <p>
    <a class="btn btn-success" href='login.php'>Login Again</a>
  </p>

</div>
</body>

<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
