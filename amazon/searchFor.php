<?php

if (isset($_POST['search_term']))
{
	header('Content-Type: application/json');
	
	echo json_encode("hey, you searchin");
}

else {
	header('HTTP/1.1 403 Forbidden');
}