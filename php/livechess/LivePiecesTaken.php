<?php
/*
simple class for dealing with the pieces_taken table

TODO could do with indexing it by colour or summat
*/

require_once "php/chess/PiecesTaken.php";
require_once "php/db.php";

class LivePiecesTaken extends PiecesTaken{
	public $gid;
	private $table_name="pieces_taken";

	public function __construct($gid) {
		$this->gid=$gid;
	}

	public function add($piece) {
		$success=false;

		$ins=Db::insert($this->table_name, array(
			"colour"=>colour($piece),
			"type"=>type($piece),
			"piece"=>$piece,
			"gid"=>$this->gid,
			"mtime"=>mtime()
		));

		if($ins) {
			parent::add($piece);
			$success=true;
		}

		return $success;
	}

	public function remove($piece) {
		$success=false;
		$del=Db::query("delete from {$this->table_name} where gid='{$this->gid}' and piece='$piece' limit 1");

		if($del) {
			$success=parent::remove($piece);
		}

		return $success;
	}

	public function db_load() {
		$success=false;
		$this->pieces=array();

		$table=Db::table("select piece from {$this->table_name} where gid='{$this->gid}'");

		if($table!==false) {
			foreach($table as $row) {
				$this->pieces[]=$row["piece"];
			}

			$success=true;
		}

		return $success;
	}

	public function clear() {
		return Db::remove($this->table_name, [
			"gid"=>$this->gid
		]);
	}
}
?>