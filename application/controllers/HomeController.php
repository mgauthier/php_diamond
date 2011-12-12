<?php
class HomeController extends DiamondBaseController
{	

	public static function indexGET($params=null) {	
		self::render('index');
	}

	public static function indexPOST($params=null) {
		$name = $params['name'];
		// print $params['name'];
		self::redirect('/auction/show');
	}
}
?>