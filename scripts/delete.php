<?php
require_once(getcwd()."/application/DiamondBase.php");
$type = $argv[1] ? strtolower($argv[1]) : null;
$name = $argv[2] ? strtolower($argv[2]) : null;

// print $type ? $type."\n" : "No valid type entered\n";
// print $name ? $name."\n" : "No valid name entered\n";
print $type ? "" : "No valid type entered\n";
print $name ? "" : "No valid name entered\n";


switch($type) {
	case "mvc":
		deleteModel($name);
		deleteController($name);
		deleteView($name);
	break;
	case "model":
		deleteModel($name);
	break;
	case "controller":
		deleteController($name);
	break;
	case "view":
		deleteView($name);
	break;
	default:
		print "Sorry, $type is not a valid type\n";
	break;
}

function getConfirmation($file) {
	$resp = "";
	while($resp != "y" && $resp != "n") {
		print("Delete $file? y/n: ");
		$resp = fgets(STDIN); 
		$resp = trim($resp);
	}
	return $resp;
}

function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
} 

function deleteModel($name) {
	$dir = "application/models/";
	$class = $class = DiamondBase::typeToClass($name,"model");
	$file = $dir.DiamondBase::typeToFile($name,"model");

	$resp = getConfirmation($file);
	if($resp == "y") {
		if(file_exists($file)) {
			unlink($file);
		} else {
			print("Model $file does not exist.\n");
		}
	}
}

function deleteController($name) {
	$dir = "application/controllers/";
	$class = $class = DiamondBase::typeToClass($name,"controller");
	$file = $dir.DiamondBase::typeToFile($name,"controller");

	$resp = getConfirmation($file);
	if($resp == "y") {
		if(file_exists($file)) {
			unlink($file);
			rrmdir("application/views/$name");
		} else {
			print("Controller $file does not exist.\n");
		}
	}
}

function deleteView($controller, $name) {
	
}