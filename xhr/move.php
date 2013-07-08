<?php
/*
input:

*gid
*fs
*ts
promote_to

ouptut:

false or the mtime of the move
*/

require_once "base.php";
require_once "Data.php";
require_once "php/livechess/LiveGame.php";
require_once "php/init.php";

$result=false;

if($session->user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	/*
	time compensation - assume that the move was made half the last round
	trip time earlier than it was received.

	the maximum time compensation allowed is 1s

	the first lrtt set will be half a second for everyone.
	*/

	$now=mtime();
	$time_compensation=min(MSEC_PER_SEC, round($q["lrtt"]/2));
	$move_mtime=$now-$time_compensation;

	if(isset($q["gid"]) && isset($q["fs"]) && isset($q["ts"])) {
		$colour=Db::cell("
			select colour from seats
			where user='{$session->user->username}'
			and gid='{$q["gid"]}'
			and type='".SEAT_TYPE_PLAYER."'
		");

		if($colour!==false) {
			$promote_to=QUEEN;

			if(isset($q["promote_to"])) {
				$promote_to=$q["promote_to"];
			}

			$game=new LiveGame($q["gid"]);
			$game->check_premoves();

			if($game->position->active===$colour) {
				if($game->move($session->user->username, $q["fs"], $q["ts"], $promote_to, false, false, $move_mtime)->success) {
					$result=$now;
					$game->check_premoves();
					$game->save();
				}
			}
		}
	}
}

echo Data::serialise($result);
?>