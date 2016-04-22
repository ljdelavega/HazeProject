<?php
$page_title = "Reviews of video games on Haze!";
$page_description = "Read what people thought of games in their video game collection.";
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
			<h1>Reviews</h1>
      <p>Read user reviews of the games that they own.</p>

		</div>

    <div class="col-xs-3">
      <h2>Dynasty Warrior</h2>
      <p>Reviewed by <a href="#">{{username}}</a></p>
      <p>Rating: 3</p>
      <p>
        Paleo kinfolk occupy flannel, slow-carb before they sold out street art
        brooklyn. Trust fund roof party brunch mixtape street art. Cornhole
        banjo man bun, synth keffiyeh raw denim quinoa pickled chillwave organic
        lumbersexual chicharrones. Bicycle rights master cleanse yuccie,
        3 wolf moon fashion axe vice mustache asymmetrical vinyl kickstarter ugh
        chambray chicharrones salvia. Asymmetrical viral microdosing scenester,
        jean shorts direct trade sriracha kombucha occupy YOLO. Meggings normcore
        typewriter, cold-pressed leggings austin flexitarian iPhone. Echo park
        kogi sriracha, butcher green juice cornhole normcore lo-fi fanny pack
        biodiesel brunch ramps ennui migas kickstarter.
      </p>
      <p><a href="#">Read full review ></a></p>
    </div>
    <div class="col-xs-3">
      <h2>XCOM 2</h2>
      <p>Reviewed by <a href="#">{{username}}</a></p>
      <p>Rating: 5</p>
      <p>
        Pug four loko austin gochujang vice wayfarers. Wayfarers vegan lomo
        tote bag church-key, master cleanse green juice shabby chic cred. Fashion
        axe marfa kogi lomo literally, gluten-free viral small batch DIY
        food truck single-origin coffee mustache YOLO meggings tofu. Flexitarian
        selfies butcher, pour-over paleo artisan yuccie meditation narwhal
        wayfarers pop-up readymade. Fanny pack put a bird on it salvia
        intelligentsia, etsy tacos stumptown authentic four dollar toast.
        Gluten-free street art meggings pinterest, shoreditch taxidermy craft
        beer VHS kinfolk selfies thundercats polaroid portland. Godard jean
        shorts mumblecore, bespoke drinking vinegar before they sold out polaroid
        tumblr venmo pabst.
      </p>
      <p><a href="#">Read full review ></a></p>
    </div>
    <div class="col-xs-3">
      <h2>Dead or Alive: Xtreme Beach Volleyball</h2>
      <p>Reviewed by <a href="#">{{username}}</a></p>
      <p>Rating: 5</p>
      <p>
        Migas tumblr echo park, fixie photo booth you probably haven't heard of them
        fap quinoa keytar pabst semiotics readymade ramps. Thundercats normcore
        3 wolf moon yuccie chartreuse. Kogi wayfarers williamsburg, kitsch
        gluten-free direct trade forage. Austin distillery +1, chia banjo
        food truck trust fund brunch poutine. Before they sold out yuccie keytar
        sartorial microdosing. Thundercats jean shorts small batch slow-carb,
        tilde tousled chambray YOLO. Photo booth fingerstache vegan meh, twee
        normcore affogato street art pour-over letterpress.
      </p>
      <p><a href="#">Read full review ></a></p>
    </div>
    <div class="col-xs-3">
      <h2>The Idolmaster SP</h2>
      <p>Reviewed by <a href="#">{{username}}</a></p>
      <p>Rating: 2</p>
      <p>
        VHS cred cliche asymmetrical. Man bun offal put a bird on it, austin
        forage deep v chartreuse cold-pressed portland keffiyeh DIY semiotics
        pabst. Kogi pinterest chambray deep v cred echo park, mustache
        green juice chia wolf sartorial. Tilde fingerstache actually franzen,
        leggings normcore +1 readymade kickstarter next level flexitarian tofu
        listicle. Vice disrupt semiotics, gochujang XOXO put a bird on it
        green juice single-origin coffee. Etsy wolf post-ironic, scenester
        pork belly tumblr messenger bag microdosing blog fingerstache authentic
        paleo. Franzen put a bird on it biodiesel tote bag.
      </p>
      <p><a href="#">Read full review ></a></p>
    </div>
    <div class="clearfix"></div>
    <div class="col-xs-3">
      <h2>Super Mario Bros. 3</h2>
      <p>Reviewed by <a href="#">{{username}}</a></p>
      <p>Rating: 5</p>
      <p>
        Aesthetic kickstarter trust fund, fixie man braid 3 wolf moon pork belly
        tofu truffaut locavore tilde. Portland you probably haven't heard of them
        letterpress, polaroid chia street art echo park PBR&B. Artisan etsy cliche,
        slow-carb leggings migas biodiesel tattooed. Wayfarers tattooed church-key
        twee. Yuccie hashtag typewriter, franzen messenger bag wayfarers blog
        biodiesel literally crucifix XOXO narwhal. Gluten-free four loko health
        goth freegan. Fanny pack pop-up ethical brooklyn.
      </p>
      <p><a href="#">Read full review ></a></p>
    </div>
	</div>
</div>
<!-- End content -->

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
