<?php
function __autoload($class){
//	print "__autoload:".$class."<br/>";
	
	$file = $class.".php";
	$include_dirs = array("application/controllers/", "application/models/", "application/views/");
	
	foreach($include_dirs as $dir) {
		if(file_exists($dir.$file)) {
		    require_once($dir.$file);
		}
	}
}