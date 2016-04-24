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
	if ($site->UpdateReview())
	{
		echo "Game successfully updated! \r\n";
			$site->RedirectToURL("reviews.php");
	}
}

//user clicks delete
if(isset($_POST['deleted']))
{
	if ($site->DeleteGame())
	{
		echo "Game successfully deleted! \r\n";
			$site->RedirectToURL("reviews.php");
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
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
    <div class="col-xs-6">
      <h1>Edit Your Review</h1>
      <form id='update' action='<?php echo $site->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
				<input type='hidden' name='submitted' id='submitted' value='1'/>

				<div class="form-group">
					<label for="rating">Rating *</label>
					<select name="rating" class="form-control" id="rating">
						<option value="5">5</option>
						<option value="4">4</option>
						<option value="3">3</option>
						<option value="2">2</option>
						<option value="1">1</option>
					</select>
				</div>
				<div class="form-group">
					<label for="text-review">Text Review *</label>
					<textarea id="text-review" name="text_review" class="form-control" value ="<?php echo $site->SafeDisplay('text_review') ?>" rows="15" placeholder="Talk about your experiences with the game. Did you enjoy it? What parts did you like and dislike?" required autofocus autocomplete></textarea>
				</div>
				
				<input type="submit" name="gameedit" class="btn btn-primary" value="Save edits">
        <a class="btn btn-default" href="main.php" role="button">Back to Reviews</a>
			</form>

      <br />

	  <div class="panel panel-danger">
		<div class="panel-heading">Delete Review</div>
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
