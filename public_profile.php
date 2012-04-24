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
    $local_user = false;
}
else {
	/* Get user access tokens out of the session. */
	$access_token = $_SESSION['access_token'];

	/* Create a TwitterOauth object with consumer/user tokens. */
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

	/* If method is set change API call made. Test is called by default. */
	$content = $connection->get('account/verify_credentials');	
}



if (!isset($local_user) || $local_user != false) {

	$local_user = getLocalUser($content->id, $content->name, $content->screen_name);

}

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

<body class="public_profile"<?php

if (isset($_GET["user"])) {
	echo " id=\"" . $_GET["user"] . "\"";
}

?>>

<nav>
	<ul>
		<?php if ($local_user != false): ?>
			<li class="signed_in_as">Signed in as <a href="./profile.php"><?php echo $local_user["nicename"]; ?></a></li>
			<li><a href="./logout.php">Logout</a></li>
		<?php else: ?>
			<li class="signed_in_as"><a href="./logout.php">Sign in using Twitter!</a></li>
		<?php endif; ?>
	</ul>
</nav>

<hgroup>
	<h1>When I, I Will...</h1>
	<h2>Motivation through consumerism!</h2>
</hgroup>

<div class="description">

	<h3></h3>

	<div class="my_motivations loading">

	</div>
</div>


<footer>
	<p>Copyright &copy; 2012 &ndash; <a href="http://keeg.me">keeg.me</a></p>
</footer>

</body>
</html>
