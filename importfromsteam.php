<?php
$page_title = "Import your game library from Steam into Haze.";
$page_description = "Haze keeps track of everything, including your Steam games.";
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
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
		<div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
			<h1>Import from Steam <small>Get Your Game Names in Haze</small></h1>
			<p class="lead">As a gamer, you might have a game library on Steam that you'd like to see in Haze. Don't worry, you don't have to enter anything in manually.</p>
			<p>For ease of use, we'll give you the option to import your game library from Steam into your Haze library.</p>
			<form>
			  <div class="form-group">
			    <label for="steam_id">Your Steam ID</label>
			    <input type="text" class="form-control" id="steam_id" placeholder="e.g. http://steamcommunity.com/id/example">
			  </div>
			  <button type="submit" class="btn btn-primary">Import Steam Library</button>
				<p>Don't have a Steam account? <a href="#">Skip this step</a></p>
			</form>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-6 hidden-xs center-block">
			<img class="img-responsive" src="img/steam_logo.svg" alt="Steam logo" />
		</div>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
