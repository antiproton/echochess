<?php
class PgnLine {
	public $line=array();
	public $first_comment=array();
	public $last_move=null;

	public function add_move($move) {
		/*
		make sure first comment (straight after start of line or straight
		after variation) gets picked up by the next move.
		then make sure first_comment is empty for the next move after that
		*/

		$move->comment_before=$this->first_comment;
		$this->first_comment=array();
		$this->line[]=$move;
		$this->last_move=$move;
	}

	public function add_comment($str) {
		if($this->last_move===null) {
			$this->first_comment[]=$str;
		}

		else {
			$this->last_move->comment_after[]=$str;
		}
	}

	public function add_variation($var) {
		$this->line[]=$var;

		/*
		so that comments after a bracketed variation don't get added to
		the move before the variation (to which last_move still points),
		but get put in first_comment to be picked up by the next move
		after the variation:
		*/

		$this->last_move=null;
	}
}
?>