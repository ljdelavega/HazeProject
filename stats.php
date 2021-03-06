<?php
$page_title = "Your video game collection statistics with Haze.";
$page_description = "Take a look at the hours you've played, and how much you've spent.";
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
$list_id = $_SESSION['list_id'];
?>

<!-- Insert content here -->
<div class = "container-fluid">
	<div class="row">
		<div class="col-xs-12 bg-primary">
			<h1>Statistics for <?php echo $username;?></h1>
      <p class="lead">Here are some of your statistics for your game list.</p>
		</div>
    <div class="col-xs-12">
      <h1>Total Hours Played: {{hours}}<h1>
      <br />
      <h2>Your Game List</h2>
      <table class="table table-striped">
				<tr>
          <th>Game</th>
					<th>Price</th>
          <th>Hours Played</th>
					<th>Price to Hours Played Ratio</th>
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
	               <td><?php echo $row['name'];?></td>
	               <td><?php echo $row['price'];?></td>
	               <td><?php echo $row['hours'];?></td>
	               <td><?php echo $row['price'] / $row['hours']?></td>
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
