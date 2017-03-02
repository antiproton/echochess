<?php
require_once "Db.php";

abstract class DbRow {
	public $id;
	public $is_new=true;
	public $is_deleted=false;

	protected $fields=[];
	protected $db;
	protected $row;
	protected $table_name;

	public function __construct() {
		$this->db=Db::getinst();
	}

	public function load($id) {
		$row=$this->db->row("select * from {$this->table_name} where id=$id");

		if($row) {
			$this->load_row($row);
		}
	}

	public function load_row($row) {
		foreach($this->fields as $field) {
			$this->$field=$row[$field];
		}

		$this->is_new=false;
	}

	public function save() {
		$data=[];
		$success=false;

		foreach($this->fields as $field) {
			$data[$field]=$this->$field;
		}

		if($this->is_new) {
			$success=$this->db->insert($this->table_name, $data);

			if($success) {
				$this->id=$this->db->insert_id;
				$this->is_new=false;
			}
		}

		else {
			$success=$this->db->update($this->table_name, $data, [
				"id"=>$this->id
			]);
		}

		return $success;
	}

	public function delete() {
		if(!$this->is_new) {
			if($this->db->remove($this->table_name, ["id"=>$this->id])) {
				$this->is_deleted=true;
			}
		}
	}
}
?>