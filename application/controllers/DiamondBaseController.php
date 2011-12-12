<?php
class DiamondBaseController extends DiamondBase
{	

	public static function stylesheet($filename) {
		return '<LINK href="'.self::public_dir().'/stylesheets/'.$filename.'.css" rel="stylesheet" type="text/css">';
	}

	public static function javascript($filename) {
		return '<script src="'.self::public_dir().'/javascripts/'.$filename.'.js" type="text/javascript"></script>';
	}

	public static function image($filename,$options) {
		$ret = '<img ';
		foreach($options as $key => $value) {
			$ret .= $key.'="'.$value.'" ';
		}
		$ret .= 'src="'.self::public_dir().'/images/'.$filename.'" />';
		return $ret;
	}

	public static function partial($view_dir,$view, $options=null) {
		//print('<br>'.'DiamonBaseController::partial:'.$view.'<br>');
		include(self::$views_dir.'/'.$view_dir.'/_'.$view.'.php');
	}

	public static function render($view, $options=null) {
		//print('<br>'.'DiamonBaseController::render:'.$view.'<br>');
		$called_class = get_called_class();
		$view_dir = self::classToDir($called_class);
		require_once(self::$views_dir.'/'.$view_dir.'/'.$view.'.php');
	}

	private static function classToDir($controller_class) {
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
}
