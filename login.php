<?php
$page_title = "Login to your Haze account";
$page_description = "Login to Haze and make keeping track of your video game collection easy. Everything is in one place, and you can log your progress whenever you want.";
$page_keywords = array("game collection", "video game tracker", "track games", "track game", "video games", "computer games", "free", "steam", "origin", "ea origin", "valve games", "half-life", "backloggery", "grouvee");

/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");
if(isset($_POST['submitted']))
{
   if($site->Login())
   {
        $site->RedirectToURL("main.php");
   }
}
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
		<div class="col-lg-6, col-md-6 col-sm-6 hidden-xs center-block">
			<img class="img-responsive" src="http://images.freeimages.com/images/previews/e54/world-cyber-games-2004-finals-1440376.jpg" alt="Demystify your game collection with Haze." />
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h1>Login to your Haze account</h1>
			<p>Login to Haze, and make keeping track of your video game collection easy.</p>
			<form id = 'login' action = '<?php echo $site->GetSelfScript(); ?>' method = 'post' accept-charset='UTF-8'>
				<input type = 'hidden' name = 'submitted' id = 'submitted' value = '1'/>
				<div class="form-group">
					<label for="username">Username *</label>
					<input type="text" id="username" name = "username" value = '<?php echo $site->SafeDisplay('username') ?>' maxlength = "50" class="form-control" placeholder="Enter your username here." required autofocus autocomplete>
				</div>

				<div class="form-group">
					<label for="password">Password *</label>
					<input type="password" id="password" name = "password" maxlength = "50" class="form-control" placeholder="Enter your password here." required autofocus autocomplete>
				</div>

				<input type="submit" name="login" class="btn btn-primary" value="Log me in!">
				<div><span class='error'><?php echo $site->GetErrorMessage(); ?></span></div>
			</form>
		</div>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
