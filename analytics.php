<?php
$page_title = "Analytics - Analyze your video game collection!";
$page_description = "See all your games and view statistics to analyze.";
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

// populate the game list with user's list of games.
$gamelist = $site->GetGamesFromList($list_id);
$total_price = 0;
$total_hours = 0;
$total_price_vs_hours = 0;
if (!$gamelist)
{
  echo "You currently have no games in your list.";
}
else
{
  //loop through to get total price and total hours
   while ($row = mysqli_fetch_array($gamelist)) {
       $total_price += $row['price'];
       // now get hours from the completion state for the game.
       $completion_state_result = $site->GetCompletionStateByID($row['game_id']);
       if (!$completion_state_result)
       {
       	echo "There is no completion state with this ID: " . $game_id ;
       }
       $completion_state = mysqli_fetch_array($completion_state_result);
       $total_hours += $completion_state['hours'];
     }
}
$total_price_vs_hours = round (($total_price / $total_hours), 2);
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
		<div class="col-xs-12 bg-primary">
			<h1>Analytics Overview for: <?php echo $username;?>!</h1>
		</div>
    <div class="col-xs-12">
      <h1>Overall Stats</h1>
      <div class="row">
        <div class="well col-xs-4">
          <h3>Total Hours Played</h3>
          <p><?php echo $total_hours ?></p>
        </div>
        <div class="well col-xs-4">
          <h3>Total Money Spent</h3>
          <p><?php echo $total_price ?></p>
        </div>
        <div class="well col-xs-4">
          <h3>Average Price Per Hour</h3>
          <p><?php echo $total_price_vs_hours ?></p>
        </div>
      </div>
		</div>
    <div class="col-xs-12">
      <h1>Individual Game Stats</h1>
      <table class="table table-striped">
				<tr>
          <th>Game</th>
					<th>Price</th>
					<th>Hours</th>
          <th>Price Per Hour</th>
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
	           while ($row = mysqli_fetch_array($gamelist)) {
               // now get hours from the completion state for the game.
               $completion_state_result = $site->GetCompletionStateByID($row['game_id']);
               if (!$completion_state_result)
               {
               	echo "There is no completion state with this ID: " . $game_id ;
               }
               $completion_state = mysqli_fetch_array($completion_state_result);
               ?>
	               <tr>
	               <td><?php echo $row['game_name'];?></td>
	               <td><?php echo $row['price'];?></td>
	               <td><?php echo $completion_state['hours'];?></td>
                 <td><?php echo round(($row['price'] / $completion_state['hours']), 2);?></td>
	               </tr>
	  	<?php }
					} ?>
      </table>
    </div>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
