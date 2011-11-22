<?php

//functions for opening and closing db connection
function open_db_connection()
{
	$host = '127.0.0.1';
	$username = 'root';
	$pwd = '';
	$db = 'speeddate';

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
?>