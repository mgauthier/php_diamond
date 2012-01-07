<?php
class HomeController extends DiamondBaseController
{	

	public static function index_GET($params=null) {	
		self::render('index');
	}

	public static function index_POST($params=null) {
		
		//User::insert(array('id'=>'1'));

		self::redirect('/auction/show');
	}

	public static function car_GET($params=null) {	
		self::render('car', array("layout" => "default"));
	}
}
?>