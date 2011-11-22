<?php
class UserModel extends GenericModel
{	
	protected static function table()
	{
		return "users";
	}
	
	public static function add($userId,$budget)
	{
		$response = array();
		$response["result"] = "error";
		
		$uid = intval(mysql_real_escape_string($userId));
		$b = ((is_numeric($budget) && !strpos($budget, ".")) ? intval(mysql_real_escape_string($budget)) : 0);
		
		if($b <= 0)
			$response["error"] = "invalid budget";
		else if($uid <= 0)			
			$response["error"] = "invalid user id";
		else if(self::exists($uid))
			$response["error"] = "user already exists";		
		else
		{
			if(self::insert(array("id"=>$uid,"budget"=>$b)))
				$response["result"] = "success";
			else
				$response["error"] = "unable to process at this time";
		}
		
		return $response;
	}
	
	//returns integer
	private static function blocked_budget($uid)
	{
		$bb_result = AuctionModel::find_select_where("sum(current_price) as blocked_budget", "active=1 and best_bidder_id=".$uid);
		$bb_row = $bb_result[0];
		return (is_null($bb_row->blocked_budget) ? 0 : intval($bb_row->blocked_budget));
	}
	
	//returns array of int values
	private static function owned_items($uid)
	{
		$owned_items_result = array();
		$owned_items = AuctionModel::find_select_where("item_id", "active=0 and best_bidder_id=".$uid);
		
		foreach($owned_items as $item)
			array_push($owned_items_result,intval($item->item_id));
			
		return $owned_items_result;
	}
	
	public static function snapshot()
	{
		$response = array();
		$response["result"] = "error";
		
		$users = self::find_select("id,budget");
		$response_users = array();
				
		if(!$users)
			$response["error"] = "unable to process at this time";
		else
		{
			foreach($users as $row)
			{
				$user_data = array();
				$user_data["id"] = intval($row->id);
				$user_data["budget"] = intval($row->budget);
				
				//calculate the blocked budget for the user
				$user_data["blockedBudget"] = self::blocked_budget($row->id);
	
				//find owned items for the user
				$user_data["ownedItemIds"] = self::owned_items($row->id);
				
				array_push($response_users, $user_data);
			}
			
			$response["result"] = "success";
			
		}
		
		$response["data"] = $response_users;
		return $response;
	}

}
?>