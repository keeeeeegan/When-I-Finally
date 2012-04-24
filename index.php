<?php
session_start();

if (isset($_SESSION) && isset($_SESSION["status"]) && $_SESSION["status"] == "verified") {
	// if already logged in, go to confirm page.
	header('Location: ./confirm.php');
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

<body class="splash">

<hgroup>
	<h1>When I, I Will...</h1>
	<h2>Motivation through consumerism!</h2>
</hgroup>

<div class="description">

	<h3>What is this?</h3>

	<p>Everyone has one or two goals in mind. Some just think about the gratification they'll feel at the end to keep them motivated. That's all fine and dandy, but if you're like the most of us, you'd like a little reward when you've finally accomplished something. That's what this site is for, as <em>motivation through consumerism</em> is our motto here. Attach an item you've really been wanting to that goal you've really been needing to accomplish. It might just keep you motivated.</p>

	<h3>How do I sign up?</h3>

	<p>You do not need to provide a username or password for this site. Instead, you can login using Twitter! Just click the button to the right to get started.</p>

</div>

<div class="sign_in">
	<a href="twitteroauth/redirect.php" class="button twitter_button">Sign in using your Twitter account</a>
</div>

<footer>
	<p>Copyright &copy; 2012 &ndash; <a href="http://keeg.me">keeg.me</a></p>
</footer>

</body>
</html>
