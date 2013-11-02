<?php
/*
games

*table
*games - the number of games required.  a maximum of this number of games will be returned,
ordered by mtime_start to avoid bringing back old games.

it is theoretically possible to bring back all the games along with mtime_start etc,
so that LiveTable can sort it out on the client side and let the user flip through
each game in the session.

output:

list of gids and game_ids, or false
*/

require_once "base.php";
require_once "Data.php";
require_once "Db.php";
require_once "php/init.php";
require_once "dbcodes/chess.php";

$result=false;

if($user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);
	$limit=(int) $q["games"];

	$result=$db->table("
		select gid, game_id
		from games
		where tables='{$q["table"]}'
		order by mtime_start desc
		limit $limit
	");
}

echo Data::serialise($result);
?>