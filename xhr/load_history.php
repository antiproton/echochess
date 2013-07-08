<?php
/*
load_history

*gid

output:

move list (empty if no moves)
*/

require_once "base.php";
require_once "Data.php";
require_once "php/db.php";
require_once "php/init.php";
require_once "dbcodes/chess.php";

$result=false;

if($session->user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);
	$result=Db::table("select * from moves where gid='{$q["gid"]}'");
}

echo Data::serialise($result);
?>