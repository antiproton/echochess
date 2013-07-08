<?php
/*
challenge_open:

find and accept a matching challenge or create a new one

*variant
rating_min
rating_max
*timing_initial
*timing_increment
*choose_colour
*challenge_colour
*rated

output:

true if the user created a challenge and it was accepted
false if the user created a challenge and no one accepted it within the timeout period
a table id if the user accepted a challenge

if the user creates a challenge and it is accepted they will get the id in
a message
*/

require_once "base.php";
require_once "Data.php";
require_once "Query.php";
require_once "dbcodes/chess.php";
require_once "php/constants.php";
require_once "php/init.php";
require_once "php/livechess/Table.php";
require_once "php/livechess/Ratings.php";
require_once "php/chess/Timing.php";

$result=false;

if($session->user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	$user_rating=Ratings::get_rating(
		$session->user->username,
		GAME_TYPE_STANDARD,
		$q["variant"],
		Timing::get_format(TIMING_FISCHER_AFTER, $q["timing_initial"], $q["timing_increment"])
	);

	//rating_min and _max can be specified as absolute values, or relative to
	//the user's rating with a plus or minus sign

	foreach(["rating_min", "rating_max"] as $var) {
		if(isset($q[$var])) {
			$str=trim($q[$var]);
			$temp=0;

			if(startswith($str, "-") || startswith($str, "+")) {
				$temp=$user_rating+(int) $str;
			}

			else {
				$temp=(int) $str;
			}

			if($temp>0) {
				$$var=$temp;
			}
		}
	}

	/*
	check for a matching challenge

	gets the one where the ratings are closest if there is one
	*/

	$existing_challenge=false;

	if(!isset($q["challenge_to"]) || $q["challenge_to"]===null) {
		$query=new Query("
			select id
			from tables
			where challenge_type='".CHALLENGE_TYPE_QUICK."'
			and challenge_accepted=".Db::BOOL_FALSE."
			and owner!='{$session->user->username}'
		");

		$query->add_cond($rating_min, "owner_rating", ">=");
		$query->add_cond($rating_max, "owner_rating", "<=");
		$query->add_cond_arr($q, "timing_initial");
		$query->add_cond_arr($q, "timing_increment");
		$query->add_cond_arr($q, "variant");
		$query->add_cond_arr($q, "subvariant");

		$query->str.=" and (accept_rating_min is null or $user_rating>=accept_rating_min)";
		$query->str.=" and (accept_rating_max is null or $user_rating<=accept_rating_max)";

		if($q["choose_colour"]) {
			$query->str.=" and (choose_colour=".Db::BOOL_FALSE." or challenge_colour=".opp_colour($q["challenge_colour"]).")";
		}

		$query->str.=" order by abs(owner_rating-$user_rating) asc limit 1";

		$existing_challenge=Db::cell($query->str);
	}

	if($existing_challenge!==false) { //accept the challenge
		$table=new Table($existing_challenge);
		$table->challenge_accept($session->user->username);
		$result=$existing_challenge;
	}

	else { //create a new challenge
		$table=new Table();

		$table->setup(
			$session->user->username,
			GAME_TYPE_STANDARD,
			$q["rated"],
			EVENT_TYPE_CASUAL,
			CHALLENGE_TYPE_QUICK,
			$q["choose_colour"],
			$q["challenge_colour"],
			$q["challenge_to"]
		);

		$table->variant=$q["variant"];
		$table->timing_initial=$q["timing_initial"];
		$table->timing_increment=$q["timing_increment"];

		if(isset($rating_min)) {
			$table->accept_rating_min=$rating_min;
		}

		if(isset($rating_max)) {
			$table->accept_rating_max=$rating_max;
		}

		$table->owner_rating=$user_rating;

		$table->save();
		$id=$table->id;

		session_commit();
		Db::commit();

		$timeout=time()+QUICK_CHALLENGE_SEEK_TIMEOUT;
		$usec_delay=LONGPOLL_DELAY*USEC_PER_SEC;

		while(time()<$timeout) {
			usleep($usec_delay);

			$data=Db::row("select challenge_accepted, challenge_declined from tables where id=$id");

			if($data["challenge_accepted"]) {
				$result=$id;

				break;
			}

			else if($data["challenge_declined"]) {
				break;
			}
		}

		/*
		if no one accepted the challenge within the timeout period, the
		table can be deleted
		*/

		if(!$result) {
			$table=new Table($id);
			$table->delete();
		}
	}
}

echo Data::serialise($result);
?>