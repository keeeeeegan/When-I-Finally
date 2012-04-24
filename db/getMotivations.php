<?php

/**
 * @file
 * User has successfully authenticated with Twitter. Access tokens saved to session and DB.
 */

/* Load required lib files. */
session_start();
require_once('../twitteroauth/twitteroauth/twitteroauth.php');
require_once('../twitteroauth/config.php');

require_once('./getLocalUser.php');

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

if (isset($_POST['motivations']) && $_POST['motivations'] == "get")
{
	echo getMotivations($content->id);
}
else {
	header('HTTP/1.1 403 Forbidden');
}

// retrieve the user from the database, or register them if they have not been
// added previously
function getMotivations($twitter_id) {


	$motivations = array();

	$result = mysql_query("SELECT * FROM users_to_motivations WHERE user_id = " . $twitter_id) or die(mysql_error());

	while ($row = mysql_fetch_array($result)) {
		$motivation_result = mysql_query("SELECT * FROM motivations WHERE id = " . $row["motivation_id"]) or die(mysql_error());
		while ($motivation_row = mysql_fetch_array($motivation_result)) {
			$motivations[] = array(
				"motivation_id" => $motivation_row["id"],
				"item_url" => $motivation_row["item_url"],
				"item_img_url" => $motivation_row["item_img_url"],
				"item_id" => $motivation_row["item_id"],
				"item_name" => $motivation_row["item_name"],
				"milestone" => $motivation_row["milestone"],
				"date_added" => $motivation_row["date_added"],
			);
		}
	}

	return json_encode($motivations);

	
}