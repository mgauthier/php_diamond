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
	protected static function public_dir() {
		return self::$_base_dir.self::$_public_dir;
	}
}

