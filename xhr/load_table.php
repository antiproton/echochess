<?php
/*
table details (for initial load):

*id

output:

the table data or false

opponents are notified that the user has connected.
*/

require_once "base.php";
require_once "Data.php";
require_once "Db.php";
require_once "php/init.php";
require_once "dbcodes/chess.php";
require_once "php/Messages.php";
require_once "php/livechess/LiveGame.php";

$result=false;

if($user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);
	$id=$q["id"];
	$result=$db->row("select * from tables where id='$id'");

	if($result!==false) {
		/*
		do an initial time check in case no one was present to send the check_time
		xhr when time got low
		*/

		$games_in_progress=$db->col("
			select gid
			from games
			where state='".GAME_STATE_IN_PROGRESS."'
			and tables='$id'
		");

		if($games_in_progress!==false) {
			foreach($games_in_progress as $gid) {
				$game=new LiveGame($gid);

				if($game->check_time()) {
					$game->save();
				}
			}
		}

		/*
		de-quit from the table and message other seated players saying the user
		has connected
		*/

		$seats=$db->col("
			select user
			from seats
			where tables='$id'
			and type='".SEAT_TYPE_PLAYER."'
		");

		$seated=false;

		if($seats!==false) {
			foreach($seats as $user) {
				if($user===$user->username) {
					$seated=true;

					break;
				}
			}
		}

		if($seated) {
			$db->remove("live_table_quit", [
				"user"=>$user->username,
				"tables"=>$id
			]);

			foreach($seats as $user) {
				if($user!==$user->username) {
					Messages::send($user->username, $user, MESSAGE_TYPE_OPPONENT_CONNECT, $id);
				}
			}
		}
	}
}

echo Data::serialise($result);
?>