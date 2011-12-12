<?php
class AuctionController extends DiamondBaseController
{	

	public static function showGET($params) {	
		self::render('show');
	}
	
	public static function allGET($params) {
		
		self::render('all');
	}
	
}
?>