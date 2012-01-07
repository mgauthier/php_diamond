<?php
class DiamondBaseController extends DiamondBase
{	
	public static function partial($view_dir,$view, $params=null) {
		include(self::$views_dir.'/'.$view_dir.'/_'.$view.'.php');
	}

	public static function render($view, $params=null) {
		$called_class = get_called_class();
		$view_dir = self::classToDir($called_class);
		$layout_view = self::$views_dir.'/'.$view_dir.'/'.$view.'.php';

		$layout_file = $params["layout"] ? $params["layout"].'.php' : null;
		$layout_file = $layout_file ? self::$views_dir.'/layouts/'.$layout_file : null;
		
		if($layout_file)
			require_once($layout_file);
		else
			require_once($layout_view);
	}
}
