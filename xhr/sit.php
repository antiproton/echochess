<?php
/*
sit

*table
*colour
*game_id

output:

true or false
*/

require_once "base.php";
require_once "Data.php";
require_once "dbcodes/chess.php";
require_once "php/init.php";
require_once "php/livechess/Table.php";

$result=false;

if($session->user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);
	$table=new Table((int) $q["table"]);

	if($table->sit($session->user->username, $q["colour"], $q["game_id"])) {
		$result=true;
	}
}

echo Data::serialise($result);
?>