<?php
class Premove {
	public $user;
	public $gid;
	public $fs;
	public $ts;
	public $move_index;
	public $promote_to=null;

	private static $update_row=[
		"user",
		"gid",
		"fs",
		"ts",
		"move_index",
		"promote_to"
	];

	public function __construct($row=null) {
		if($row!==null) {
			foreach(self::$update_row as $field) {
				if(isset($row[$field])) {
					$this->$field=$row[$field];
				}
			}
		}
	}
}
?>