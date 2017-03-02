<?php
/*
tables_quick:

filters

output:

list of tables or false
*/

require_once "base.php";
require_once "Db.php";
require_once "Data.php";
require_once "dbcodes/chess.php";
require_once "php/init.php";
require_once "Query.php";
require_once "php/chess/util.php";
require_once "php/constants.php";

$result=false;

if($user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	$rating_conditions=[
		"rating_min"=>">=",
		"rating_max"=>"<="
	];

	$query=new Query("
		select
			owner,
			variant,
			timing_initial,
			timing_increment,
			owner_rating,
			rated,
			choose_colour,
			challenge_colour,
			id
		from
			tables
		where
			event_type='".EVENT_TYPE_CASUAL."'
			and challenge_type='".CHALLENGE_TYPE_QUICK."'
			and challenge_accepted=".$db->db_value(false)."
			and (challenge_to is null or challenge_to='{$user->username}')
	");

	/*
	compare user's rating against min/max accept ratings set by the owner

	NOTE this can be moved to the client if it becomes a bottleneck
	*/

	foreach($rating_conditions as $field=>$operator) {
		$query->str.=" and (
			accept_$field is null
			or 1200 $operator accept_$field
		)";
	}

	$query->add_cond_arr($q, "variant");
	$query->add_cond_arr($q, "format");
	$query->add_cond_arr($q, "rated");

	if(isset($q["colour"])) {
		$query->str.=" and choose_colour=".$db->db_value(true);
		$query->str.=" and challenge_colour=".opp_colour($q["colour"]);
	}

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
				//see tables_custom for notes

				$query->str.="
					and owner_rating $operator 1200$str
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
	$query->str.=" limit ".TABLE_LIST_LIMIT_QUICK;

	$result=$db->table($query->str);
}

echo Data::serialise($result);
?>