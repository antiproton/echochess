<?php
/*
longpoll "sessions" are stored so that each user only has one
longpoll running for each tab they have open

the response contains the server time as well, so it's:

{
	data: (the updates)
	server_time: mtime at the time of sending the response back
}
*/

/*
NOTE input is stripped of single quotes, but data that is supposed to be
numeric can still be text, so numbers should be cast outside the sql query
or quoted inside it:

"select * from tables where id='0 or 1=1; drop table users\c'"

or

$id=(int) "0 or 1=1; drop table users\\c";
"select * from tables where id=$id"
*/

require_once "base.php";
require_once "dbcodes/chess.php";
require_once "Data.php";
require_once "php/chess/util.php";
require_once "php/constants.php";
require_once "php/init.php";
require_once "php/db.php";
require_once "Clean.php";
require_once "php/Messages.php";
require_once "php/GenericUpdates.php";
require_once "php/livechess/Premoves.php";

session_commit();

$update=[];
$q=Data::unserialise_clean($_GET["q"]);
$usec_delay=LONGPOLL_DELAY*USEC_PER_SEC;
$timeout=time()+LONGPOLL_TIMEOUT;
$mtime=mtime();
$window_id=Clean::$get["id"];

if($session->user->signedin) {
	$user=$session->user->username;

	Db::insert_or_update("longpolls", [
		"user"=>$user,
		"window_id"=>$window_id,
		"mtime"=>$mtime
	]);

	msg("\n----------------------------------------------------------------");
	msg("$user $window_id $mtime starting");

	Db::commit();

	while(true) {
		$iteration_start_time=mtime();
		$have_updates=false;

		$current_mtime=Db::cell("
			select mtime
			from longpolls
			where user='$user'
			and window_id='$window_id'
		");

		//DEBUG

		if(time()>$timeout) {
			msg("$user $window_id $mtime $current_mtime timeout");
		}

		if($mtime!==$current_mtime) {
			msg("$user $window_id $mtime $current_mtime replaced");
		}

		if(time()>$timeout || $mtime!==$current_mtime) {
			msg("$user $window_id $mtime $current_mtime deleting - count ".Db::cell("select count(*) from longpolls where user='$user'"));

			Db::remove("longpolls", [
				"user"=>$user,
				"mtime"=>$mtime,
				"window_id"=>$window_id
			]);

			msg(Data::serialise(Db::table("select * from longpolls where user='$user'")));
			msg("printing mysql error");
			msg(mysqli_error(Db::$default_link));

			msg("$user $window_id $mtime $current_mtime deleted - count ".Db::cell("select count(*) from longpolls"));

			break;
		}

		foreach($q as $id=>$data) {
			switch($data[UPDATES_TYPE_PROP]) {
				case UPDATE_TYPE_GENERIC_UPDATES: {
					/*
					very basic update - "something has changed since last time"

					types of things can be found in codes
					*/

					$last_update=GenericUpdates::get_last_update($data["type"], $user);

					if($last_update>$data["last_update"]) {
						$update[$id]=[
							"last_update"=>$last_update
						];

						$have_updates=true;
					}

					break;
				}

				case UPDATE_TYPE_MESSAGES: {
					/*
					type
					subject
					sender

					none required
					*/

					$type=null;
					$subject=null;
					$sender=null;

					if(isset($data["type"])) {
						$type=$data["type"];
					}

					if(isset($data["subject"])) {
						$subject=$data["subject"];
					}

					if(isset($data["sender"])) {
						$sender=$data["sender"];
					}

					$msgs=Messages::retrieve($user, $type, $subject, $sender);

					if($msgs!==false && count($msgs)>0) {
						$update[$id]=$msgs;
						$have_updates=true;
					}

					break;
				}

				case UPDATE_TYPE_DIRECT_CHALLENGE: {
					/*
					last_challenge_mtime

					returns a list of open challenges directed at the user
					*/

					$table=Db::table("
						select
							owner,
							variant,
							timing_initial,
							timing_increment,
							rated,
							choose_colour,
							challenge_colour,
							mtime_created,
							id
						from tables
						where challenge_type='".CHALLENGE_TYPE_QUICK."'
						and challenge_accepted=".Db::BOOL_FALSE."
						and challenge_to='$user'
						and mtime_created>'{$data["last_challenge_mtime"]}'
					");

					if(count($table)>0) {
						$update[$id]=$table;
						$have_updates=true;
					}

					break;
				}

				case UPDATE_TYPE_TABLE: {
					/*
					"id"
					"mtime_last_update"
					*/

					$row=Db::row("
						select *
						from tables
						where id='{$data["id"]}'
						and mtime_last_update>'{$data["mtime_last_update"]}'
					");

					if($row!==false) {
						$update[$id]=$row;
						$have_updates=true;
					}

					break;
				}

				case UPDATE_TYPE_SEAT: {
					/*
					*table
					*colour
					*game_id
					*ready
					*username (or null)

					sends back details if username is different

					NOTE this used to also update if the ready changed, but this is
					unnecessary so has been taken out
					*/

					$row=Db::row("
						select user, colour, ready
						from seats
						where tables='{$data["table"]}'
						and game_id='{$data["game_id"]}'
						and colour='{$data["colour"]}'
						and type='".SEAT_TYPE_PLAYER."'
					");

					if($row===false) {
						$ready=false;
						$username=null;
					}

					else {
						$username=$row["user"];
						$ready=$row["ready"];
					}

					if($username!==$data["username"]/* || $ready!==$data["ready"]*/) {
						$update[$id]=[
							"username"=>$username,
							"ready"=>$ready
						];

						$have_updates=true;
					}

					break;
				}

				case UPDATE_TYPE_GAME: {
					/*
					"gid"
					"mtime_last_update"
					*/

					$row=Db::row("
						select
							state,
							threefold_claimable,
							fiftymove_claimable,
							draw_offered,
							undo_requested,
							undo_granted,
							result,
							result_details,
							mtime_finish,
							mtime_last_update
						from games
						where
							gid='{$data["gid"]}'
							and mtime_last_update>'{$data["mtime_last_update"]}'"
					);

					if($row!==false) {
						$update[$id]=$row;
						$have_updates=true;
					}

					break;
				}

				case UPDATE_TYPE_HISTORY: {
					/*
					*gid
					*move_index
					colour (supply to only get moves by that colour)
					*/

					//this won't tell the client that moves have been undone (see separate type for that)

					/*
					this pulls back all moves since the current move index (which will include moves
					that the user has just made), but then only sends the update if either there is
					an opponent move or there is some reason to send back the player's moves as well
					(if the client wants all moves (no colour specified), if it was a premove, or if
					there have been multiple moves since the given move_index)
					*/

					$where="gid='{$data["gid"]}' and move_index>'{$data["move_index"]}'";
					$table=Db::table("select * from moves where $where order by move_index");
					$count=count($table);

					if($table!==false && $count>0) {
						if(!isset($data["colour"]) || $table[0]["colour"]===$data["colour"] || $table[0]["premove"] || $count>1) {
							$update[$id]=$table;
							$have_updates=true;
						}
					}

					break;
				}

				case UPDATE_TYPE_UNDO: {
					/*
					 check whether the given move has been undone

					"gid"
					"move_index"
					*/

					$exists=Db::row("
						select move_index
						from moves
						where gid='{$data["gid"]}'
						and move_index='{$data["move_index"]}'
					");

					if($exists===false) {
						$update[$id]=true;
						$have_updates=true;
					}

					break;
				}

				case UPDATE_TYPE_PREMOVES: {
					/*
					"gid"
					"move_index"
					"colour"
					*/

					/*
					if opponent has moved since we last checked, we need a
					premoves update
					*/

					$opp_moves=Db::cell("
						select count(*)
						from moves
						where gid='{$data["gid"]}'
						and move_index>'{$data["move_index"]}'
						and colour='".opp_colour($data["colour"])."'
					");

					if($opp_moves>0) {
						$update[$id]=Db::table("
							select fs, ts, promote_to, move_index
							from premoves
							where gid='{$data["gid"]}'
							and user='$user'
							order by move_index
						");

						$have_updates=true;
					}

					break;
				}

				case UPDATE_TYPE_COMMENTS: {
					/*
					"type"
					"subject"
					"mtime_last_post"
					*/

					$query="
						select user, body, subject_line, mtime_posted
						from comments
						where type='{$data["type"]}'
						and subject='{$data["subject"]}'
						and mtime_posted>'{$data["mtime_last_post"]}'
						and user!='$user'
					";

					//stop players from seeing table comments from spectators:

					if($data["type"]===COMMENT_TYPE_TABLE) {
						$query.="
							and (
								(select game_in_progress from tables where id='{$data["subject"]}')=".Db::BOOL_FALSE."
								or
								not exists (
									select * from seats
									where tables='{$data["subject"]}'
									and user='$user'
									and type='".SEAT_TYPE_PLAYER."'
								)
								or
								exists (
									select * from seats
									where tables='{$data["subject"]}'
									and user=comments.user
									and type='".SEAT_TYPE_PLAYER."'
								)
							)
						";
					}

					$comments=Db::table($query);

					if(count($comments)>0) {
						$update[$id]=$comments;
						$have_updates=true;
					}

					break;
				}
			}
		}

		if($have_updates) {
			break;
		}

		/*
		wait for the delay time, minus any time spent on the checks.
		*/

		$msec_delay=round($usec_delay/USEC_PER_MSEC);
		$iteration_end_time=mtime();
		$time_taken=$iteration_end_time-$iteration_start_time;

		if($time_taken<$msec_delay) {
			usleep($usec_delay-($time_taken*USEC_PER_MSEC));
		}
	}

	msg("$user $window_id $mtime $current_mtime quitting");
	msg("----------------------------------------------------------------\n");

	echo Data::serialise([
		"data"=>$update,
		"server_time"=>mtime()
	]);
}
?>