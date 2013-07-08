<?php
/*
leave

called (hopefully) when a user playing or spectating a table closes the
window, shuts down computer etc.

NOTE this won't get called if they crash or disable it somehow, so shouldn't
be relied upon.  TODO to free up tables, users should maybe be checked and
if they're gone (haven't updated for longer than LONGPOLL_TIMEOUT maybe), the
seat should "leave" automatically

*table

output:

true or false

NOTE table->save required now as games_played gets reset if a player leaves
*/

require_once "base.php";
require_once "Data.php";
require_once "dbcodes/chess.php";
require_once "php/init.php";
require_once "php/livechess/Table.php";

$result=false;

$q=Data::unserialise_clean($_GET["q"]);

if(isset($q["table"])) {
	$table=new Table($q["table"]);
	$result=($table->leave($session->user->username) && $table->save());
}

echo Data::serialise($result);
?>