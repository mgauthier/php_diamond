<?php 
class DiamondBase {
	public static $models_dir = "application/models";
	public static $base_model = "DiamondBaseModel";
	
	public static $default_controller = "home";
	public static $default_action = "index";

	protected static $_base_dir = '/php_diamond';
	protected static $_public_dir = '/public';
	protected static $views_dir = 'application/views';


	protected static function link_path($path) {
		return self::$_base_dir.$path;
	}

	protected static function redirect($path) {
		header('Location: '.self::link_path($path));
	}

	protected static function public_dir() {
		return self::$_base_dir.self::$_public_dir;
	}

	public static function stylesheet($filename) {
		return '<LINK href="'.self::public_dir().'/stylesheets/'.$filename.'.css" rel="stylesheet" type="text/css">
';
	}

	public static function javascript($filename) {
		return '<script src="'.self::public_dir().'/javascripts/'.$filename.'.js" type="text/javascript"></script>
';
	}

	public static function image($filename,$options) {
		$ret = '<img ';
		foreach($options as $key => $value) {
			$ret .= $key.'="'.$value.'" ';
		}
		$ret .= 'src="'.self::public_dir().'/images/'.$filename.'" />';
		return $ret;
	}

	public static function link($path,$label,$options=null) {
		return '<a href="'.self::link_path($path).'">'.$label.'</a>';
	}

	public static function view_exists($controller_dir,$action) {
		return file_exists("application/views/$controller_dir/".$action.".php");
	}

	public static function classToDir($controller_class) {
		$s = $controller_class;
		$s_arr = preg_split('/([A-Z])/', $s, -1, PREG_SPLIT_DELIM_CAPTURE  );
		array_pop($s_arr);
		array_pop($s_arr);
		
		return self::toType($s_arr);
	}
	public static function fileToClass($file) {
		return substr($file,0,strpos($file,".php"));
	}
	public static function fileToType($file,$type) {
		$f = ucfirst($type).".php";
		$breakpoint = strpos($file,$f);
		$toconvert = substr($file,0,$breakpoint);
		$s_arr = preg_split('/([A-Z])/', $toconvert, -1, PREG_SPLIT_DELIM_CAPTURE  );

		return self::toType($s_arr);
	}

	private static function toType($s_arr) {
		$ret = '';
		$i=1;
		for ($i; $i<count($s_arr)-2; $i+=2) {
			$ret .= strtolower($s_arr[$i]).$s_arr[$i+1].= '_';
		}
		return $ret.strtolower($s_arr[$i]).$s_arr[$i+1];	
	}

	public static function typeToFile($name, $type) {
		$ret = self::typeToPrefix($name);
		return strtolower($type) != "view" ? $ret.ucfirst($type).".php" : strtolower($ret).".php";
	}

	public static function typeToClass($name, $type) {
		return self::typeToPrefix($name).ucfirst($type);
	}

	private static function typeToPrefix($name) {
		$pieces = explode("_",$name);
		$prefix = "";
		for($i=0; $i<count($pieces); $i++) {
			$prefix .= ucfirst(strtolower($pieces[$i]));
		}

		return $prefix;
	}
}
