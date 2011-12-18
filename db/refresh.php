<?php
include_once("autoload.php");
require_once("application/DiamondBase.php");
require_once("db/db.php");

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
					print $class::$table_name."\n";		
				} else {
					print $class::$table_name."*\n";		
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