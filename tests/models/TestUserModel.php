<?php
class TestUserModel extends GenericModel
{	
	protected static function table()
	{
		return "users";
	}
	
	public static function find($userId)
	{
		return parent::find($userId);
	}
}
?>