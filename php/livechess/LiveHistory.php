<?php
/*
this is a live game history, so it isn't full featured like the js version

there are no variations and no way to select other moves (moves always added on at end)
*/

/*
NOTE the game might not have a gid - if it doesn't throw an error (is what i should do)
*/

require_once "Db.php";
require_once "php/chess/History.php";

class LiveHistory extends History {
	private $table_name="moves";

	public function db_load() {
		$db=Db::getinst();
		$success=false;
		$temp=$this->main_line->auto_update_pointers;
		$this->main_line->auto_update_pointers=false; //for performance
		$this->main_line->clear(); //delete all moves

		$table=$db->table("select * from {$this->table_name} where gid='{$this->game->gid}' order by move_index");

		if($table!==false) {
			foreach($table as $row) {
				$move=new Move();

				$move->valid=true;
				$move->legal=true;
				$move->label->piece=$row["label_piece"];
				$move->label->disambiguation=$row["label_disambiguation"];
				$move->label->sign=$row["label_sign"];
				$move->label->to=$row["label_to"];
				$move->label->special=$row["label_special"];
				$move->label->check=$row["label_check"];
				$move->label->notes=$row["label_notes"];
				$move->colour=$row["colour"];
				$move->fs=$row["fs"];
				$move->ts=$row["ts"];
				$move->piece=$row["piece"];
				$move->fen=$row["fen"];
				$move->mtime=$row["mtime"];
				$move->capture=$row["capture"];
				$move->promote_to=$row["promote_to"];
				$move->premove=$row["premove"];
				$move->gid=$this->game->gid;

				$this->main_line->add($move);
			}

			$this->main_line->update_pointers();
			$this->main_line->auto_update_pointers=$temp;
			$success=true;
		}

		return $success;
	}

	public function move($move) {
		$db=Db::getinst();
		$success=false;
		$move->gid=$this->game->gid;

		parent::move($move);

		$ins=$db->insert($this->table_name, [
			"move_index"=>$move->move_index,
			"label_piece"=>$move->label->piece,
			"label_disambiguation"=>$move->label->disambiguation,
			"label_sign"=>$move->label->sign,
			"label_to"=>$move->label->to,
			"label_special"=>$move->label->special,
			"label_check"=>$move->label->check,
			"label_notes"=>$move->label->notes,
			"colour"=>$move->colour,
			"fs"=>$move->fs,
			"ts"=>$move->ts,
			"piece"=>$move->piece,
			"mtime"=>$move->mtime,
			"fen"=>$move->fen,
			"capture"=>$move->capture,
			"promote_to"=>$move->promote_to,
			"premove"=>$move->premove,
			"gid"=>$move->gid
		]);

		if($ins!==false) {
			$success=true;
		}

		else {
			parent::undo();
		}

		return $success;
	}

	public function undo() {
		$db=Db::getinst();
		$success=false;
		$move=$this->main_line->last_move;

		parent::undo();

		$del=$db->remove($this->table_name, [
			"move_index"=>$move->move_index,
			"gid"=>$move->gid
		]);

		if($del!==false) {
			$success=true;
		}

		else {
			$this->main_line->add($move);
		}

		return $success;
	}
}
?>