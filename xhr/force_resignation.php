<?php
/*
input:

*gid

ouptut:

true or false
*/

require_once "base.php";
require_once "Data.php";
require_once "php/livechess/LiveGame.php";
require_once "php/init.php";
require_once "php/constants.php";

$result=false;

if($user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	if(isset($q["gid"])) {
		$colours=[
			"white"=>WHITE,
			"black"=>BLACK
		];

		$game=new LiveGame($q["gid"]);
		$opp_colour=null;
		$opponent=null;

		if($game->state===GAME_STATE_IN_PROGRESS && $game->timing_initial<=LONGEST_GAME_TO_RESIGN_IF_QUIT) {
			foreach($colours as $field=>$colour) {
				if($game->$field!==$user->username) {
					$opp_colour=$colour;
					$opponent=$game->$field;

					break;
				}
			}

			if($opponent!==null) {
				$mtime_quit=$db->cell("
					select mtime_quit
					from live_table_quit
					where user='$opponent'
					and tables='{$game->table}'
				");

				if($mtime_quit!==false && mtime()-$mtime_quit>(MIN_DC_TIME_TO_FORCE_RESIGN*MSEC_PER_SEC)) {
					$game->resign($opp_colour);
					$result=$game->save();
				}
			}
		}

	}
}

echo Data::serialise($result);
?>