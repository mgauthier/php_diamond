<?php
class HomeController extends DiamondBaseController
{	

	public static function indexGET($params=null) {	
		self::render('index');
	}
	
	
}
?>