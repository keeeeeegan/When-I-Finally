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

if (isset($_POST['motivation']) && $_POST['motivation'] == "delete")
{
	deleteMotivation($_POST['motivation_id']);
}
else {
	header('HTTP/1.1 403 Forbidden');
}

// delete motivation
function deleteMotivation($motivation_id) {

	$motivations = array();

	mysql_query("DELETE FROM users_to_motivations WHERE motivation_id = " . $motivation_id) or die(mysql_error());
	mysql_query("DELETE FROM motivations WHERE id = " . $motivation_id) or die(mysql_error());

	header('HTTP/1.1 200 OK');
	
}