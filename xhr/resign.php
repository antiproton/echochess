<?php
/*
resign:

gid

ouptut:

true or false
*/

require_once "base.php";
require_once "Data.php";
require_once "php/livechess/LiveGame.php";
require_once "php/init.php";

$result=false;

if($user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	if(isset($q["gid"])) {
		$colour=$db->cell("
			select colour
			from seats
			where user='{$user->username}'
			and gid='{$q["gid"]}' and type='".SEAT_TYPE_PLAYER."'"
		);

		if($colour!==false) {
			$game=new LiveGame($q["gid"]);
			$game->resign($colour);
			$result=$game->save();
		}
	}
}

echo Data::serialise($result);
?>