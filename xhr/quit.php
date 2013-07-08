<?php
/*
quit - takes a list of tables that the user has quit from and notifies
opponents that the user has disconnected.

also sets a flag in live_table_quit

this should be called on main window close but not popup close (closing
a detached tab popup window should be the same as closing the tab - resigns
straight away)
*/

require_once "base.php";
require_once "php/init.php";
require_once "php/Messages.php";
require_once "Data.php";
require_once "dbcodes/chess.php";

if($session->user->signedin) {
	$ids=array_map("intval", Data::unserialise_clean($_GET["q"]));

	//notify users

	if(is_array($ids) && count($ids)>0) {
		$opponents=Db::table("
			select user, tables
			from seats
			where type='".SEAT_TYPE_PLAYER."'
			and user!='{$session->user->username}'
			and tables in (".implode(", ", $ids).")
		");

		foreach($opponents as $row) {
			Messages::send($session->user->username, $row["user"], MESSAGE_TYPE_OPPONENT_DISCONNECT, $row["tables"]);
		}

		$mtime=mtime();

		foreach($ids as $id) {
			Db::insert_or_update("live_table_quit", [
				"user"=>$session->user->username,
				"tables"=>$id,
				"mtime_quit"=>$mtime
			]);
		}
	}
}
?>