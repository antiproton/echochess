<?php
/*
tables_custom:

NOTE currently not using permissions - all games are open to anyone

TODO not using FEN yet but the display on the client should be a little
board that comes up on hover (and the row should be clearly marked to
indicate that it is a custom fen... users would be annoyed to find that
it is mate in 1 etc)

output:

list of tables, or false if not signed in
*/

require_once "base.php";
require_once "Db.php";
require_once "Data.php";
require_once "dbcodes/chess.php";
require_once "php/init.php";
require_once "Query.php";
require_once "php/constants.php";
require_once "php/chess/constants.php";

$result=false;

if($user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	$query=new Query("
		select
			owner,
			type,
			variant,
			timing_style,
			timing_initial,
			timing_increment,
			timing_overtime,
			timing_overtime_increment,
			timing_overtime_cutoff,
			owner_rating,
			rated,
			(select user from seats where tables=tables.id and game_id=0 and colour=".WHITE." and type='".SEAT_TYPE_PLAYER."') as game_0_white,
			(select user from seats where tables=tables.id and game_id=0 and colour=".BLACK." and type='".SEAT_TYPE_PLAYER."') as game_0_black,
			(select user from seats where tables=tables.id and game_id=1 and colour=".WHITE." and type='".SEAT_TYPE_PLAYER."') as game_1_white,
			(select user from seats where tables=tables.id and game_id=1 and colour=".BLACK." and type='".SEAT_TYPE_PLAYER."') as game_1_black,
			/*fen,*/
			id
		from
			tables
		where
			event_type='".EVENT_TYPE_CASUAL."'
			and challenge_type='".CHALLENGE_TYPE_CUSTOM."'
	");

	$query->add_cond_arr($q, "type");
	$query->add_cond_arr($q, "variant");
	$query->add_cond_arr($q, "format");
	$query->add_cond_arr($q, "rated");

	$rating_conditions=[
		"rating_min"=>">=",
		"rating_max"=>"<="
	];

	foreach($rating_conditions as $field=>$operator) {
		if(isset($q[$field])) {
			$str=trim($q[$field]);

			if(startswith($str, "-") || startswith($str, "+")) {
				//deconstruct and rebuild the strings for db safety

				$temp=(int) $str;

				if($temp<0) {
					$str="$temp";
				}

				else {
					$str="+$temp";
				}

				//compare owner rating against whatever the user's rating is for the table settings

				$query->str.="
					and owner_rating $operator get_rating(
						'{$user->username}',
						tables.type,
						tables.variant,
						tables.format
					)$str
				";
			}

			else {
				$rating=(int) $str;

				if($rating>0) {
					$query->add_cond($rating, "owner_rating", $operator);
				}
			}
		}
	}

	$query->str.=" order by owner_rating desc";
	$query->str.=" limit ".TABLE_LIST_LIMIT_CUSTOM;

	$result=$db->table($query->str);
}

echo Data::serialise($result);
?>