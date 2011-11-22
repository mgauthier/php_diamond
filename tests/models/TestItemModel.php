<?php
class TestItemModel extends GenericModel
{
	protected static function table()
	{
		return "items";
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