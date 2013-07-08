<?php
/*
Move - object describing a move.
*/

require_once "php/chess/constants.php";
require_once "php/chess/util.php";
require_once "php/chess/MoveLabel.php";

class Move {
	public $valid=false;
	public $legal=false;
	public $success=false;
	public $capture=null;
	public $promote_to=null;
	public $premove=false; //whether the move was made automatically by the server because the user pre-moved it
	public $action=array();
	public $castling=false; //NOTE this won't necessarily be true on castling moves that are just pulled out of the db or whatever
	public $fen;
	public $label;
	public $fs=null;
	public $ts=null;
	public $piece=null; //for bughouse moves
	public $mtime;
	public $is_variation=false;
	public $variation;
	public $previous_move;
	public $previous_variation;
	public $next_move;
	public $next_variation;
	public $previous_item;
	public $next_item;
	public $item_index;
	public $colour;
	public $dot;
	public $display_fullmove=false;
	public $halfmove;
	public $fullmove;
	public $move_index;
	public $gid;

	function __construct() {
		$this->label=new MoveLabel();
	}

	public function get_label() {
		return $this->label->get_label();
	}

	public function reset_pointers() {
		//general

		$this->variation=null;
		$this->previous_move=null;
		$this->previous_variation=null;
		$this->next_move=null;
		$this->next_variation=null;
		$this->previous_item=null;
		$this->next_item=null;
		$this->item_index=null;

		//move-specific

		$this->colour=null;
		$this->dot=FULLMOVE_DOT_WHITE;
		$this->display_fullmove=false;
		$this->halfmove=null;
		$this->fullmove=null;
		$this->move_index=null;
	}
}
?>