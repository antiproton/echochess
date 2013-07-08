<?php
/*
premove_push - add a premove:

*gid
*fs
*ts
*move_index
promote_to

output:

the move index and whether it succeeded.

the move index is necessary so that order can be maintained even if multiple
moves are added so quickly that the requests don't come back in the right order.
*/

require_once "base.php";
require_once "Data.php";
require_once "Curl.php";
require_once "php/init.php";
require_once "php/livechess/LiveGame.php";
require_once "php/livechess/Seat.php";

$result=false;

if($session->user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);
	$promote_to=null;

	if(isset($q["promote_to"])) {
		$promote_to=$q["promote_to"];
	}

	if(Seat::is_seated($session->user->username, $q["gid"])) {
		$game=new LiveGame($q["gid"]);
		$game->check_premoves();
		$success=$game->premove($session->user->username, $q["fs"], $q["ts"], $q["move_index"], $promote_to);

		if($success) {
			$game->check_premoves();
			$game->save();

			$now=mtime();
			$game_start=$game->mtime_start+($game->clock_start_delay*MSEC_PER_SEC);

			if($now<$game_start) {
				Curl::get("http://chess:10250/check_premoves", [
					"gid"=>$q["gid"],
					"sleep"=>($game_start-$now)/MSEC_PER_SEC
				]);
			}
		}

		$result=[
			"success"=>$success,
			"move_index"=>$q["move_index"]
		];
	}
}

echo Data::serialise($result);
?>