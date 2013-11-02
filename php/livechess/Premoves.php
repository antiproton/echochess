<?php
/*
maintains both player's premoves.
*/

require_once "Db.php";
require_once "php/livechess/Premove.php";

class Premoves {
	public $by_index=[];
	public $by_user=[];

	private $game;
	private $db;

	public function __construct($game) {
		$this->db=Db::getinst();
		$this->game=$game;
		$this->by_user[$game->white]=[];
		$this->by_user[$game->black]=[];
	}

	public function db_load() {
		$premoves=self::get_premoves($this->game->gid);

		foreach($premoves as $row) {
			$premove=new Premove($row);
			$this->add_premove($premove);
		}
	}

	/*
	delete_line - delete the supplied premove and any subsequent
	premoves that user has
	*/

	public function delete_line($premove) {
		$this->db->query("
			delete
			from premoves
			where gid='{$this->game->gid}'
			and user='{$premove->user}'
			and move_index>='{$premove->move_index}'
		");
	}

	/*
	delete premoves for moves that have now been made
	*/

	public function delete_old_premoves($current_move_index) {
		$this->db->query("
			delete
			from premoves
			where gid='{$this->game->gid}'
			and move_index<='$current_move_index'
		");
	}

	private function add_premove($premove) {
		$this->by_index[$premove->move_index]=$premove;
		$this->by_user[$premove->user][$premove->move_index]=$premove;
	}

	public static function count($user, $gid) {
		return $this->db->cell("
			select count(*)
			from premoves
			where user='$user'
			and gid='$gid'
		");
	}

	public static function delete($user, $gid, $move_index=null) {
		$where=[
			"gid"=>$gid,
			"user"=>$user
		];

		if($move_index!==null) {
			$where["move_index"]=$move_index;
		}

		$this->db->remove("premoves", $where);
	}

	public static function get_premoves($gid, $user=null) {
		$q="
			select user, fs, ts, move_index, promote_to
			from premoves
			where gid='$gid'
		";

		if($user!==null) {
			$q.=" and user='$user'";
		}

		$q.=" order by move_index asc";

		return $this->db->table($q);
	}

	public function add($user, $fs, $ts, $move_index, $promote_to=null) {
		$row=[
			"user"=>$user,
			"gid"=>$this->game->gid,
			"fs"=>$fs,
			"ts"=>$ts,
			"move_index"=>$move_index,
			"promote_to"=>$promote_to
		];

		$success=$this->db->insert("premoves", $row);

		if($success) {
			$premove=new Premove($row);
			$this->add_premove($premove);
		}

		return $success;
	}

	public function delete_all() {
		$this->db->remove("premoves", [
			"gid"=>$this->game->gid
		]);
	}
}
?>