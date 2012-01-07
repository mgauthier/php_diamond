<?php
//functions for opening and closing db connection
function open_db_connection()
{
	global $config;
	$db = $config["db"];

	$con = mysql_connect($db["host"],$db["username"],$db["pwd"]);

	if(!$con)
		return false;
	else
		return mysql_select_db($db["name"], $con);
}

function close_db_connection($connection_resource)
{
	return mysql_close($connection_resource);
}

function table_list() {
	global $config;
	$db = $config["db"];

	$list = array();
	$result = mysql_query("show tables from ".$db["name"]) or die (mysql_error());
	while($row = mysql_fetch_row($result)) {
		$list[] = $row[0];
	}
	return $list;
}
