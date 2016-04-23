<?php
$page_title = "Edit Your Haze Profile";
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
$password = $_SESSION['password'];

if(isset($_POST['submitted']))
{
   if($site->UpdateUser())
   {
        echo "Profile successfully updated! \r\n";
				$site->RedirectToURL("user.php");
   }
}
?>
<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
    <div class="col-xs-12">
      <h1>Edit Profile: <?php echo $username;?></h1>
			<form id='update' action='<?php echo $site->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
				<input type='hidden' name='submitted' id='submitted' value='1'/>
				<div class="form-group">
					<label for="first_name">First name *</label>
					<input type="text" id="first_name" name="first_name" value="<?php echo $firstname;?>"  class="form-control" placeholder="e.g. Lara" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="last_name">Last name *</label>
					<input type="text" id="last_name" name="last_name" value="<?php echo $lastname;?>"  class="form-control" placeholder="e.g. Croft" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="input_email">Email address *</label>
					<input type="email" id="input_email" name="input_email" value="<?php echo $email;?>" class="form-control" placeholder="e.g. lara.croft@example.com" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="password">Password *</label>
					<input type="password" id="password" name="password" value="<?php echo $password;?>" class="form-control" placeholder="Enter a strong password here." required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="password_reconfirm">Re-confirm password *</label>
					<input type="password" id="password_reconfirm" name="password_reconfirm" value="<?php echo $password;?>" class="form-control" placeholder="Re-enter the password." required autofocus autocomplete>
				</div>
				<div><span class='error'><?php echo $site->GetErrorMessage(); ?></span></div>
				<input type="submit" name="Submit" class="btn btn-primary" value="Update!">
			</form>

      <br />

      <div class="panel panel-danger">
        <div class="panel-heading">Delete Account</div>
        <div class="panel-body">
          <p>
            We hope that you really enjoy using Haze, but if you feel that it's time to pack it in, we won't stop you.
          </p>
          <p class="label label-warning">NOTE:</p>
          <p>
            Clicking the below button will permanently remove your account from the system. You won't be able to log in or see your profile anymore. We mean business here!</p>
          <p>Ready to say <i>sayonara</i>?</p>
          <a class="btn btn-danger" href="userdelete.php" role="button">Delete Account</a>
        </div>
      </div>
    </div>
	</div>
</div>

<!-- End content -->
<script type='text/javascript'>

    var frmvalidator  = new Validator("update");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();
		frmvalidator.addValidation("first_name","req","Please provide your first name!");
		frmvalidator.addValidation("last_name","req","Please provide your last name!");
    frmvalidator.addValidation("input_email","req","Please provide your email address!");
    frmvalidator.addValidation("input_email","email","Please provide a valid email address!");
    frmvalidator.addValidation("password","req","Please provide a password!");
		frmvalidator.addValidation("password_reconfirm","req","Please confirm your password!");

</script>
<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
