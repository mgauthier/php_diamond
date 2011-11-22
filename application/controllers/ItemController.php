<?php
class ItemController
{
	public static function add($params)
	{		
		$userId = $params["userId"];
		$itemName = $params["itemName"];
		$startPrice = $params["startPrice"];
		
		$response = ItemModel::add($userId,$itemName,$startPrice);
		
		return $response; 
	}
}
?>