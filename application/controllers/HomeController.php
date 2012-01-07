<?php
class HomeController extends DiamondBaseController
{	

	public static function index_GET($params=null) {	
		self::render('index', array("layout" => "default"));
	}

	public static function index_POST($params=null) {
		//User::insert(array('id'=>'1'));
		self::redirect('/home');
	}

}
?>