<?php
$page_title = "Add a new game";
$page_description = "Add a new video game to your collection.";
$page_keywords = array("game collection", "video game tracker", "track games", "track game", "video games", "computer games", "free", "steam", "origin", "ea origin", "valve games", "half-life", "backloggery", "grouvee");

/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");

if(isset($_POST['submitted']))
{
   if($site->RegisterGame())
   {
        echo "Added a new game! \r\n";
   }
}
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
    <div class="col-xs-6">
      <h1>Add new game to your game list</h1>
      <form id='gameadd' action='<?php echo $site->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
				<input type='hidden' name='submitted' id='submitted' value='1'/>
				<div class="form-group">
					<label for="game_name">Game Name *</label>
					<input type="text" id="game_name" name="game_name" value ="<?php echo $site->SafeDisplay('game_name') ?>" class="form-control" placeholder="e.g. Super Mario Bros." required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="genre">Genre *</label>
					<input type="text" id="genre" name="genre" value ="<?php echo $site->SafeDisplay('genre') ?>" class="form-control" placeholder="e.g. First-person shooter" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="price">Price *</label>
					<input type="number" id="price" name="price" min="0.01" step="0.01" max="2500" value ="<?php echo $site->SafeDisplay('price') ?>" class="form-control" placeholder="e.g. 69.99" required autofocus autocomplete>
				</div>
        <div class="form-group">
					<label for="hours">Hours Played *</label>
					<input type="number" id="hours" name="hours" value ="<?php echo $site->SafeDisplay('hours') ?>" class="form-control" placeholder="e.g. 20" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="completion_state">Completion state *</label>
          <div class="radio">
            <label><input type="radio" id="completion_state" value="<?php echo $site->SafeDisplay('unplayed') ?>" name="completion_state" checked="checked">Unplayed</label>
          </div>
          <div class="radio">
            <label><input type="radio" id="completion_state" value="<?php echo $site->SafeDisplay('unfinished') ?>" name="completion_state">Unfinished</label>
          </div>
          <div class="radio">
            <label><input type="radio" id="completion_state" value="<?php echo $site->SafeDisplay('completed') ?>" name="completion_state">Completed</label>
          </div>
          <div class="radio">
            <label><input type="radio" id="completion_state" value="<?php echo $site->SafeDisplay('other') ?>" name="completion_state">Other</label>
          </div>
				</div>

				<input type="submit" name="gameadd" class="btn btn-primary" value="Add game">
        <a class="btn btn-default" href="main.php" role="button">Back to Game List</a>
		<div><span class='error'><?php echo $site->GetErrorMessage(); ?></span></div>
			</form>

      <br />
    </div>
	</div>
</div>
<!-- End content -->


<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
