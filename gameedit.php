<?php
$page_title = "Edit a game";
$page_description = "Edit a video game in your collection.";
$page_keywords = array("game collection", "video game tracker", "track games", "track game", "video games", "computer games", "free", "steam", "origin", "ea origin", "valve games", "half-life", "backloggery", "grouvee");

/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");


if(isset($_POST['submitted']))
{
	if ($site->UpdateGame())
	{
		echo "Game successfully updated! \r\n";
			$site->RedirectToURL("main.php");
	}
}


?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
    <div class="col-xs-6">
      <h1>Edit game in your game list</h1>
      <form id='update' action='<?php echo $site->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
				<input type='hidden' name='submitted' id='submitted' value='1'/>
				<div class="form-group">
					<label for="game_name">Game Name *</label>
					<input type="text" id="game_name" name="game_name" class="form-control" placeholder="e.g. Super Mario Bros." required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="genre">Genre *</label>
					<input type="text" id="genre" name="genre" class="form-control" placeholder="e.g. First-person shooter" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="Price">Price *</label>
					<input type="text" id="price" name="price" class="form-control" placeholder="e.g. 23" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="completion_state">Completion state *</label>
          <div class="radio">
            <label><input type="radio" id="completion_state" name="optradio">Unplayed</label>
          </div>
          <div class="radio">
            <label><input type="radio" id="completion_state" name="optradio">Unfinished</label>
          </div>
          <div class="radio">
            <label><input type="radio" id="completion_state" name="optradio">Completed</label>
          </div>
          <div class="radio">
            <label><input type="radio" id="completion_state" name="optradio">Other</label>
          </div>
				</div>

				<input type="submit" name="Submit" class="btn btn-primary" value="Save edits">
        <a class="btn btn-default" href="main.php" role="button">Back to Game List</a>
			</form>

      <br />
    </div>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
