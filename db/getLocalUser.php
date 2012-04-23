<?php

require_once('connectdb.php');

// retrieve the user from the database, or register them if they have not been
// added previously
function getLocalUser($twitter_id, $nicename) {

	$result = mysql_query("SELECT * FROM users") or die(mysql_error());

	$twitter_ids = array();

	$registered = 0;

	while ($row = mysql_fetch_array($result)) {
		$twitter_ids[] = $row["twitter_id"];
	}

	if (!in_array($twitter_id, $twitter_ids)) {
		mysql_query("INSERT INTO users (twitter_id, nicename, registered, last_accessed) VALUES ('" . $twitter_id . "', '" . $nicename . "', '" . time() . "', '" . time() . "')") or die(mysql_error());
		$registered = time();
	}
	else {
		mysql_query("UPDATE users SET last_accessed = " . time() . " WHERE twitter_id = '" . $twitter_id . "'") or die(mysql_error());
		$result = mysql_query("SELECT * from users WHERE twitter_id = '" . $twitter_id . "'") or die(mysql_error());
		$row = mysql_fetch_array($result);
		$registered = $row["registered"];
	}

	return array(
		"nicename" => $nicename,
		"registered" => date("F j, Y, g:i a", $registered),
		"last-login" => date("F j, Y, g:i a", time()),
	);
}
