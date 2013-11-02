<?php
/*
check time:

*gid

output:

nothing

the client xhrs this when someone's clock is close to flagging.
this is the only way games can be ended on time reliably-ish.
*/

require_once "base.php";
require_once "Data.php";
require_once "php/init.php";
require_once "php/livechess/LiveGame.php";

session_commit();

if($user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);
	$gid=$q["gid"];

	while(true) {
		$game=new LiveGame($gid);

		if($game->state!==GAME_STATE_IN_PROGRESS) {
			break;
		}

		$timeout=$game->check_time();

		if($timeout) {
			$game->save();

			break;
		}

		$closest_timeout=min($game->time[WHITE], $game->time[BLACK]);

		if($closest_timeout>=10*MSEC_PER_SEC) {
			break;
		}

		$sleep_ms=$closest_timeout+35;

		usleep($sleep_ms*USEC_PER_MSEC);
	}
}
?>