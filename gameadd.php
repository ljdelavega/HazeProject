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
        echo "Thanks for registering! \r\n";
   }
}
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
    <div class="col-xs-6">
      <h1>Add new game to your game list</h1>
      <form id='signup' action='<?php echo $site->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
				<input type='hidden' name='submitted' id='submitted' value='1'/>
				<div class="form-group">
					<label for="game_name">Game Name *</label>
					<input type="text" id="game_name" value ="<?php echo $site->SafeDisplay('game_name') ?>" class="form-control" placeholder="e.g. Super Mario Bros." required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="genre">Genre *</label>
					<input type="text" id="genre" value ="<?php echo $site->SafeDisplay('genre') ?>" class="form-control" placeholder="e.g. First-person shooter" required autofocus autocomplete>
				</div>
				<div class="form-group">
					<label for="price">Price *</label>
					<input type="text" id="price" value ="<?php echo $site->SafeDisplay('price') ?>" class="form-control" placeholder="e.g. 23" required autofocus autocomplete>
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

				<input type="submit" name="signup" class="btn btn-primary" value="Add game">
        <a class="btn btn-default" href="main.php" role="button">Back to Game List</a>
		<div><span class='error'><?php echo $site->GetErrorMessage(); ?></span></div>
			</form>

      <br />
    </div>
	</div>
</div>
<!-- End content -->
<script type='text/javascript'>

    var frmvalidator  = new Validator("signup");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();
    $validator->addValidation("game_name","req","Please enter the title!");
    $validator->addValidation("genre","req","Please enter the genre!");
    $validator->addValidation("price","req","Please enter the price!");
    $validator->addValidation("completion_state","req","Please select a completion state!");

</script>

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
