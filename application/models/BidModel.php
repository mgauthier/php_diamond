<?php
class BidModel extends GenericModel
{
	protected static function table()
	{
		return "bids";
	}

	public static function add($auctionId, $userId, $amount)
	{
		$success = false;
		
		$aid = intval(mysql_real_escape_string($auctionId));
 		$uid = intval(mysql_real_escape_string($userId));
		$amt = intval(mysql_real_escape_string($amount));
		
		//check for basic errors
		if($amt > 0)
		{
			//verify user and auction exist
			if(AuctionModel::exists($aid) && UserModel::exists($uid))
			{
				if(self::insert(array("auction_id"=>$aid,"user_id"=>$uid,"amount"=>$amt,"created_at"=>"NOW()")))
					$success = true;
			}
		}
		
		return $success;
	}
}
?>