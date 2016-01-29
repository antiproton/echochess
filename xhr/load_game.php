<?php
/*
game details (for initial load):

*gid

output:

the game data or false
*/

require_once "base.php";
require_once "Data.php";
require_once "Db.php";
require_once "php/init.php";

$result=false;

if($user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);
	$gid=$q["gid"];
	$result=$db->row("select * from games where gid='$gid'");

	/*
	add a timestamp for use in calculating player clock times
	*/

	if($result!==false) {
		$result["server_time"]=mtime();
	}
}

echo Data::serialise($result);
?>