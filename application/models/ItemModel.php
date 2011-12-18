<?php
class ItemModel extends DiamondBaseModel
{
	public static function table()
	{
		return "items";
	}
	
	public static function add($userId,$itemName,$startPrice)
	{
		$response = array();
		$response["result"] = "error";
		
		$uid = intval(mysql_real_escape_string($userId));
		$item_name = trim(mysql_real_escape_string($itemName));
		$start_price = intval(mysql_real_escape_string($startPrice));
		
		if($uid <= 0)			
			$response["error"] = "invalid user id";
		else if(empty($itemName))
			$response["error"] = "invalid item name";
		else if($start_price <= 0)
			$response["error"] = "invalid start price";
		else if(!UserModel::exists($uid))
			$response["error"] = "user does not exist";
		else if(self::exists_where("name='".$itemName."'"))
			$response["error"] = "item already exists";
		else
		{
				mysql_query("SET autocommit = 0");
				mysql_query("START TRANSACTION");
				
				if(self::insert(array("user_id"=>$uid,"name"=>"'".$item_name."'","start_price"=>$start_price)))
				{
					$insert_id = mysql_insert_id();
					$response["result"] = "success";
					$response["data"] = $insert_id;
				
					mysql_query("COMMIT");
				}
				else
					mysql_query("ROLLBACK");
		}
		
		return $response;
	}
	
}
?>