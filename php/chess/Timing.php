<?php
require_once "dbcodes/chess.php";

class Timing {
	/*
	avg moves per game - used to calculate how much time each player is likely
	to have for the game when deciding which time format it comes under
	*/

	const AVG_MOVES_PER_GAME=40;

	/*
	calculated time (taking increment and overtime into account) is checked
	against each on of the following code/limit pairs.  if less than or equal
	to the limit, the format is the associated code.

	if none of the codes match, the format is correspondence
	*/

	private static $max_times=[
		GAME_FORMAT_BULLET=>60, //1 min
		GAME_FORMAT_BLITZ=>600, //10 min
		GAME_FORMAT_QUICK=>1800, //half an hour
		GAME_FORMAT_STANDARD=>86400 //a day
	];

	/*
	calculate chance of getting overtime increment based on the cutoff.

	if less than 15 moves it's 1; more than 90 it's 0.01

	everything in between scales up in a straight line

	TODO make it a slight concave curve

	e.g. for 5m + 1h @ 100 moves, the chances of getting into overtime are
	small so it is still a blitz game

	but 1m + 1d @ 1 moves is a correspondence game
	*/

	private static function overtime_chance($cutoff) {
		$high_chance=15;
		$low_chance=90;
		$scale=$low_chance-$high_chance;

		if($cutoff<$high_chance) {
			return 1;
		}

		else if($cutoff>$low_chance) {
			return 0.01;
		}

		else {
			return (1/$scale*($cutoff-$high_chance));
		}
	}

	public static function get_format($style, $initial, $increment=0, $overtime=false, $overtime_increment=0, $overtime_cutoff=40) {
		if(!in_array($style, [TIMING_BRONSTEIN_DELAY, TIMING_FISCHER, TIMING_FISCHER_AFTER, TIMING_SIMPLE_DELAY])) {
			$increment=0;
		}

		$total_added=$increment*self::AVG_MOVES_PER_GAME;

		if($overtime) {
			$total_added+=$overtime_increment*self::overtime_chance($overtime_cutoff);
		}

		$total_time=$initial+$total_added;

		foreach(self::$max_times as $format=>$max_time) {
			if($total_time<=$max_time) {
				return $format;
			}
		}

		return GAME_FORMAT_CORRESPONDENCE;
	}
}
?>