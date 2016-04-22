<?php
$page_title = "Add a new game";
$page_description = "Add a new video game to your collection.";
$page_keywords = array("game collection", "video game tracker", "track games", "track game", "video games", "computer games", "free", "steam", "origin", "ea origin", "valve games", "half-life", "backloggery", "grouvee");

/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
    <div class="col-xs-6">
      <h1>Add new game to your game list</h1>
      <form>
				<div class="form-group">
					<label for="game_name">Game Name *</label>
					<input type="text" id="game_name" class="form-control" placeholder="e.g. Super Mario Bros." required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="genre">Genre *</label>
					<input type="text" id="genre" class="form-control" placeholder="e.g. First-person shooter" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="hours_played">Hours played *</label>
					<input type="text" id="hours_played" class="form-control" placeholder="e.g. 23" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="completion_state">Completion state *</label>
          <div class="radio">
            <label><input type="radio" name="optradio">Unplayed</label>
          </div>
          <div class="radio">
            <label><input type="radio" name="optradio">Unfinished</label>
          </div>
          <div class="radio">
            <label><input type="radio" name="optradio">Completed</label>
          </div>
          <div class="radio">
            <label><input type="radio" name="optradio">Other</label>
          </div>
				</div>

				<input type="submit" name="signup" class="btn btn-primary" value="Add game">
        <a class="btn btn-default" href="main.php" role="button">Back to Game List</a>
			</form>

      <br />
    </div>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
