<?php

require_once('./connectdb.php');

if (isset($_POST['motivations']) && $_POST['motivations'] == "get" && isset($_POST['twitter_user']))
{
	echo getMotivations($_POST['twitter_user']);
}
else {
	header('HTTP/1.1 403 Forbidden');
}

// retrieve the motivations for the user
function getMotivations($twitter_name) {

	$twitter_name = mysql_real_escape_string($twitter_name);
	$motivations = array();
	
	$result = mysql_query("SELECT * FROM users WHERE twitter_name = '" . $twitter_name . "' LIMIT 1") or die(mysql_error());
	$row = mysql_fetch_array($result);
	if ($row == false) {
		return "404";
	}

	$twitter_id = $row['twitter_id'];
	$user_name = $row['nicename'];

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

	header('Content-Type: application/json');

	$return = array(
		"user_name" => $user_name,
		"motivations" => $motivations,

		);

	return json_encode($return);

	
}