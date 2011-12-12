<?php
class DiamondBaseController extends DiamondBase
{	
	public static function partial($view_dir,$view, $options=null) {
		include(self::$views_dir.'/'.$view_dir.'/_'.$view.'.php');
	}

	public static function render($view, $options=null) {
		$called_class = get_called_class();
		$view_dir = self::classToDir($called_class);
		$layout_view = self::$views_dir.'/'.$view_dir.'/'.$view.'.php';

		$layout_file = $options["layout"] ? $options["layout"].'.php' : 'default.php';
		$layout_file = self::$views_dir.'/layouts/'.$layout_file;
		
		require_once($layout_file);
	}
}
