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

$local_user = getLocalUser($content->id, $content->name);

?>

<!DOCTYPE html> 
<html dir="ltr" lang="en-US">
 
<head>
<meta charset="utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" media="all" href="css/main.css" />

<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>

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

	<h3>You are logged in!</h3>

	<p>Donec quis dui eget arcu fringilla accumsan et a ligula. Sed quis lectus ac neque fermentum rutrum sagittis quis dolor. Aenean sagittis sapien non sapien porta vehicula pulvinar augue aliquam. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce id ligula eu purus adipiscing pharetra. Vivamus interdum ornare felis, eu sollicitudin massa scelerisque vitae. Nunc molestie, ipsum ut lacinia tempus, neque lacus interdum enim, quis mattis diam ipsum id metus. Maecenas dignissim erat ac sem gravida malesuada. Donec tempor semper odio a pellentesque.</p>

	<h3>How do I sign up?</h3>

	<p>Sed facilisis blandit ipsum, vitae tempor velit posuere vitae. Fusce vel nulla id neque aliquam elementum ut vel turpis. Aliquam non dui vehicula diam ultricies malesuada a in purus. Suspendisse quis nisl et diam blandit scelerisque. Mauris vehicula malesuada lorem, quis rhoncus justo elementum non. Integer sollicitudin porttitor erat eget congue. Mauris in nulla magna. Mauris at nisi nibh, ut tempus lectus. Maecenas vestibulum convallis nisi, sit amet lobortis tortor fermentum at. Aliquam a orci lacus. Duis accumsan vehicula leo, ut elementum nibh ultricies vitae.</p>

	<pre>
    	<?php print_r($content); ?>
    </pre>

</div>

<!--<div class="sign_in">
	<a href="#" class="twitter_button signedin">Signed in!</a>
</div>-->

<footer>
	<p>Copyright &copy; 2012 &ndash; <a href="#">keeg.me</a></p>
</footer>

</body>
</html>
