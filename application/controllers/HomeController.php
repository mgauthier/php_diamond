<?php
class HomeController extends DiamondBaseController
{	

	public static function indexGET($params=null) {	
		self::render('index');
	}

	public static function indexPOST($params=null) {
		
		//User::insert(array('id'=>'1'));

		self::redirect('/auction/show');
	}

	public static function carGET($params=null) {	
		self::render('car');
	}
}
?>