<?php
$page_title = "Add a new game";
$page_description = "Add a new video game to your collection.";
$page_keywords = array("game collection", "video game tracker", "track games", "track game", "video games", "computer games", "free", "steam", "origin", "ea origin", "valve games", "half-life", "backloggery", "grouvee");

/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");

if(!$site->CheckLogin())
{
    $site->RedirectToURL("login.php");
    exit;
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

if(isset($_POST['submitted']))
{
   if($site->RegisterReview())
   {
        echo "Added a new game! \r\n";
   }
}
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
    <div class="col-xs-6">
      <h1>Add new review for: <?php echo $game['game_name']; ?></h1>
      <form id='reviewadd' action='<?php echo $site->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
				<input type='hidden' name='submitted' id='submitted' value='1'/>
				<input type='hidden' name='username' id='username' value='<?php $_SESSION['username'] ?>'/>
				<input type='hidden' name='game_id' id='game_id' value='<?php $_SESSION['game_id'] ?>'/>
				<div class="form-group">
					<label for="rating">Rating *</label>
					<select class="form-control" id="rating">
						<option>5</option>
						<option>4</option>
						<option>3</option>
						<option>2</option>
						<option>1</option>
					</select>
				</div>
				<div class="form-group">
					<label for="text-review">Text Review *</label>
					<textarea id="text-review" class="form-control" value ="<?php echo $site->SafeDisplay('text_review') ?>" rows="15" placeholder="Talk about your experiences with the game. Did you enjoy it? What parts did you like and dislike?" required autofocus autocomplete></textarea>
				</div>

				<input type="submit" name="reviewadd" class="btn btn-primary" value="Add review">
        <a class="btn btn-default" href="main.php" role="button">Back to Game List</a>
				<div><span class='error'><?php echo $site->GetErrorMessage(); ?></span></div>
			</form>

      <br />
    </div>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
