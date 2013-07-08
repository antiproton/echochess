<?php
/*
what to do with FEN_NONE ("-"):
	ep square: null
	castling availability: CASTLING_NONE
*/

require_once "php/chess/constants.php";
require_once "php/chess/util.php";
require_once "php/chess/Fen.php";
require_once "php/chess/CastlingPrivileges.php";

class Position {
	public $board=array();
	public $kings=array();
	public $active=WHITE;
	public $castling=null;
	public $ep=null;
	public $clock=0;
	public $fullmove=1;

	public function __construct($fen=null) {
		$this->castling=new CastlingPrivileges();

		if($fen===null) {
			$this->set_fen(FEN_INITIAL);
		}

		else {
			$this->set_fen($fen);
		}
	}

	public function set_square($sq, $pc) {
		$this->board[$sq]=$pc;

		if(type($pc)===KING) {
			$this->kings[colour($pc)]=$sq;
		}
	}

	public function set_fen($str) {
		$fen=Fen::fen_to_array($str);

		$this->active=Fen::colour_int($fen[FEN_FIELD_ACTIVE]);
		$this->castling->set_str($fen[FEN_FIELD_CASTLING]);
		$this->ep=($fen[FEN_FIELD_EP]===FEN_NONE)?null:sq($fen[FEN_FIELD_EP]);
		$this->clock=(int) $fen[FEN_FIELD_CLOCK];
		$this->fullmove=(int) $fen[FEN_FIELD_FULLMOVE];

		$board=Fen::pos_to_array($fen[FEN_FIELD_POSITION]);

		foreach($board as $sq=>$piece) {
			$this->set_square($sq, $piece);
		}
	}

	public function get_fen() {
		return Fen::array_to_fen(array(
			FEN_FIELD_POSITION=>Fen::array_to_pos($this->board),
			FEN_FIELD_ACTIVE=>Fen::colour_str($this->active),
			FEN_FIELD_CASTLING=>$this->castling->get_str(),
			FEN_FIELD_EP=>$this->ep===null?FEN_NONE:alg_sq($this->ep),
			FEN_FIELD_CLOCK=>(string) $this->clock,
			FEN_FIELD_FULLMOVE=>(string) $this->fullmove
		));
	}
}
?>