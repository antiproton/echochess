<?php
/*
input:

gid

ouptut:

success (true or false)
*/

require_once "base.php";
require_once "Data.php";
require_once "php/livechess/LiveGame.php";
require_once "php/init.php";

$result=false;

if($session->user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	if(isset($q["gid"])) {
		$colour=Db::cell("
			select colour from seats
			where user='{$session->user->username}'
			and gid='{$q["gid"]}'
			and type='".SEAT_TYPE_PLAYER."'
		");

		if($colour!==false) {
			$game=new LiveGame($q["gid"]);
			$result=($game->offer_draw($colour) && $game->save());
		}
	}
}

echo Data::serialise($result);
?>