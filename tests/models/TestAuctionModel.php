<?php
class TestAuctionModel extends GenericModel
{
	protected static function table()
	{
		return "auctions";
	}
	
	public static function find($userId)
	{
		return parent::find($userId);
	}
	
	public static function find_where($condition)
	{
		return parent::find_where($condition);
	}
}

?>