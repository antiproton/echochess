<?php
/*
bughouse_move

gid
piece
ts
last move round trip time

output:

false or the mtime of the move
*/

require_once "base.php";
require_once "Data.php";
require_once "php/livechess/LiveGame.php";
require_once "php/init.php";

$result=false;

if($user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	if(isset($q["gid"]) && isset($q["piece"]) && isset($q["ts"])) {
		$now=mtime();
		$time_compensation=min(MSEC_PER_SEC, round($q["lrtt"]/2));
		$move_mtime=$now-$time_compensation;

		$piece=$q["piece"];
		$square=$q["ts"];

		$colour=$db->cell("
			select colour
			from seats
			where user='{$user->username}'
			and gid='{$q["gid"]}'
			and type='".SEAT_TYPE_PLAYER."'
		");

		if($colour!==false) {
			$game=new LiveGame($q["gid"]);

			if($game->bughouse_move($user->username, $piece, $square, false, $move_mtime)->success) {
				$result=$game->history->main_line->last_move->mtime;
				$game->save();
			}
		}
	}
}

echo Data::serialise($result);
?>