<?php
require_once "Db.php";

/*
basic class for building up a query string
*/

class Query {
	public $str;

	public function __construct($str="") {
		$this->str=$str;
	}

	/*
	if $value is defined, add a condition to the end of the query string with
	the specified field name and operator
	*/

	public function add_cond(&$value, $field, $operator="=") {
		$db=Db::getinst();

		if(isset($value)) {
			$this->str.=" and $field $operator ".$db->db_value($value);
		}
	}

	/*
	if $field is in $array, add a condition to the end of the query string
	using the array key as the field name
	*/

	public function add_cond_arr($array, $field, $operator="=") {
		$db=Db::getinst();

		if(array_key_exists($field, $array)) {
			$this->str.=" and $field $operator ".$db->db_value($array[$field]);
		}
	}
}
?>