<?php
class AuctionController extends DiamondBaseController
{	

	public static function showGET($params=null) {	
		self::render('show');
	}
	
	public static function allGET($params=null) {
		self::render('all');
	}
	
}
?>