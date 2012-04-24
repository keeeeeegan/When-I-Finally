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

if (isset($_POST['item_url']) && isset($_POST['item_img_url']) && isset($_POST['item_id']) && isset($_POST['item_name']))
{
	echo saveItem($_POST['item_url'], $_POST['item_img_url'], $_POST['item_id'], $_POST['item_name'], $_POST['milestone'], $content->id);
}
else {
	header('HTTP/1.1 403 Forbidden');
}

// save new item
function saveItem($item_url, $item_img_url, $item_id, $item_name, $milestone, $twitter_id) {


	$milestone = mysql_real_escape_string($milestone);

	$result = mysql_query("SELECT * FROM motivations") or die(mysql_error());

	$previous_motivations = array();

	$registered = 0;

	while ($row = mysql_fetch_array($result)) {
		$previous_motivations[] = $row["item_id"].$row["milestone"];
	}

	if (!in_array($item_id.$milestone, $previous_motivations)) {
		mysql_query("INSERT INTO motivations (item_url, item_img_url, item_id, item_name, milestone, date_added) VALUES ('" . $item_url . "', '" . $item_img_url . "', '" . $item_id . "', '" . $item_name . "', '" . $milestone . "', '" . time() . "')") or die(mysql_error());
		$insert_id = mysql_insert_id();
		
		mysql_query("INSERT INTO users_to_motivations (user_id, motivation_id) VALUES ('" . $twitter_id . "', '" . $insert_id . "')") or die(mysql_error());

		header('HTTP/1.1 200 OK');
	}
	else {
		header('HTTP/1.1 304 Not Modified');
	}



	
}