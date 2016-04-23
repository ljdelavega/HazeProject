<?php
$page_title = "Edit a game";
$page_description = "Edit a video game in your collection.";
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

// user submits update
if(isset($_POST['submitted']))
{
	if ($site->UpdateGame())
	{
		echo "Game successfully updated! \r\n";
			$site->RedirectToURL("main.php");
	}
}

//user clicks delete
if(isset($_POST['deleted']))
{
	if ($site->DeleteGame())
	{
		echo "Game successfully deleted! \r\n";
			$site->RedirectToURL("main.php");
	}
}

// get vars based on passed in game_id from session
$game_id = $_GET['game'];
$_SESSION['game_id'] = $game_id;
$game_result = $site->GetGameByID($game_id);
if (!$game_result)
{
	echo "There is no game with this ID: " . $game_id ;
}
$game = mysqli_fetch_array($game_result);

$completion_state_result = $site->GetCompletionStateByID($game_id);
if (!$completion_state_result)
{
	echo "There is no completion state with this ID: " . $game_id ;
}
$completion_state = mysqli_fetch_array($completion_state_result);
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
					<input type="text" id="game_name" name="game_name" class="form-control" value="<?php echo $game['game_name']; ?>" placeholder="e.g. Super Mario Bros." required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="genre">Genre *</label>
					<input type="text" id="genre" name="genre" class="form-control" value="<?php echo $game['genre']; ?>" placeholder="e.g. First-person shooter" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="price">Price *</label>
					<input type="number" id="price" name="price" min="0.00" step="0.01" max="2500" value ="<?php echo $game['price']; ?>" class="form-control" placeholder="e.g. 69.99" required autofocus autocomplete>
				</div>
        <div class="form-group">
					<label for="hours">Hours Played *</label>
					<input type="number" id="hours" name="hours" value ="<?php echo $completion_state['hours'];?>" class="form-control" placeholder="e.g. 20" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="completion_state">Completion state *</label>
          <div class="radio">
            <label><input type="radio" id="completion_state" value="<?php echo $site->SafeDisplay('unplayed') ?>" name="completion_state" <?php echo ($completion_state['state']=='unplayed')?'checked':'' ?>>Unplayed</label>
          </div>
          <div class="radio">
            <label><input type="radio" id="completion_state" value="<?php echo $site->SafeDisplay('unfinished') ?>" name="completion_state" <?php echo ($completion_state['state']=='unfinished')?'checked':'' ?>>Unfinished</label>
          </div>
          <div class="radio">
            <label><input type="radio" id="completion_state" value="<?php echo $site->SafeDisplay('completed') ?>" name="completion_state" <?php echo ($completion_state['state']=='completed')?'checked':'' ?>>Completed</label>
          </div>
          <div class="radio">
            <label><input type="radio" id="completion_state" value="<?php echo $site->SafeDisplay('other') ?>" name="completion_state" <?php echo ($completion_state['state']=='other')?'checked':'' ?>>Other</label>
          </div>
				</div>

				<input type="submit" name="gameedit" class="btn btn-primary" value="Save edits">
        <a class="btn btn-default" href="main.php" role="button">Back to Game List</a>
			</form>

      <br />

	  <div class="panel panel-danger">
		<div class="panel-heading">Delete Game</div>
		<div class="panel-body">
		<p>
			Are You Sure?!
		</p>
		<form id='delete' action='<?php echo $site->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
				<input type='hidden' name='deleted' id='deleted' value='1'/>
				<input type="submit" name="Delete" class="btn btn-primary" value="Yes! Delete">
			</form>
    </div>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
