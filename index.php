<?php

require_once('application/db/db.php');

function __autoload($class){
//	print "__autoload:".$class."<br/>";
	
	$file = $class.".php";
	$include_dirs = array("application/controllers/", "application/models/", "application/views/", "application/");
	
	foreach($include_dirs as $dir) {
		if(file_exists($dir.$file)) {
		    require_once($dir.$file);
	    	//print("INCLUDE ".$file."!!!!<br/>");
		} 
		// else {
		// 	$dir_files = scandir($dir);
		// 	foreach($dir_files as $dir_file) {
				
		// 		if(!is_dir($dir_file)) {
		// 			print "second level dir file:".$dir.$dir_file."/".$file."<br/>";
		// 			if(file_exists($dir.$dir_file.'/'.$file)) {
		// 				print("INCLUDE ".$file."!!!!<br/>");
		// 				require_once($dir.$dir_file.'/'.$file);
		// 			}
		// 		}		
		// 	}
		// }
	}
}
	
$response = null;
$default_controller = "AuctionController"; 

//********************************************************
//parse the action and controller from the url
//assume controller and action are the first item
//after index.php e.g. <action>_<controller>
//if no '_', then controller defaults to AuctionController
//*******************************************************
$url = $_SERVER['PHP_SELF'];
$arr = parse_url($url);

// print($url.'<br>');
// print_r($arr);
// print('<br>');
// print($_SERVER['HTTP_HOST'].'<br>');
// print($_SERVER['PHP_SELF'].'<br>');
// print($_SERVER['QUERY_STRING'].'<br>');

$path_pieces = explode("/",$arr["path"]);
$key_index = array_search("index.php", $path_pieces);

try {
	//make sure index.php is in the url and that there is only one command between index.php and the query string
	if($key_index === 0 || count($path_pieces) != ($key_index+3)) {
		throw new Exception("invalid path");
	} else {
		$controller_dir = $key_index+1 < count($path_pieces) ? $path_pieces[$key_index+1] : null;
		$action = $key_index+2 < count($path_pieces) ? $path_pieces[$key_index+2] : null;
		
		if(!$controller_dir) {
			$controller = $default_controller;		
		} else {
			$controller = ucfirst($controller_dir)."Controller";
		}

		if(!$action) {
			$view = "index";
		} else {
			$view = ucfirst($action)."View";
		}
		$view = $view;

	 	$action .= $_SERVER['REQUEST_METHOD'];

		//print "Controller:".$controller."<br/> Action:".$action."<br/> View:".$view;	
		if(!class_exists($controller)) {
			throw new Exception("controller does not exist ".$controller);
		} else if(!method_exists($controller, $action)) {
			throw new Exception("action does not exist ".$action);
		} else {
			parse_str($arr["query"], $params);

			if(open_db_connection())
				$response = $controller::$action($params, $view);
			else
				throw new Exception("connection cannot be established");
		}
	}
} catch (Exception $e) {
	$response = array();
	$response["result"] = "error";
	
	$msg = $e->getMessage();
	if(!empty($msg)) {
		$response["error"] = $e->getMessage();
		print $response["error"];
	}
}

?>