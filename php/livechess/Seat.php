<?php
require_once "dbcodes/chess.php";
require_once "Db.php";

/*
TODO switch to update_row
*/

class Seat {
	public $user;
	public $table;
	public $type;
	public $gid=null;
	public $game_id=null;
	public $colour=null;
	public $ready=false;
	public $is_new=true;
	public $is_deleted=false;
	public $id;

	private $table_name="seats";
	private $is_setup=false;
	private $row=null;

	public function __construct($id=null) {
		if($id!==null) {
			$this->load($id);
		}
	}

	public function setup($user, $table, $type, $colour=null, $game_id=null) {
		$this->user=$user;
		$this->table=$table;
		$this->type=$type;
		$this->game_id=$game_id;
		$this->colour=$colour;

		$this->is_setup=true;
	}

	public function stand() {
		$this->type=SEAT_TYPE_SPECTATOR;

		return true;
	}

	public function leave() {
		$success=false;

		if(!$this->is_new) {
			$success=Db::remove($this->table_name, [
				"id"=>$this->id
			]);

			$this->is_deleted=true;
		}

		return $success;
	}

	public function load($id) {
		$success=false;
		$row=Db::row("select * from seats where id='$id'");

		if($row!==false) {
			$this->load_row($row);
			$success=true;
		}

		return $success;
	}

	public function load_row(&$row) {
		$this->user=$row["user"];
		$this->table=$row["tables"];
		$this->type=$row["type"];
		$this->gid=$row["gid"];
		$this->colour=$row["colour"];
		$this->ready=$row["ready"];
		$this->game_id=$row["game_id"];
		$this->id=$row["id"];

		$this->row=$row;
		$this->is_new=false;
	}

	public function save() {
		$success=false;

		if($this->is_new) {
			if($this->is_setup) {
				$row=[
					"user"=>$this->user,
					"tables"=>$this->table,
					"type"=>$this->type,
					"gid"=>$this->gid,
					"game_id"=>$this->game_id,
					"colour"=>$this->colour,
					"ready"=>$this->ready
				];

				$id=Db::insert($this->table_name, $row);

				if($id!==false) {
					$this->id=$id;
					$this->row=$row;
					$this->is_new=false;
					$success=true;
				}
			}
		}

		else {
			$update=[];

			if($this->user!==$this->row["user"]) {
				$update["user"]=$this->user;
			}

			if($this->table!==$this->row["tables"]) {
				$update["tables"]=$this->table;
			}

			if($this->type!==$this->row["type"]) {
				$update["type"]=$this->type;
			}

			if($this->gid!==$this->row["gid"]) {
				$update["gid"]=$this->gid;
			}

			if($this->game_id!==$this->row["game_id"]) {
				$update["game_id"]=$this->game_id;
			}

			if($this->colour!==$this->row["colour"]) {
				$update["colour"]=$this->colour;
			}

			if($this->ready!==$this->row["ready"]) {
				$update["ready"]=$this->ready;
			}

			$update=Db::update($this->table_name, $update, [
				"id"=>$this->id
			]);

			if($update!==false) {
				$success=true;
			}
		}

		return $success;
	}

	public static function is_seated($user, $gid) {
		return (Db::cell("
			select colour
			from seats
			where user='$user'
			and gid='$gid'
			and type='".SEAT_TYPE_PLAYER."'
		")!==false);
	}
}
?>