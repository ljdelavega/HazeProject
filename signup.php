<?php
$page_title = "Create your free Haze account";
$page_description = "Sign up for Haze and make keeping track of your video game collection easy. Everything is in one place, and you can log your progress whenever you want.";
$page_keywords = array("game collection", "video game tracker", "track games", "track game", "video games", "computer games", "free", "steam", "origin", "ea origin", "valve games", "half-life", "backloggery", "grouvee");

/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");

if(isset($_POST['submitted']))
{
   if($site->RegisterUser())
   {
        echo "Thanks for registering! \r\n";
   }
}

?>

<script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
		<div class="col-lg-6, col-md-6 col-sm-6 hidden-xs center-block">
			<img class="img-responsive" src="http://images.freeimages.com/images/previews/e54/world-cyber-games-2004-finals-1440376.jpg" alt="Demystify your game collection with Haze." />
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h1>Create your Haze account</h1>
			<p>Sign up for Haze, and make keeping track of your video game collection easy.</p>
			<form id='signup' action='<?php echo $site->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
				<input type='hidden' name='submitted' id='submitted' value='1'/>
				<div class="form-group">
					<label for="username">Username *</label>
					<input type="text" id="username" name="username" value="<?php echo $site->SafeDisplay('username') ?>" class="form-control" placeholder="e.g. XxXLaraCroftBby" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="first_name">First name *</label>
					<input type="text" id="first_name" name="first_name" value="<?php echo $site->SafeDisplay('first_name') ?>"  class="form-control" placeholder="e.g. Lara" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="last_name">Last name *</label>
					<input type="text" id="last_name" name="last_name" value="<?php echo $site->SafeDisplay('last_name') ?>"  class="form-control" placeholder="e.g. Croft" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="input_email">Email address *</label>
					<input type="email" id="input_email" name="input_email" value="<?php echo $site->SafeDisplay('input_email') ?>" class="form-control" placeholder="e.g. lara.croft@example.com" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="password">Password *</label>
					<input type="password" id="password" name="password" value="<?php echo $site->SafeDisplay('password') ?>" class="form-control" placeholder="Enter a strong password here." required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="password_reconfirm">Re-confirm password *</label>
					<input type="password" id="password_reconfirm" name="password_reconfirm" value="<?php echo $site->SafeDisplay('password_reconfirm') ?>" class="form-control" placeholder="Re-enter the password." required autofocus autocomplete>
				</div>
				<div><span class='error'><?php echo $site->GetErrorMessage(); ?></span></div>
				<input type="submit" name="Submit" class="btn btn-primary" value="Sign me up!">
			</form>
		</div>
	</div>
</div>
<!-- End content -->
<script type='text/javascript'>

    var frmvalidator  = new Validator("signup");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();
    frmvalidator.addValidation("username","req","Please provide your username!");
		frmvalidator.addValidation("first_name","req","Please provide your first name!");
		frmvalidator.addValidation("last_name","req","Please provide your last name!");
    frmvalidator.addValidation("input_email","req","Please provide your email address!");
    frmvalidator.addValidation("input_email","email","Please provide a valid email address!");
    frmvalidator.addValidation("password","req","Please provide a password!");
		frmvalidator.addValidation("password_reconfirm","req","Please confirm your password!");

</script>

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
