<?php

function connectdb() {

	mysql_connect("localhost", "user", "pw") or die(mysql_error());
	mysql_select_db("db") or die(mysql_error());

}