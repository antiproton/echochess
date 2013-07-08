<?php
/*
fen.static.php - FEN conversion and utility functions.
*/

require_once "php/chess/constants.php";

class Fen {
	static $piece_char=array(
		SQ_EMPTY=>CHAR_SQ_EMPTY,
		WHITE_PAWN=>CHAR_WHITE_PAWN,
		WHITE_KNIGHT=>CHAR_WHITE_KNIGHT,
		WHITE_BISHOP=>CHAR_WHITE_BISHOP,
		WHITE_ROOK=>CHAR_WHITE_ROOK,
		WHITE_QUEEN=>CHAR_WHITE_QUEEN,
		WHITE_KING=>CHAR_WHITE_KING,
		BLACK_PAWN=>CHAR_BLACK_PAWN,
		BLACK_KNIGHT=>CHAR_BLACK_KNIGHT,
		BLACK_BISHOP=>CHAR_BLACK_BISHOP,
		BLACK_ROOK=>CHAR_BLACK_ROOK,
		BLACK_QUEEN=>CHAR_BLACK_QUEEN,
		BLACK_KING=>CHAR_BLACK_KING
	);

	static $piece_int=array(
		CHAR_SQ_EMPTY=>SQ_EMPTY,
		CHAR_WHITE_PAWN=>WHITE_PAWN,
		CHAR_WHITE_KNIGHT=>WHITE_KNIGHT,
		CHAR_WHITE_BISHOP=>WHITE_BISHOP,
		CHAR_WHITE_ROOK=>WHITE_ROOK,
		CHAR_WHITE_QUEEN=>WHITE_QUEEN,
		CHAR_WHITE_KING=>WHITE_KING,
		CHAR_BLACK_PAWN=>BLACK_PAWN,
		CHAR_BLACK_KNIGHT=>BLACK_KNIGHT,
		CHAR_BLACK_BISHOP=>BLACK_BISHOP,
		CHAR_BLACK_ROOK=>BLACK_ROOK,
		CHAR_BLACK_QUEEN=>BLACK_QUEEN,
		CHAR_BLACK_KING=>BLACK_KING
	);

	static $castling_sign=array(
		WHITE=>array(
			KINGSIDE=>FEN_WHITE_CASTLE_KS,
			QUEENSIDE=>FEN_WHITE_CASTLE_QS
		),
		BLACK=>array(
			KINGSIDE=>FEN_BLACK_CASTLE_KS,
			QUEENSIDE=>FEN_BLACK_CASTLE_QS
		)
	);

	public static function piece_char($piece) {
		return self::$piece_char[$piece];
	}

	public static function piece_int($piece) {
		return self::$piece_int[$piece];
	}

	/*
	convert between FEN positions and board arrays

	"Kb6/p7..."
	array(WHITE_KING, BLACK_BISHOP, SQ_EMPTY, ...)
	*/

	public static function pos_to_array($pos) {
		$arr=array();
		$rank=explode(FEN_POS_SEPARATOR, $pos);

		for($r=7; $r>-1; $r--) {
			$file=str_split($rank[$r]);

			$i=0;
			$f=0;

			while($i<8) {
				$sq=$file[$f];

				if(strpos(FEN_PIECES, $sq)!==false) {
					$arr[]=self::piece_int($sq);
					$i++;
				}

				else if(strpos(RANK, $sq)!==false) {
					$n=(int) $sq;

					for($j=0; $j<$n; $j++) {
						$arr[]=SQ_EMPTY;
						$i++;
					}
				}

				else {
					$i++;
				}

				$f++;
			}
		}

		return $arr;
	}

	public static function array_to_pos($arr) {
		$pos=array();
		$rank=array();

		for($i=56; $i>-1; $i-=8) {
			$rank[]=array_slice($arr, $i, 8);
		}

		foreach($rank as $r) {
			$n=0;
			$fen_rank="";

			for($i=0; $i<count($r); $i++) {
				$piece=$r[$i];

				$next=null;

				if($i<7) {
					$next=$r[$i+1];
				}

				if($piece===SQ_EMPTY) {
					$n++;

					if($next!==SQ_EMPTY) {
						$fen_rank.=$n;
						$n=0;
					}
				}

				else {
					$fen_rank.=self::piece_char($piece);
				}
			}

			$pos[]=$fen_rank;
		}

		return implode(FEN_POS_SEPARATOR, $pos);
	}

	/*
	split/join FEN fields
	*/

	public static function fen_to_array($str) {
		return preg_split("/\s+/", trim($str));
	}

	public static function array_to_fen($arr) {
		return implode(FEN_SEPARATOR, $arr);
	}

	/*
	convert between FEN castling fields and integers (1111TWO<->"KQkq")
	*/

	public static function castling_int($str) {
		if($str===FEN_NONE) {
			return CASTLING_NONE;
		}

		$castling=CASTLING_NONE;

		$colour=array(WHITE, BLACK);
		$side=array(KINGSIDE, QUEENSIDE);

		foreach($colour as $c) {
			foreach($side as $s) {
				$char=self::$castling_sign[$c][$s];
				$n=(strpos($str, $char)!==false)?1:0;
				$castling|=$n<<(3-(($c*2)+$s));
			}
		}

		return $castling;
	}

	public static function castling_str($n) {
		if($n===CASTLING_NONE) {
			return FEN_NONE;
		}

		$castling="";

		$colour=array(WHITE, BLACK);
		$side=array(KINGSIDE, QUEENSIDE);

		foreach($colour as $c) {
			foreach($side as $s) {
				$char=self::$castling_sign[$c][$s];
				$available=($n>>(3-(($c*2)+$s)))&1;

				if($available===1) {
					$castling.=$char;
				}
			}
		}

		return $castling;
	}

	/*
	convert between FEN colours (w/b) and integers (WHITE/BLACK)
	*/

	public static function colour_str($colour) { //returns "w" or "b"
		return $colour===BLACK?FEN_ACTIVE_BLACK:FEN_ACTIVE_WHITE;
	}

	public static function colour_int($str) {
		return $str===FEN_ACTIVE_BLACK?BLACK:WHITE;
	}

	public static function is_valid($fen) { //TODO test this
		if(is_string($fen)) {
			$fen=trim($fen);

			$fields=preg_split("/\s+/", $fen);

			if(count($fields)>=5) { //would be 6 but fullmove seems to be considered optional
				//position

				$pos=str_split($fields[FEN_FIELD_POSITION]);
				$ranks=0;
				$files=0;
				$i=0; //this will match the length of the position string at the end if no invalid chars are found

				foreach($pos as $ch) {
					if(preg_match("/\d/", $ch)) {
						$files+=(int) $ch;
					}

					else if(preg_match("/[pnbrqkPNBRQK]/", $ch)) {
						$files++;
					}

					else if($ch==="/") {
						if($files===8) {
							$ranks++;
							$files=0;
						}

						else { //encountered / before all squares given for current rank
							break;
						}
					}

					else {
						break;
					}

					$i++;
				}

				if($files===8) {
					$ranks++;
				}

				//NOTE gone for the old nested ifs here for some reason, should probably change it to one big one

				if($files===8 && $ranks===8 && count($pos)===$i) { //8 valid ranks and no stuff on the end; position is valid.  files===8 checks that it doesn't end in /
					if($fields[FEN_FIELD_ACTIVE]===FEN_ACTIVE_WHITE || $fields[FEN_FIELD_ACTIVE]===FEN_ACTIVE_BLACK) {
						if($fields[FEN_FIELD_CASTLING]===FEN_NONE || preg_match('/^[a-hA-HkqKQ]{1,4}$/', $fields[FEN_FIELD_CASTLING])) {
							if($fields[FEN_FIELD_EP]===FEN_NONE || preg_match('/^[a-h][36]$/', $fields[FEN_FIELD_EP])) {
								if(preg_match('/^\d+$/', $fields[FEN_FIELD_CLOCK])) {
									if(!isset($fields[FEN_FIELD_FULLMOVE]) || preg_match('/^\d+$/', $fields[FEN_FIELD_FULLMOVE])) {
										return true;
									}
								}
							}
						}
					}
				}
			}
		}

		return false;
	}
}
?>