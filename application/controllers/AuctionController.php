<?php
class AuctionController extends DiamondBaseController
{	
	public static function show($params) {
		
		self::render('show');
	}

	public static function all($params) {
		
		self::render('all');
	}
	
}
?>