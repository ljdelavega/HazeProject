<?php
$page_title = "Welcome to Haze - Track your video game collection!";
$page_description = "Start tracking your progress with your video game collection.";
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

//get variables from session
$username = $_SESSION['username'];
$firstname = $_SESSION['firstname'];
$lastname = $_SESSION['lastname'];
$list_id = $_SESSION['list_id'];
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
		<div class="col-xs-12 bg-primary">
			<h1>Welcome <?php echo $username;?>!</h1>
      <p><?php echo $firstname;?> <?php echo $lastname;?></p>
		</div>
    <div class="col-xs-12">
			<a href="gameadd.php" class="btn btn-primary">Add Game</a>
		</div>
    <div class="col-xs-12">
      <h1>Your Game List</h1>
      <table class="table table-striped">
				<tr>
          <th>Game</th>
					<th>Price</th>
          <th>Genre</th>
					<th>Actions</th>
        </tr>
				<?php
					// populate the game list with user's list of games.
					$gamelist = $site->GetGamesFromList($list_id);
					if (!$gamelist)
					{
						echo "You currently have no games in your list.";
					}
					else
					{
	           while ($row = mysqli_fetch_array($gamelist)) {?>
	               <tr>
	               <td><?php echo $row['game_name'];?></td>
	               <td><?php echo $row['price'];?></td>
	               <td><?php echo $row['genre'];?></td>
	               <td>
									 <a class="btn btn-primary" href="gameedit.php<?php $_SESSION['game_id'] = $row['game_id'];?>">Edit</a>
		             	 <button class="btn">Review</button>
							 	 </td>
	               </tr>
	  	<?php }
					} ?>
      </table>
    </div>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
