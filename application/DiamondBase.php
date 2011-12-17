<?php 
class DiamondBase {

	public static $default_controller = "HomeController";
	public static $default_action = "indexGET";

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

	protected static function classToDir($controller_class) {
		$s = $controller_class;
		$s_arr = preg_split('/([A-Z])/', $s, -1, PREG_SPLIT_DELIM_CAPTURE  );
		array_pop($s_arr);
		array_pop($s_arr);

		$dir = '';
		$i=1;
		for ($i; $i<count($s_arr)-2; $i+=2) {
			$dir .= strtolower($s_arr[$i]).$s_arr[$i+1].= '_';
		}
		$dir .= strtolower($s_arr[$i]).$s_arr[$i+1];
	
		return $dir;
	}

	public static function fileToType($file,$type) {
		$f = ucfirst($type).".php";
		$breakpoint = strpos($file,$f);
		$toconvert = substr($file,0,$breakpoint);

		$s_arr = preg_split('/([A-Z])/', $toconvert, -1, PREG_SPLIT_DELIM_CAPTURE  );
		
		$dir = '';
		$i=1;
		for ($i; $i<count($s_arr)-2; $i+=2) {
			$dir .= strtolower($s_arr[$i]).$s_arr[$i+1].= '_';
		}
		$dir .= strtolower($s_arr[$i]).$s_arr[$i+1];
	
		return $dir;
	}
}

