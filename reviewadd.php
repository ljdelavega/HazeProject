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
      <h1>Add new review for: {{game_name}}</h1>
      <form>
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
					<textarea id="text-review" class="form-control" rows="15" placeholder="Talk about your experiences with the game. Did you enjoy it? What parts did you like and dislike?" required autofocus autocomplete></textarea>
				</div>

				<input type="submit" name="signup" class="btn btn-primary" value="Add review">
        <a class="btn btn-default" href="main.php" role="button">Back to Game List</a>
			</form>

      <br />
    </div>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
