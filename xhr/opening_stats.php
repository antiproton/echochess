<?php
require_once "base.php";
require_once "php/constants.php";
require_once "php/db.php";

/*
Get a list of games where the supplied opening moves were played.

FIXME this is using moves.label, which isn't a field - would probably be better
to use fs and ts.  Wouldn't always work for bughouse games, (castling), but then
wgaf about them.

FIXME insecure (commented for now)
*/

//if(isset(Clean::$get["line"])) {
//	$line=explode(",", Clean::$get["line"]);
//	$len=count($line);
//
//	if($len>0 && $len<OPENING_STATS_MAX_DEPTH) {
//		$join="";
//
//		for($i=1; $i<$len; $i++) {
//			$join.="
//				left join moves as moves_$i
//				on (moves_$i.game=moves.game
//				and moves_$i.move_index=$i
//				and moves_$i.label='{$line[$i]}')
//			";
//		}
//
//		$query="
//			select moves.gid
//			from moves $join
//			where moves.move_index=0
//			and moves.label='{$line[0]}'
//		";
//
//		$games=$db->col($query);
//
//		echo Data::serialise($games);
//	}
//
//	else {
//		echo "Too deep";
//	}
//}
?>