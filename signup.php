<?php
$page_title = "Create your free Haze account";
$page_description = "Sign up for Haze and make keeping track of your video game collection easy. Everything is in one place, and you can log your progress whenever you want.";
$page_keywords = array("game collection", "video game tracker", "track games", "track game", "video games", "computer games", "free", "steam", "origin", "ea origin", "valve games", "half-life", "backloggery", "grouvee");

/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");
require_once(LIBRARY_PATH . "/formvalidator.php");
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
		<div class="col-lg-6, col-md-6 col-sm-6 hidden-xs center-block">
			<img class="img-responsive" src="http://images.freeimages.com/images/previews/e54/world-cyber-games-2004-finals-1440376.jpg" alt="Demystify your game collection with Haze." />
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h1>Create your Haze account</h1>
			<p>Sign up for Haze, and make keeping track of your video game collection easy.</p>
			<form>
				<div class="form-group">
					<label for="username">Username *</label>
					<input type="text" id="username" class="form-control" placeholder="e.g. XxXLaraCroftBby" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="first_name">First name *</label>
					<input type="text" id="first_name" class="form-control" placeholder="e.g. Lara" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="last_name">Last name *</label>
					<input type="text" id="last_name" class="form-control" placeholder="e.g. Croft" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="input_email">Email address *</label>
					<input type="email" id="input_email" class="form-control" placeholder="e.g. lara.croft@example.com" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="password">Password *</label>
					<input type="text" id="password" class="form-control" placeholder="Enter a strong password here." required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="password_reconfirm">Re-confirm password *</label>
					<input type="password" id="password_reconfirm" class="form-control" placeholder="Re-enter the password." required autofocus autocomplete>
				</div>
				<input type="submit" name="signup" class="btn btn-primary" value="Sign me up!">
			</form>
		</div>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
