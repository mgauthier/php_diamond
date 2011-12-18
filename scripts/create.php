<?php
include_once("autoload.php");
require_once("db/db.php");
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
		createModel($name);
		createController($name);
	break;
	case "model":
		createModel($name);
	break;
	case "controller":
		createController($name);
	break;
	case "view":
		createView($controller, $name);
	break;
	case "table":
		createTable($name);
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
		$data = "<?php\nclass $class extends $base_model {\n";
		$data .= "\n\tpublic static ".'$table_name'."=\"$name\"; \n";
		$data .= "\n\tpublic static ".'$table_properties'."= array(); \n";
		$data .= "\n}\n";

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
		createView($name,"index");
	} else {
		print("Controller $file already exists.\n");
	}
}

function createView($controller, $name, $partial=false) {
	$dir = "application/views/";
	$file = $partial ? "_".$name.".php" : $name.".php";

	if(!file_exists($dir.$controller."/".$file)) {
		$fh = fopen($dir.$controller."/".$file, 'w') or die("Can't open file: $controller/$name.php");
		$data = "$controller/$name";
		fwrite($fh, $data);
		fclose($fh);
	} else {
		print("View $controller/$file already exists.\n");
	}
}

function createTable($model_name) {
	$class = DiamondBase::typeToClass($model_name,"model");
	
	if(open_db_connection())
		if($class::create_table()) {
			print "Created table: $model_name\n";
		} else {
			print "Failed to create table: $model_name\n";
		}
	else
		print "Cannot connect to db.\n";
}
