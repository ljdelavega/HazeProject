<?php
$page_title = "Welcome to Haze - Track your video game collection!";
$page_description = "Start tracking your progress with your video game collection.";
$page_keywords = array("game collection", "video game tracker", "track games", "track game", "video games", "computer games", "free", "steam", "origin", "ea origin", "valve games", "half-life", "backloggery", "grouvee");

/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
		<div class="col-xs-12 bg-primary">
			<h1>Welcome {{username}}!</h1>
      <p>{{first name}}{{last name}}</p>
		</div>

    <div class="col-xs-12">
      <h1>Your Game List</h1>
      <table class="table table-striped">
        <tr>
          <th>Game</th>
          <th>Actions</th>
        </tr>
        <tr>
          <td>League of Legends</td>
          <td>
            <button class="btn btn-primary">Edit</button>
            <button class="btn">Review</button>
          </td>
        </tr>
        <tr>
          <td>Dead or Alive: Xtreme Beach Volleyball</td>
          <td>
            <button class="btn btn-primary">Edit</button>
            <button class="btn">Review</button>
          </td>
        </tr>
        <tr>
          <td>XCOM 2</td>
          <td>
            <button class="btn btn-primary">Edit</button>
            <button class="btn">Review</button>
          </td>
        </tr>
        <tr>
          <td>Daikatana</td>
          <td>
            <button class="btn btn-primary">Edit</button>
            <button class="btn">Review</button>
          </td>
        </tr>
      </table>
    </div>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
