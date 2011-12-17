<?php
require_once(getcwd()."/application/DiamondBase.php");

if(count($argv) == 3) {
	$type = $argv[1] ? strtolower($argv[1]) : null;
	$name = $argv[2] ? strtolower($argv[2]) : null;
} else if (count($argv) == 4) {
	$type = $argv[1] ? strtolower($argv[1]) : null;
	$controller = $argv[2] ? strtolower($argv[2]) : null;
	$name = $argv[3] ? strtolower($argv[3]) : null;
}

print $type ? "" : "No valid type entered\n";
print $name ? "" : "No valid name entered\n";


switch($type) {
	case "mvc":
		deleteModel($name);
		deleteController($name);
	break;
	case "model":
		deleteModel($name);
	break;
	case "controller":
		deleteController($name);
	break;
	case "view":
		deleteView($controller, $name);
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
	$dir = "application/views/";
	$file = $dir.$controller."/".DiamondBase::typeToFile($name,"view");
	
	$resp = getConfirmation($file);
	if($resp == "y") {
		unlink($file);
	}
}