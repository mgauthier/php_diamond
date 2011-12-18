<?php
//generic model, aimed at working with single tables
//extend this class and implement the table() method to 
//get access to these methods
abstract class DiamondBaseModel
{

	//return a string of the table name e.g. "users"
	protected abstract static function table();
	
/////////////////////////////////////////
//SELECT FUNCTIONS/////////////////////////////

	//does a record with 'id' exist
	protected static function exists($id)
	{
		$class = get_called_class();
		
		$clean_id = mysql_real_escape_string($id);
		$res = mysql_query("select id from ".$class::table()." where id=".$clean_id);
		
		if(!$res)
			return false;
		else
			return (mysql_num_rows($res) != 0);
	}

	//does a record exist with a given condition
	//WARNING:: ASSUMES VALUES ARE CLEANED BY CALLER	
	protected static function exists_where($condition)
	{
		$class = get_called_class();
		
		$res = mysql_query("select id from ".$class::table()." where ".$condition);

		if(!$res)
			return false;
		else
			return (mysql_num_rows($res) != 0);
	}
	
	//return an array of all objects for a table
	protected static function all()
	{
		$class = get_called_class();
		
		$res = mysql_query("select * from ".$class::table());
		
		if(!$res)
			return null;
		else if(mysql_num_rows($res) > 0) {
			$res_arr = array();
			while($r = mysql_fetch_object($res)) {
				$res_arr[] = $r;
			}
			return $res_arr;
		} else
			return null;	
	}

	//return an object for a record with given 'id'
	protected static function find($id)
	{
		$class = get_called_class();
		
		$clean_id = mysql_real_escape_string($id);		
		$res = mysql_query("select * from ".$class::table()." where id=".$clean_id);
		
		if(!$res)
			return null;
		else if(mysql_num_rows($res) == 1)
			return mysql_fetch_object($res);
		else
			return null;	
	}
	
	//return an object for a record with a given condition
	//WARNING:: ASSUMES VALUES ARE CLEANED BY CALLER	
	protected static function find_where($condition)
	{
		$class = get_called_class();
		
		$res = mysql_query("select * from ".$class::table()." where ".$condition);
		
		if(!$res)
			return null;
		else if(mysql_num_rows($res) > 0)
			return mysql_fetch_object($res);
		else 
			return null;
	}
	
	//return an array of objects
	protected static function find_select($columns)
	{
		$class = get_called_class();
		$table = $class::table();
	
		$result = null;	
		$cols = mysql_real_escape_string($columns);
		
		$res = mysql_query("select ".$cols." from ".$table);
		
		if(!mysql_error())
		{
			$result = array();
			while($row = mysql_fetch_object($res))
				array_push($result,$row);
		}
		
		return $result;
	}
	
	//return an array of objects
	//WARNING:: ASSUMES VALUES ARE CLEANED BY CALLER	
	protected static function find_select_where($columns, $condition)
	{
		$class = get_called_class();
		$table = $class::table();
	
		$result = null;	
		$cols = mysql_real_escape_string($columns);
		
		$res = mysql_query("select ".$cols." from ".$table." where ".$condition);
		
		if(!mysql_error())
		{
			$result = array();
			while($row = mysql_fetch_object($res))
				array_push($result,$row);
		}
		
		return $result;
	}
	
/////////////////////////////////////////
//UPDATE FUNCTIONS/////////////////////////////	

	//parse into a mysql friendly string
	//$attributes is a [column => value,..] array 
	//WARNING:: ASSUMES VALUES ARE CLEANED BY CALLER	
	private static function parse_attributes_for_update($attributes)
	{
		$keys = array_keys($attributes);
		
		$set_attributes = '';
		for($i=0; $i<count($keys); $i++)
		{
			$set_attributes .= mysql_real_escape_string($keys[$i])."=".$attributes[$keys[$i]];
			
			if($i+1 < count($keys))
				$set_attributes .= ",";
		}
		return $set_attributes;
	}
	
	//update attributes for given 'id'
	//$attributes is a [column => value,..] array
	//WARNING:: ASSUMES VALUES ARE CLEANED BY CALLER	
	protected static function update_attributes($id, $attributes)
	{
		$class = get_called_class();
		
		$clean_id = mysql_real_escape_string($id);
		$set_attributes = self::parse_attributes_for_update($attributes);
		
		mysql_query("update ".$class::table()." set ".$set_attributes." where id=".$clean_id);
		
		return !mysql_error();
	}
	
	//update attributes for given condition
	//$attributes is a [column => value,..] array
	//WARNING:: ASSUMES VALUES ARE CLEANED BY CALLER	
	protected static function update_attributes_where($attributes, $condition)
	{
		$class = get_called_class();
		
		$set_attributes = self::parse_attributes_for_update($attributes);
				
		mysql_query("update ".$class::table()." set ".$set_attributes." where ".$condition);		
		
		return !mysql_error();
	}


/////////////////////////////////////////
//INSERT FUNCTIONS/////////////////////////////
	
	private static function comma_reduce($v1,$v2)
	{
		return $v1 . "," . $v2;
	}

	//WARNING:: ASSUMES VALUES ARE CLEANED BY CALLER	
	private static function parse_attributes_for_insert($attributes,$table)
	{
		$keys = array_keys($attributes);
		$values = array_values($attributes);
		
		//create comma separated strings for the columns and associated values
		$mysql_attribute_list = array_reduce(array_slice($keys,1),"self::comma_reduce",$keys[0]);
		$mysql_value_list = array_reduce(array_slice($values,1), "self::comma_reduce",$values[0]);
		
		$result = "(".$mysql_attribute_list.") VALUES (".$mysql_value_list.")";
		
		return $result;
	}

	//WARNING:: ASSUMES VALUES ARE CLEANED BY CALLER
	protected static function insert($attributes)
	{
		$class = get_called_class();
		$table = $class::table();
		$result = false;
		
		$insert_values = self::parse_attributes_for_insert($attributes, $table);
		if($insert_values != "")
		{
			mysql_query("insert into ".$table." ".$insert_values);
			
			if(!mysql_error())
				$result = true;
		}
		
		return $result;

	}

/////////////////////////////////////////
//CREATE TABLE FUNCTION/////////////////////////////
//WARNING:: ASSUMES VALUES ARE CLEANED BY CALLER	
	private static function parse_attributes_for_create($properties)
	{
		result = "";
		for($i=0; $i<count($properties); $i++) {
			
		}
		
		return $result;
	}

	protected static function create_table($properties) {
		$class = get_called_class();
		$table = $class::table();

		$mysql_properties = self::parse_properties_for_create($properties);
		mysql_query("create table if not exists $table ( $mysql_properties );");

		return !mysql_error();
	}
	protected static function delete_table() {
		$class = get_called_class();
		$table = $class::table();
		mysql_query("drop table if exists $table;");

		return !mysql_error();
	}
}	
?>