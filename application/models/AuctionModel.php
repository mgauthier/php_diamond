<?php
class AuctionModel extends DiamondBaseModel
{
	public static function table()
	{
		return "auctions";
	}

	public static function add($itemId, $userId, $isActive)
	{
		$response = array();
		$response["result"] = "error";
		
		$iid = intval(mysql_real_escape_string($itemId));
		$uid = intval(mysql_real_escape_string($userId));
		$active = intval(mysql_real_escape_string($isActive));
		
		//check for basic errors
		if($active != 1 && $active != 0)
			$response["error"] = "invalid parameters";
		else
		{
			$item = ItemModel::find($iid);	
			
			//verify user and item are valid and auction doesn't already exist
			if(!UserModel::exists($uid))
				$response["error"] = "user does not exist";
			else if(is_null($item))
				$response["error"] = "item does not exist";
			else if(self::exists_where("item_id=".$iid." and active=1"))
				$response["error"] = "auction already running for this item";
			else
			{
					$start_time = ($active == 1 ? "NOW()" : "NULL");
					
					if(self::insert(array("item_id"=>$iid, "user_id"=>$uid, "current_price"=>$item->start_price, "active"=>$active, "created_at"=>"NOW()", "started_at"=>$start_time)))
					{
						$response["result"] = "success";
					}
					else
						$response["error"] = "cannot process at this time";
						
			}

		}
		
		return $response;
	}
	
	public static function bid($userId, $itemId, $amount)
	{
		$response = array();
		$response["result"] = "error";
		
		$uid = intval(mysql_real_escape_string($userId));
		$iid = intval(mysql_real_escape_string($itemId));
		$amount = intval(mysql_real_escape_string($amount));
		
		mysql_query("SET autocommit = 0");
		mysql_query("START TRANSACTION");
		
		$auction = self::find_where("item_id=".$iid." and active=1 for update");
		$user = UserModel::find_where("id=".$uid." for update");

		//make sure auction and user exists
		if(is_null($user))
			$response["error"] = "invalid user";		
		else if(is_null($auction))
			$response["error"] = "invalid auction";
		else
		{
			$new_budget = $user->budget - $amount;

			if($amount <= $auction->current_price)
				$response["error"] = "invalid amount:";
			else if(!$auction->active)
				$response["error"] = "auction closed";
			else if($new_budget < 0)
				$response["error"] = "insufficient funds";
			else if($user->id == $auction->best_bidder_id)
				$response["error"] = "user already has highest bid";
			else
			{
				if(!is_null($auction->best_bidder_id))
				{
					//give best bidder back his budget
					$best_bidder = UserModel::find_where("id=".$auction->best_bidder_id." for update");
					$best_bidder_new_budget = $best_bidder->budget + $auction->current_price;
					UserModel::update_attributes($auction->best_bidder_id, array("budget"=>$best_bidder_new_budget));
				}
				
				//update auction, update user, create a record of the bid
				if( self::update_attributes($auction->id, array("current_price"=>$amount, "best_bidder_id"=>$uid)) && 		//update auction
				UserModel::update_attributes($uid, array("budget"=>$new_budget)) && 										//update user budget
				BidModel::add($auction->id,$uid,$amount) )																	//create a record of the bid
					$response["result"] = "success";
				else
					$response["error"] = "unable to process at this time";
			}
		}
		
		
		if($response["result"] == "success")
			mysql_query("COMMIT");
		else
			mysql_query("ROLLBACK");

		
		return $response;
	}
	
	public static function finish($itemId)
	{
		$response = array();
		$response["result"] = "error";
		
		$iid = intval(mysql_real_escape_string($itemId));

		mysql_query("SET autocommit = 0");
		mysql_query("START TRANSACTION");

		$auction = self::find_where("item_id=".$iid." and active=1 for update");
		
		if(is_null($auction))
			$response["error"] = "auction does not exist or has already ended";
		else if(!self::update_attributes($auction->id, array("active" => 0, "ended_at" => "NOW()")))
			$response["error"] = "unable to process at this time";
		else
		{				
			$response["result"] = "success";
			
			$best_bidder_id = (!is_null($auction->best_bidder_id) ? $auction->best_bidder_id : '');
			$response["data"] = array("winnerId" => $best_bidder_id, "price" => $auction->current_price);
		}
				
		
		if($response["result"] == "success")
			mysql_query("COMMIT");
		else
			mysql_query("ROLLBACK");
		
		return $response;
	}
	
	public static function snapshot()
	{
		$response = array();
		$response["result"] = "error";
		$response["data"] = array();
		$response_auctions = array();
		
		$auctions = self::find_select("*");

		if(!$auctions)
			$response["error"] = "unable to process at this time";
		else
		{
			foreach($auctions as $row)
			{
				$auction_data = array();
				//formatting for response
				$auction_data["itemId"] = $row->item_id;
				$auction_data["userId"] = $row->user_id;
				$auction_data["currentPrice"] = intval($row->current_price);
				$auction_data["isActive"] = ($row->active == 1 ? true : false);
				$auction_data["bestBidderId"] = intval($row->best_bidder_id);
	
				array_push($response_auctions, $auction_data);			
			}
			
			$response["result"] = "success";
		}	
		$response["data"]["auctions"] = $response_auctions;	
		
		//add user snapshot to response data
		$user_snapshot_response = UserModel::snapshot();
		$response["data"]["users"] = $user_snapshot_response["data"];
		
		if($user_snapshot_response["result"] == "error" && $response["result"] != "error")
			$response["result"] = "error";

		return $response;
	}
	
}

?>