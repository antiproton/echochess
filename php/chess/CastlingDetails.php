<?php
require_once "php/chess/constants.php";

/*
this was originally a util function that returned an array but I didn't like the
hardcoded strings ($castling_details["rook_start_pos"]) and adding constants for
them seemed a bit much.
*/

class CastlingDetails {
	public $rook_start_pos;
	public $rook_end_pos;
	public $side;
	public $sign;
	public $valid=false;

	public static $signs=array(
		KINGSIDE=>SIGN_CASTLE_KS,
		QUEENSIDE=>SIGN_CASTLE_QS
	);

	public function __construct($fs, $ts) {
		$king_start_pos=array(4, 60);

		foreach($king_start_pos as $n) {
			if($fs===$n) {
				$king_end_pos=array(
					KINGSIDE=>$n+2,
					QUEENSIDE=>$n-2
				);

				foreach($king_end_pos as $side=>$o) {
					if($ts===$o) {
						$rook=array(
							KINGSIDE=>array($o+1, $o-1),
							QUEENSIDE=>array($o-2, $o+1)
						);

						$this->side=$side;
						$this->rook_start_pos=$rook[$side][0];
						$this->rook_end_pos=$rook[$side][1];
						$this->sign=self::$signs[$side];
						$this->valid=true;
					}
				}
			}
		}
	}
}
?>