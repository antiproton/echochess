<?php
/*
stand

*table

output:

true or false

NOTE table->save required now as games_played gets reset if a player stands up
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

	$result=($table->stand($session->user->username) && $table->save());
}

echo Data::serialise($result);
?>