<?php
require_once "dbcodes/chess.php";
require_once "Db.php";

/*
TODO switch to update_row
*/

class Seat extends DbRow {
	public $user;
	public $table;
	public $type;
	public $gid=null;
	public $game_id=null;
	public $colour=null;
	public $ready=false;

	protected $fields=[
		"user",
		"table",
		"type",
		"gid",
		"game_id",
		"colour",
		"ready"
	];

	protected $table_name="seats";
	private $is_setup=false;

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
		return $this->delete();
	}

	public function save() {
		$success=false;

		if(!$this->is_new || $this->is_setup) {
			$success=parent::save();
		}

		return $success;
	}

	public static function is_seated($user, $gid) {
		return ($this->db->cell("
			select colour
			from seats
			where user='$user'
			and gid='$gid'
			and type='".SEAT_TYPE_PLAYER."'
		")!==false);
	}
}
?>