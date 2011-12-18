<?php
require_once("application/DiamondBase.php");
require_once("db/db.php");

function __autoload($class){
	//print "__autoload:".$class."\n";
	$file = $class.".php";
	$include_dir = "application/models/";
	if(file_exists($include_dir.$file)) {
	    require_once($include_dir.$file);
	} 
}

if(open_db_connection()) {
	$table_list = table_list();
	
	print "Refresh tables...\n";
	$dir = DiamondBase::$models_dir;
	$models = scandir($dir);
	foreach($models as $value) {
		if($value != "." && $value != "..") {
			$class = DiamondBase::fileToClass($value);
			if ($class != DiamondBase::$base_model) {

				if(array_search($class::table(),$table_list)) {
					print $class::table()."\n";		
				} else {
					print $class::table()."*\n";		
				}
				
				
			}
		}
	}
} else {
	print "Cannot connect to db";
}



//Compare tables currently in db with models

//Delete any tables that do not have a corresponding model

//Create tables that do no exist for all models