<?php
require_once "php/chess/constants.php";

//Squares
///////////////////////////////////////////////////////////////////////////////

function on_board($sq) {
	return ($sq>-1 && $sq<64);
}

function rel_sq_no($sq, $colour) {
	return ($colour===BLACK?63-$sq:$sq);
}

function opp_colour($colour) {
	return (int) !$colour;
}

function opp_result($result) {
	$opp=[
		RESULT_DRAW=>RESULT_DRAW,
		RESULT_WHITE=>RESULT_BLACK,
		RESULT_BLACK=>RESULT_WHITE
	];

	return $opp[$result];
}

function fullmove_index($halfmove) {
	return floor($halfmove/2);
}

function fullmove($halfmove) {
	return fullmove_index($halfmove)+1;
}

function fullmove_dot($colour) {
	$dot=array(
		WHITE=>FULLMOVE_DOT_WHITE,
		BLACK=>FULLMOVE_DOT_BLACK
	);

	return $dot[$colour];
}

function score($result, $colour) { //NOTE careful handling results - they are floats so if WHITE is put in it won't be identical to WHITE when it comes back out
	if($result===RESULT_DRAW) {
		return SCORE_DRAW;
	}

	if($colour===WHITE) {
		return $result===RESULT_WHITE?SCORE_WIN:SCORE_LOSS;
	}

	if($colour===BLACK) {
		return $result===RESULT_BLACK?SCORE_WIN:SCORE_LOSS;
	}
}

function result_win($colour) {
	$results=array(
		WHITE=>RESULT_WHITE,
		BLACK=>RESULT_BLACK
	);

	return $results[$colour];
}

function hm_colour($halfmove) {
	return ($halfmove%2===1)?BLACK:WHITE;
}

function x($sq) {
	return ($sq%8);
}

function y($sq) {
	return (($sq-x($sq))/8);
}

function file_str($sq) {
	return substr(FILE, ($sq%8), 1);
}

function rank_str($sq) {
	return substr(RANK, (($sq-($sq%8))/8), 1);
}

function sq($sq) { //takes alg sq
	list($f, $r)=str_split($sq);
	return coords_to_sq(array(strpos(FILE, $f), strpos(RANK, $r)));
}

function alg_sq($sq) {
	return file_str($sq).rank_str($sq);
}

function sq_to_coords($sq) {
	$x=$sq%8;
	$y=($sq-$x)/8;
	return array($x, $y);
}

function coords_to_sq($coords) {
	return ($coords[Y]*8)+$coords[X];
}

function diff($a, $b) {
	return abs($a-$b);
}

function same_file($a, $b) { //abs sq nos
	return file_str($a)===file_str($b);
}

function same_rank($a, $b) { //abs sq nos
	return rank_str($a)===rank_str($b);
}

function sq_colour($n) {
	return (int) !((($n%2)+(floor($n/8)%2))%2);
}

function colour_name($colour) {
	$colours=array(
		WHITE=>"white",
		BLACK=>"black",
		"w"=>"white",
		"b"=>"black",
		"white"=>"white",
		"black"=>"black"
	);

	return $colours[$colour];
}

function colour_int($colour) {
	$colours=array(
		"white"=>WHITE,
		"black"=>BLACK,
		"w"=>WHITE,
		"b"=>BLACK,
		WHITE=>WHITE,
		BLACK=>BLACK
	);

	return $colours[$colour];
}

function colour_fen($colour) {
	$colours=array(
		WHITE=>"w",
		BLACK=>"b",
		"white"=>"w",
		"black"=>"b",
		"w"=>"w",
		"b"=>"b"
	);

	return $colours[$colour];
}

//Basic piece movements
///////////////////////////////////////////////////////////////////////////////

/*
Note: regular_move and pawn_move_ functions don't check whether the from or to
squares are actually on the board
*/

/*
regular_move - checks the geometry of "normal" moves (no castling or pawns)
*/

function regular_move($type, $fc, $tc) {
	$d=array();
	$coord=array(X, Y);

	foreach($coord as $axis) {
		$d[$axis]=diff($fc[$axis], $tc[$axis]);
	}

	if($d[X]===0 && $d[Y]===0) {
		return false;
	}

	switch($type) {
		case KNIGHT: {
			return (($d[X]===2 && $d[Y]===1) || ($d[X]===1 && $d[Y]===2));
		}

		case BISHOP: {
			return ($d[X]===$d[Y]);
		}

		case ROOK: {
			return ($d[X]===0 || $d[Y]===0);
		}

		case QUEEN: {
			return (regular_move(ROOK, $fc, $tc) || regular_move(BISHOP, $fc, $tc));
		}

		case KING: {
			return (($d[X]===1 || $d[X]===0) && ($d[Y]===1 || $d[Y]===0));
		}
	}

	return false;
}

/*
special moves

ep is handled in game.class.php, which has access to the ep square
*/

//pawn move checks take rel sq no because they have an idea of "forwards".

function pawn_move($fs, $ts) {
	return ($ts-$fs===8);
}

function pawn_move_double($fs, $ts) {
	return ($fs>7 && $fs<16 && $ts-$fs===16);
}

function pawn_move_capture($fs, $ts) {
	list($fx, $fy)=sq_to_coords($fs);
	list($tx, $ty)=sq_to_coords($ts);

	return ($ty-$fy===1 && diff($tx, $fx)===1);
}

function pawn_move_promote($ts) {
	return ($ts>55);
}

//ep_pawn - the pawn is on the same rank as $fs and same file as $ts
//this take abs sq

function ep_pawn($fs, $ts) {
	return coords_to_sq(array(x($ts), y($fs)));
}

//Lines etc
///////////////////////////////////////////////////////////////////////////////

function distance_diagonal($fc, $tc) {
	return diff($fc[X], $tc[X]);
}

/*
squares_between - return an array of the squares between $fs and $ts
this returns an empty array for adjacent squares and non-linear moves
*/

function squares_between($fs, $ts, $inclusive=false) {
	$arr=array();

	//go from lower to higher sq so same loop can be used in either dir

	$temp=$fs;
	$fs=min($fs, $ts);
	$ts=max($temp, $ts);

	list($fc, $tc)=array(sq_to_coords($fs), sq_to_coords($ts));

	$difference=diff($fs, $ts);

	if($inclusive) {
		$arr[]=$fs;
	}

	if(regular_move(BISHOP, $fc, $tc)) {
		$distance=distance_diagonal($fc, $tc);

		if($distance>0) {
			$increment=$difference/$distance;

			for($n=$fs+$increment; $n<$ts; $n+=$increment) {
				$arr[]=$n;
			}
		}
	}

	else if(regular_move(ROOK, $fc, $tc)) {
		$increment=$difference>7?8:1; //?vertical:horizontal

		for($n=$fs+$increment; $n<$ts; $n+=$increment) {
			$arr[]=$n;
		}
	}

	if($inclusive) {
		$arr[]=$ts;
	}

	return $arr;
}

/*
blocked - is there a non-SQ_EMPTY square between $fs and $ts on $board?

returns false for non-linear movements (ls_inter_sq returns an empty array in
this case)
*/

function blocked($board, $fs, $ts) {
	$intermediate=squares_between($fs, $ts);

	foreach($intermediate as $n) {
		if($board[$n]!==SQ_EMPTY) {
			return true;
		}
	}

	return false;
}

//Attack squares and movement ranges
///////////////////////////////////////////////////////////////////////////////

/*
moves_available - list the squares within range of a geometrically valid (e.g.
"knights go in an L shape") $move of $sq.

validity checks go as far as staying on the board, but that's it -- blockages
and friendly captures will be tested for higher up.

includes pawns and castling.  as with the other types, castling is defined in
terms of relative geometry (a jump of 2 on the x axis) and doesn't take any of
the rules into account.

queen squares is an array_merge of ROOK and BISHOP squares.
*/

function moves_available($move, $sq, $colour) {
	list($x, $y)=sq_to_coords($sq);
	$available=array();

	switch($move) {
		case PAWN: {
			$relsq=rel_sq_no($sq, $colour);

			//double

			if($relsq<16) {
				$available[]=rel_sq_no($relsq+16, $colour);
			}

			//single and captures

			list($relx, $rely)=sq_to_coords($relsq);

			for($x_diff=-1; $x_diff<2; $x_diff++) {
				$_x=$relx+$x_diff;
				$_y=$rely+1;

				if($_x>-1 && $_x<8 && $_y>-1 && $_y<8) {
					$available[]=rel_sq_no(coords_to_sq(array($_x, $_y)), $colour);
				}
			}

			break;
		}

		case KNIGHT: {
			$xdiff=array(-1, -1, 1, 1, -2, -2, 2, 2);
			$ydiff=array(-2, 2, -2, 2, 1, -1, 1, -1);

			for($i=0; $i<8; $i++) {
				$_x=$x+$xdiff[$i];
				$_y=$y+$ydiff[$i];

				if($_x>-1 && $_x<8 && $_y>-1 && $_y<8) {
					$n=coords_to_sq(array($_x, $_y));
					$available[]=$n;
				}
			}

			break;
		}

		case BISHOP: {
			$diff=array(1, -1);

			/*
			a bishop has four branches of attack coming off it, assuming it's
			not right at the edge.  the two nested foreach loops iterating over
			$diff cover each of the four directions.
			*/

			foreach($diff as $dx) {
				foreach($diff as $dy) {
					list($_x, $_y)=array($x, $y); //temp copy of coords for branching

					while($_x>-1 && $_x<8 && $_y>-1 && $_y<8) {
						$_x+=$dx;
						$_y+=$dy;

						if($_x>-1 && $_x<8 && $_y>-1 && $_y<8) {
							$available[]=coords_to_sq(array($_x, $_y));
						}
					}
				}
			}

			break;
		}

		case ROOK: {
			for($i=0; $i<8; $i++) {
				$square=array(
					($y*8)+$i, //same rank
					$x+($i*8) //same file
				);

				foreach($square as $n) {
					if($n!==$sq) {
						$available[]=$n;
					}
				}
			}

			break;
		}

		case QUEEN: {
			$available=array_merge(moves_available(ROOK, $sq, $colour), moves_available(BISHOP, $sq, $colour));

			break;
		}

		case KING: {
			//regular

			for($x_diff=-1; $x_diff<2; $x_diff++) {
				$_x=$x+$x_diff;

				if($_x>-1 && $_x<8) {
					for($y_diff=-1; $y_diff<2; $y_diff++) {
						$_y=$y+$y_diff;

						if($_y>-1 && $_y<8) {
							$available[]=coords_to_sq(array($_x, $_y));
						}
					}
				}
			}

			//castling

			$x_diff=array(-2, 2);

			foreach($x_diff as $d) {
				$_x=$x+$d;

				if($_x>-1 && $_x<8) {
					$available[]=coords_to_sq(array($_x, $y));
				}
			}

			break;
		}
	}

	return $available;
}

/*
pieces_attacking - $colour pieces of type $type that point at $sq on $board.

doesn't include pawns or kings (pawns_attacking/kings_attacking do that).
*/

function pieces_attacking($board, $type, $sq, $colour) {
	$attacker=array();
	$piece=piece($type, $colour);
	$square=moves_available($type, $sq, $colour);

	foreach($square as $n) {
		if($board[$n]===$piece && !blocked($board, $sq, $n)) {
			$attacker[]=$n;
		}
	}

	return $attacker;
}

/*
pawns_attacking - $colour PAWNs attacking $sq on $board
*/

function pawns_attacking($board, $sq, $colour) {
	$piece=piece(PAWN, $colour);
	$plr_colour=opp_colour($colour);
	$relsq=rel_sq_no($sq, $plr_colour);
	$x_diff=array(-1, 1);
	$attacker=array();

	list($relx, $rely)=sq_to_coords($relsq);

	foreach($x_diff as $d) {
		$_x=$relx+$d;
		$_y=$rely+1;

		if($_x>-1 && $_x<8 && $_y>-1 && $_y<8) {
			$n=rel_sq_no(coords_to_sq(array($_x, $_y)), $plr_colour);

			if($board[$n]===$piece) {
				$attacker[]=$n;
			}
		}
	}

	return $attacker;
}

/*
kings_attacking - moves_available can't be used in the usual way because it
includes castling.
*/

function kings_attacking($board, $sq, $colour) {
	$piece=piece(KING, $colour);
	list($x, $y)=sq_to_coords($sq);
	$attackers=array();

	for($x_diff=-1; $x_diff<2; $x_diff++) {
		$_x=$x+$x_diff;

		if($_x>-1 && $_x<8) {
			for($y_diff=-1; $y_diff<2; $y_diff++) {
				$_y=$y+$y_diff;

				if($_y>-1 && $_y<8) {
					$n=coords_to_sq(array($_x, $_y));

					if($board[$n]===$piece) {
						$attackers[]=$n;
					}
				}
			}
		}
	}

	return $attackers;
}

/*
attackers - returns a list of $colour pieces that attack $sq on $board

combines pieces_, pawns_ and kings_attacking (which shouldn't be used on their
own).
*/

function attackers($board, $sq, $colour) {
	$attackers=array();
	$type=array(KNIGHT, BISHOP, ROOK, QUEEN);

	foreach($type as $piecetype) {
		$attackers=array_merge($attackers, pieces_attacking($board, $piecetype, $sq, $colour));
	}

	$attackers=array_merge($attackers, pawns_attacking($board, $sq, $colour));
	$attackers=array_merge($attackers, kings_attacking($board, $sq, $colour));

	return $attackers;
}

//Misc
///////////////////////////////////////////////////////////////////////////////

/*
bitwise functions to extract piece info
*/

function type($piece) {
	return $piece&TYPE;
}

function colour($piece) {
	return $piece>>COLOUR;
}

function piece($type, $colour) {
	return (($colour<<COLOUR)|$type);
}

/*
disambiguate

if there are other pieces of the same type that can also move to $ts, specify
the file/rank/square of the piece that's moving

returns a string such as "a4" to be used as in "Ra4a1"
*/

function disambiguate($board, $type, $colour, $fs, $ts) {
	$str="";
	$pieces_in_range=pieces_attacking($board, $type, $ts, $colour);

	if(count($pieces_in_range)>1) {
		$disambiguation=array(
			"file"=>"",
			"rank"=>""
		);

		foreach($pieces_in_range as $n) {
			if($n!==$fs) {
				if(rank_str($n)===rank_str($fs)) {
					$disambiguation["file"]=file_str($fs);
				}

				if(file_str($n)===file_str($fs)) {
					$disambiguation["rank"]=rank_str($fs);
				}
			}
		}

		$str=$disambiguation["file"].$disambiguation["rank"];

		//if neither rank nor file is the same, specify file

		if(strlen($str)===0) {
			$str=file_str($fs);
		}
	}

	return $str;
}
?>