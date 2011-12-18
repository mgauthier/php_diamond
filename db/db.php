<?php
$host = '127.0.0.1';
$username = 'root';
$pwd = '';
$db = 'diamond';

//functions for opening and closing db connection
function open_db_connection()
{
	global $host,$username,$pwd,$db;

	$con = mysql_connect($host,$username,$pwd);
	
	if(!$con)
		return false;
	else
		return mysql_select_db($db, $con);
}

function close_db_connection($connection_resource)
{
	return mysql_close($connection_resource);
}

function table_list() {
	global $db;
	$list = array();
	$result = mysql_query("show tables from $db") or die (mysql_error());
	while($row = mysql_fetch_row($result)) {
		$list[] = $row[0];
	}
	return $list;
}
