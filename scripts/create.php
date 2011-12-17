<?php
$type = $argv[1] ? strtolower($argv[1]) : null;
$name = $argv[2] ? strtolower($argv[2]) : null;

print $type ? $type."\n" : "No valid type entered\n";
print $name ? $name."\n" : "No valid name entered\n";

switch($type) {
	case "all":
		createModel($name);
		createController($name);
		createView($name);
	break;
	case "model":
		createModel($name);
	break;
	case "controller":
		createController($name);
	break;
	case "view":
		createView($name);
	break;
	default:
		print "Sorry, $type is not a valid type\n";
	break;
}

function createModel($name) {
	$base_model = "DiamondBaseModel";
	$dir = "application/models/";
	$class = ucfirst($name)."Model";
	$file = $dir.$class.".php";

	if(!file_exists($file)) {
		$fh = fopen($file, 'w') or die("Can't open file: $fname");
		$data = "<?php\nclass $class";
		$data .= " extends $base_model";
		$data .= " {\n\n\tprotected static function table() { \n\t\treturn \"$name\"; \n\t}\n\n}\n";

		fwrite($fh, $data);
		fclose($fh);
	} else {
		print("Model $file already exists.\n");
	}
}

function createController($name) {
	$base_controller = "DiamondBaseController";
	$dir = "application/controllers/";
	$class = ucfirst($name)."Controller";
	$file = $dir.$class.".php";

	if(!file_exists($file)) {
		$fh = fopen($file, 'w') or die("Can't open file: $fname");
		$data = "<?php\nclass $class";
		$data .= " extends $base_controller";
		$data .= " {\n \n \n}\n";

		fwrite($fh, $data);
		fclose($fh);

		mkdir("application/views/$name");
	} else {
		print("Controller $file already exists.\n");
	}
}

function createView($controller, $name) {
	
}