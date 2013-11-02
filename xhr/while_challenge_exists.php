<?php
/*
sleep while the specified challenge is still valid (exists and not accepted)

*id

output: nothing
*/

require_once "base.php";
require_once "Data.php";
require_once "dbcodes/chess.php";
require_once "php/constants.php";
require_once "php/init.php";

if($user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	session_commit();
	$db->commit();

	$timeout=time()+QUICK_CHALLENGE_SEEK_TIMEOUT;
	$usec_delay=LONGPOLL_DELAY*USEC_PER_SEC;

	$query="
		select id
		from tables
		where id='{$q["id"]}'
		and challenge_accepted=".$db->db_value(false)."
	";

	while(time()<$timeout && $db->cell($query)) {
		usleep($usec_delay);
	}
}
?>