<?php
/*
input:

*gid
move_index (if not specified, all will be removed)

output:

nothing

NOTE this is done through the static method on Premoves instead
of through a LiveGame for performance.
*/

require_once "base.php";
require_once "Data.php";
require_once "php/init.php";
require_once "dbcodes/chess.php";
require_once "php/livechess/Seat.php";
require_once "php/livechess/Premoves.php";

if($session->user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);
	$move_index=null;

	if(isset($q["move_index"])) {
		$move_index=$q["move_index"];
	}

	if(Seat::is_seated($session->user->username, $q["gid"])) {
		Premoves::delete($session->user->username, $q["gid"], $move_index);
	}
}
?>