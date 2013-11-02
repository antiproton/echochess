<?php
/*
talk to your bughouse teammate
*/

require_once "base.php";
require_once "php/init.php";
require_once "php/Messages.php";
require_once "Data.php";
require_once "dbcodes/chess.php";

$result=false;

if($user->signedin) {
	$q=Data::unserialise($_GET["q"]);

	$result=Messages::send($user->username, $q["partner"], MESSAGE_TYPE_TEAM_CHAT, $q["table"], $q["message"]);
}

echo Data::serialise($result);
?>