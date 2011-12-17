<?php
require_once(getcwd()."/application/DiamondBase.php");

$type = $argv[1] ? strtolower($argv[1]) : null;
$name = $argv[2] ? strtolower($argv[2]) : null;

print $type ? "" : "No valid type entered\n";
print $name ? "" : "No valid name entered\n";

switch($type) {
	case "mvc":
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
	$class = DiamondBase::typeToClass($name,"model");
	$file = $dir.DiamondBase::typeToFile($name,"model");

	if(!file_exists($file)) {
		$fh = fopen($file, 'w') or die("Can't open file: $file");
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
	$class = DiamondBase::typeToClass($name,"controller");
	$file = $dir.DiamondBase::typeToFile($name,"controller");

	if(!file_exists($file)) {
		//create controller class with default index method
		$fh = fopen($file, 'w') or die("Can't open file: $file");
		$data = "<?php\nclass $class";
		$data .= " extends $base_controller";
		$data .= " {\n\n";
		$data .= "\tpublic static function indexGET(".'$params'."=null) {\n\t\tself::render('index');\n\t}\n";
		$data .= "\n}\n";
		fwrite($fh, $data);
		fclose($fh);

		//create views dir
		mkdir("application/views/$name");

		//create a default index page
		$fh = fopen("application/views/$name/index.php", 'w') or die("Can't open file: $name/index.php");
		$data = "$class default page";
		fwrite($fh, $data);
		fclose($fh);
	} else {
		print("Controller $file already exists.\n");
	}
}

function createView($controller, $name) {
	
}