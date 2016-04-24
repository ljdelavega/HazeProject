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

if(isset($_POST['submitted']))
{
   if($site->ImportSteamGames())
   {
      echo "
        <div class='container'>
          <div class='row'>
            <div class='col-xs-12'>
              <h1>Success!</h1>
              <p class='lead'>Added a Steam library to your game list!</p>
              <a class='btn btn-default' href='main.php' role='button'>Back to Game List</a>
            </div>
          </div>
        </div>

        ";
		  $site->RedirectToURL("main.php");
   }
}
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
		<div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
			<h1>Import from Steam <small>Get Your Game Names in Haze</small></h1>
			<p class="lead">As a gamer, you might have a game library on Steam that you'd like to see in Haze. Don't worry, you don't have to enter anything in manually.</p>
			<p>For ease of use, we'll give you the option to import your game library from Steam into your Haze library.</p>
      <p><strong>Example ID:</strong> 76561198025201892</p>
			<form id='importfromsteam' action='<?php echo $site->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
        <input type='hidden' name='submitted' id='submitted' value='1'/>
        <div class="form-group">
			    <label for="steam_id">Your Steam ID</label>
			    <input type="text" name="steam_id" value ="<?php echo $site->SafeDisplay('steam_id') ?>" class="form-control" id="steam_id" placeholder="an integer -- e.g. 76561198025201892">
			  </div>
        <div id="spinner" style="display:none;"><img src="img/spinner.gif" alt="Loading animation" /></div>
			  <input type="submit" name="importfromsteam" class="btn btn-primary" value="Import Steam library" onclick="toggle_visibility('spinner')";>
				<p>Don't have a Steam account? <a href="main.php">No worries, head back to your game list >></a></p>
        <div><span class='error'><?php echo $site->GetErrorMessage(); ?></span></div>
			</form>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-6 hidden-xs center-block">
			<img class="img-responsive" src="img/steam_logo.svg" alt="Steam logo" />
		</div>
	</div>
</div>
<!-- End content -->

<script type="text/javascript">
<!--
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
//-->
</script>
<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
