<?php
require_once "DbError.php";

class Db extends mysqli {
	const BOOL_TRUE=1;
	const BOOL_FALSE=0;

	public $connected=false;
	public $last_row_count=null;

	private static $instance=null;

	public function __construct() {
		parent::init();
	}

	public static function getinst() {
		if(self::$instance===null) {
			self::$instance=new self();
		}

		return self::$instance;
	}

	public function connect($host, $user, $pass) {
		$this->connected=parent::real_connect($host, $user, $pass);

		if(!$this->connected) {
			throw new DbError($this->connect_error, $this->connect_errno);
		}
	}

	public function select($db) {
		parent::select_db($db);
	}

	public function query($str) {
		$result=parent::query($str);

		if($this->errno!==0) {
			throw new DbError($this->error." $str", $this->errno);
		}

		$this->last_row_count=is_bool($result)?0:$result->num_rows;

		return $result;
	}

	public function escape($str) {
		return $this->real_escape_string($str);
	}

	public function where_string($arr, $include_where=true) {
		$where_clause=[];

		foreach($arr as $field=>$value) {
			if($value===null) {
				$where_clause[]="`$field` is null";
			}

			else {
				$where_clause[]="`$field`=".$this->db_value($value);
			}
		}

		if(count($where_clause)>0) {
			return ($include_where?" where ":"").implode(" and ", $where_clause);
		}

		return "";
	}

	/*
	php_value - get the value of a field in a result resource, converted to its
	equivalent type for PHP.  this returns the new value, leaving the original
	unmodified.
	*/

	public static function php_value($value, $type) {
		/*
		MySQL NULL values come back as null, so leave them alone.
		*/

		//any types that need converting should be put in here in the form of "php_type"=>[mysql_type, ...]

		$conversion=[
			"float"=>[MYSQLI_TYPE_FLOAT, MYSQLI_TYPE_DOUBLE, MYSQLI_TYPE_DECIMAL, MYSQLI_TYPE_NEWDECIMAL],
			"int"=>[MYSQLI_TYPE_TINY, MYSQLI_TYPE_SHORT, MYSQLI_TYPE_INT24, MYSQLI_TYPE_LONG, MYSQLI_TYPE_LONGLONG],
			"bool"=>[MYSQLI_TYPE_BIT]
		];

		if($value!==null) {
			foreach($conversion as $php_type=>$db_types) {
				foreach($db_types as $db_type) {
					if($type===$db_type) {
						settype($value, $php_type);

						break 2;
					}
				}
			}
		}

		return $value;
	}

	public function db_value($value) {
		/*
		put single quotes around strings
		convert bools to 0 or 1 for going into a BIT field
		convert null to "NULL"
		*/

		if(is_string($value)) {
			return "'".$this->escape_string($value)."'";
		}

		else if(is_bool($value)) {
			return self::db_bool($value);
		}

		else if($value===null) {
			return "NULL";
		}

		return $value;
	}

	public static function php_bool($value) {
		return ($value===self::BOOL_TRUE)?true:false;
	}

	public static function db_bool($value) {
		return $value?self::BOOL_TRUE:self::BOOL_FALSE;
	}

	/*
	cell - value of a single field - gets the value of the first column of
	the first row returned by $q.

	NOTE the return value of this will be ambiguous for BIT fields -
	false could mean either false or no data.  If you need to know which
	one, check last_row_count.
	*/

	public function cell($q) {
		$result=$this->query($q);

		if($result && $result->num_rows>0) {
			list($data)=$result->fetch_row();
			$field=$result->fetch_field();

			return self::php_value($data, $field->type);
		}

		return false;
	}

	/*
	row - assoc array of cells
	*/

	public function row($q) {
		$data=[];
		$result=$this->query($q);

		if($result && $result->num_rows>0) {
			$row=$result->fetch_assoc();

			while($field=$result->fetch_field()) {
				$data[$field->name]=self::php_value($row[$field->name], $field->type);
			}
		}

		if(count($data)>0) {
			return $data;
		}

		return false;
	}

	/*
	col - get a single column as a 1d array
	*/

	public function col($q) {
		$col=[];
		$table=$this->table($q);

		foreach($table as $row) {
			foreach($row as $value) {
				$col[]=$value;

				break;
			}
		}

		if(count($col)>0) {
			return $col;
		}

		return false;
	}

	/*
	table - numeric array of rows
	*/

	public function table($q) {
		$data=[];
		$result=$this->query($q);

		if($result && $result->num_rows>0) {
			while($row=$result->fetch_assoc()) {
				$data_row=[];

				$result->field_seek(0);

				while($field=$result->fetch_field()) {
					$data_row[$field->name]=self::php_value($row[$field->name], $field->type);
				}

				$data[]=$data_row;
			}
		}

		return $data;
	}

	/*
	get a 2d array where the keys are the values from the $group_field field
	and the values are the sections of table where the values for $group_field
	are the same as the key

	TODO multi-level groupings
	*/

	public function grouped_table($q, $group_field) {
		$results=[];

		$table=$this->table($q);

		foreach($table as $row) {
			if(!isset($results[$row[$group_field]])) {
				$results[$row[$group_field]]=[];
			}

			$results[$row[$group_field]][]=$row;
		}

		if(count($results)>0) {
			return $results;
		}

		return false;
	}

	/*
	insert - take an assoc array and insert it as a row

	TODO insert_table - to run "insert into table (col1, col2) values (1, 2), (3, 4)"
	*/

	public function insert($table, $data, $db=null, $update_if_duplicate=false) {
		if(count($data)>0) {
			$fields=[];
			$values=[];

			foreach($data as $field=>$value) {
				$fields[]="`$field`";
				$values[]=$this->db_value($value);
			}

			$table_str="`$table`";

			if($db!==null) {
				$table_str="`$db`.$table_str";
			}

			$update_str="";

			if($update_if_duplicate) {
				$set_pair=[];

				foreach($data as $field=>$value) {
					$set_pair[]="`$field`=".$this->db_value($value);
				}

				$update_str=" on duplicate key update ".implode(", ", $set_pair);
			}

			if(self::query("insert into $table_str (".implode(", ", $fields).") values (".implode(", ", $values).")$update_str")) {
				return $this->insert_id;
			}

			else {
				return false;
			}
		}
	}

	/*
	insert_or_update - if the row exists, update it, otherwise insert it

	NOTE there has to be a unique key on the table for this to work
	*/

	public function insert_or_update($table, $data, $db=null) {
		$this->insert($table, $data, $db, true);
	}

	/*
	update - update a row with the fields in $data.  if $where is specified, it
	should be another associative array of field names and the values to match.
	*/

	public function update($table, $data, $where=null, $db=null) {
		if(count($data)>0) {
			$where_string="";

			if($where!==null) {
				$where_string=$this->where_string($where, true, $use_link);
			}

			$set_pair=array();

			foreach($data as $field=>$value) {
				$set_pair[]="`$field`=".$this->db_value($value);
			}

			$table_str="`$table`";

			if($db!==null) {
				$table_str="`$db`.$table_str";
			}

			return $this->query("update $table_str set ".implode(", ", $set_pair)." $where_string");
		}
	}

	public function remove($table, $where=null, $db=null) {
		$where_string="";

		if($where!==null) {
			$where_string=$this->where_string($where, true);
		}

		$table_str="`$table`";

		if($db!==null) {
			$table_str="`$db`.$table_str";
		}

		return $this->query("delete from $table_str $where_string");
	}
}
?>