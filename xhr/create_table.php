<?php
/*
create table:

*type
*rated
variant
subvariant
fen
timing_initial
timing_increment
timing_style
permissions_watch
permissions_play

output:

the table id or false

this is for creating CASUAL games, so event_type isn't specified
(games that have different event types will probably have to be
created automatically, e.g. tournament games)

NOTE

alternate_colours
chess960_rerandomise_mode

left out of options for now
*/

require_once "base.php";
require_once "Data.php";
require_once "dbcodes/chess.php";
require_once "php/init.php";
require_once "php/livechess/Table.php";

$result=false;

if($session->user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	if(isset($q["type"])) {
		$challenge_colour=WHITE;
		$fen=null;

		if(isset($q["fen"])) {
			$fen=$q["fen"];
		}

		$table=new Table();

		if($table->setup($session->user->username, $q["type"], $q["rated"], EVENT_TYPE_CASUAL, CHALLENGE_TYPE_CUSTOM, false, WHITE, null, $fen)) {
			$optional_fields=[
				"variant",
				"subvariant",
				"timing_initial",
				"timing_increment",
				"timing_style",
				"permissions_watch",
				"permissions_play"
			];

			/*
			set the owner rating with the initial options set

			the user can change the options so that their rating for games played
			is different to the owner rating, but it is too costly to update the owner
			rating every time.

			hopefully the initial owner rating will be a good enough indication of
			the owner's strength for people joining tables to find a decent opponent
			*/

			$variant=VARIANT_STANDARD;

			if(isset($q["variant"])) {
				$variant=$q["variant"];
			}

			$format=GAME_FORMAT_OVERALL;

			if(isset($q["timing_style"]) && isset($q["timing_initial"])) {
				$increment=0;

				if(isset($q["timing_increment"])) {
					$increment=$q["timing_increment"];
				}

				$format=Timing::get_format($q["timing_style"], $q["timing_initial"], $increment);
			}

			$table->owner_rating=Ratings::get_rating($session->user->username, $q["type"], $variant, $format);

			foreach($optional_fields as $field) {
				if(isset($q[$field])) {
					$table->$field=$q[$field];
				}
			}

			if($table->save()) {
				$result=$table->id;
			}
		}
	}
}

echo Data::serialise($result);
?>