<?php
/*
deny_undo

gid

output:

true or false
*/

require_once "base.php";
require_once "Data.php";
require_once "php/livechess/LiveGame.php";
require_once "php/init.php";

$result=false;

if($user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	$colour=$db->cell("
		select colour
		from seats
		where user='{$user->username}'
		and gid='{$q["gid"]}'
		and type='".SEAT_TYPE_PLAYER."'
	");

	if($colour!==false) {
		$game=new LiveGame($q["gid"]);

		if($game->deny_undo() && $game->save()) {
			$result=true;
		}
	}
}

echo Data::serialise($result);
?>