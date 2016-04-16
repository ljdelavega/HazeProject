<?php
$page_title = "Edit Your Haze Profile";
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
      <h1>Edit Profile: {{username}}</h1>
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
					<input type="password" id="password" class="form-control" placeholder="Enter a strong password here." required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="password_reconfirm">Re-confirm password *</label>
					<input type="password" id="password_reconfirm" class="form-control" placeholder="Re-enter the password." required autofocus autocomplete>
				</div>
				<input type="submit" name="signup" class="btn btn-primary" value="Update user">
        <a class="btn btn-default" href="user.php" role="button">Back to Profile</a>
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

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
