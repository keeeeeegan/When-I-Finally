<?php
/**
 * @file
 * User has successfully authenticated with Twitter. Access tokens saved to session and DB.
 */

/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth/twitteroauth.php');
require_once('twitteroauth/config.php');

require_once('./db/getLocalUser.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ./logout.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/* If method is set change API call made. Test is called by default. */
$content = $connection->get('account/verify_credentials');

$local_user = getLocalUser($content->id, $content->name, $content->screen_name);

?>

<!DOCTYPE html> 
<html dir="ltr" lang="en-US">
 
<head>
<meta charset="utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" media="all" href="css/main.css" />

<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="js/wheniiwill.js"></script>

</head>

<body>

<nav>
	<ul>
		<li class="signed_in_as">Signed in as <a href="./profile.php"><?php echo $local_user["nicename"]; ?></a></li>
		<li><a href="./logout.php">Logout</a></li>
	</ul>
</nav>

<hgroup>
	<h1>When I, I Will...</h1>
	<h2>Motivation through consumerism!</h2>
</hgroup>

<div class="description">

	<h3>Add new Motivation</h3>

	<div class="new_motivation">
	When I finally <input type="text" value="" name="motivation_when" /><!--<a class="help" data-helptext="examples go here">?</a>-->, 
	I will buy myself <a href="#" class="item_lookup">lookup item</a>
	</div>

	<a href="#" class="button">Add new Motivation</a>

</div>

<!--<div class="sign_in">
	<a href="#" class="twitter_button signedin">Signed in!</a>
</div>-->

<footer>
	<p>Copyright &copy; 2012 &ndash; <a href="#">keeg.me</a></p>
</footer>

</body>
</html>
