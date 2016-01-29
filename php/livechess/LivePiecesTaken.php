<?php
/*
simple class for dealing with the pieces_taken table

TODO could do with indexing it by colour or summat

FIXME all these have too many Db::getinst calls - they need to extend DbRow,
but then they need to extend their own stuff as well - what to do...
*/

require_once "php/chess/PiecesTaken.php";
require_once "Db.php";

class LivePiecesTaken extends PiecesTaken{
	public $gid;
	private $table_name="pieces_taken";

	public function __construct($gid) {
		$this->gid=$gid;
	}

	public function add($piece) {
		$db=Db::getinst();
		$success=false;

		$ins=$db->insert($this->table_name, array(
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
		$db=Db::getinst();
		$success=false;
		$del=$db->query("delete from {$this->table_name} where gid='{$this->gid}' and piece='$piece' limit 1");

		if($del) {
			$success=parent::remove($piece);
		}

		return $success;
	}

	public function db_load() {
		$db=Db::getinst();
		$success=false;
		$this->pieces=array();

		$table=$db->table("select piece from {$this->table_name} where gid='{$this->gid}'");

		if($table!==false) {
			foreach($table as $row) {
				$this->pieces[]=$row["piece"];
			}

			$success=true;
		}

		return $success;
	}

	public function clear() {
		$db=Db::getinst();
		return $db->remove($this->table_name, [
			"gid"=>$this->gid
		]);
	}
}
?>