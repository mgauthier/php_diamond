<?php
include_once("autoload.php");
require_once('db/db.php');
require_once('application/DiamondBase.php');
	
$response = null;
$default_controller = DiamondBase::$default_controller;
$default_action = DiamondBase::$default_action;

//********************************************************
//parse the action and controller from the url
//assume controller and action are structured as controller/action
//*******************************************************
$url = $_SERVER['PHP_SELF'];
$arr = parse_url($url);

// print($_SERVER['HTTP_HOST'].'<br>');
// print($_SERVER['PHP_SELF'].'<br>');
// print($_SERVER['QUERY_STRING'].'<br>');

$path_pieces = explode("/",$arr["path"]);
$key_index = array_search("index.php", $path_pieces);

try {
	//make sure index.php is in the url and that there is at least a controller in the path
	if($key_index === 0 || count($path_pieces) < ($key_index+2)) {
		$controller_dir = $default_controller;
		$_action = $default_action;
	} else {
		$controller_dir = $key_index+1 < count($path_pieces) ? strtolower($path_pieces[$key_index+1]) : $default_controller;
		$_action = $key_index+2 < count($path_pieces) && strlen($path_pieces[$key_index+2]) > 0 ? $path_pieces[$key_index+2] : $default_action;
	}
	
	$action = $_action.$_SERVER['REQUEST_METHOD'];
	$view = DiamondBase::typeToClass($action,"view");
 			
	if(!$controller_dir) {
		$controller = $default_controller;		
	} else {
		$controller = DiamondBase::typeToClass($controller_dir,"controller");
	}

	if(!class_exists($controller)) {
		throw new Exception("controller does not exist ".$controller);
	} else if(!method_exists($controller, $action)) {
		throw new Exception("action does not exist ".DiamondBase::classToDir($controller)."/$action");
	} else if(!DiamondBase::view_exists($controller_dir, $_action)) {
		throw new Exception("view does not exist ".$controller_dir."/".$_action.".php");
	} else {

		if($_SERVER['REQUEST_METHOD'] == "GET")
			parse_str($arr["query"], $params);
		else if($_SERVER['REQUEST_METHOD'] == "POST") {
			$params = array();
			foreach($_POST as $key => $value) {
				$params[$key] = $value;
			}
		}

		if(open_db_connection())
			$controller::$action($params);
		else
			throw new Exception("connection cannot be established");
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
