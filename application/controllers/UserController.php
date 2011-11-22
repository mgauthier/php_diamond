<?php
class UserController
{
	public static function add($params)
	{		
		$userId = $params["userId"];
		$budget = $params["budget"];

		$response = UserModel::add($userId, $budget);

		return $response; 
	}
}
?>