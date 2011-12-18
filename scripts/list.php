<?php
require_once(getcwd()."/application/DiamondBase.php");

$type = $argv[1] ? strtolower($argv[1]) : null;
print $type ? "" : "No valid type entered\n";

switch($type) {
	case "mvc":
		print "Models:\n";
		_list("model");
		print "\nControllers:\n";
		_list("controller");
		print "\nViews:\n";
		_list("view");
	break;
	case "models":
	case "controllers":
	case "views":
		print ucfirst($type).":\n";
		_list(substr($type,0,count($type)-2));
	break;
	default:
		print "Sorry, $type is not a valid type\n";
	break;
}

function _list($type) {
	$list = scandir("application/".$type."s");

	if($type != "view") {
		foreach($list as $value) { 
			print $value != "." && $value != ".." ? DiamondBase::fileToType($value,$type)."\n" : ""; 
		}
	} else {}
}