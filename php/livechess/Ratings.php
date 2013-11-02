<?php
require_once "php/constants.php";
require_once "php/chess/constants.php";

class Ratings {
	public static function update($white, $black, $result, $type, $variant, $format, $game=null) {
		$colours=array(WHITE, BLACK);

		$old_ratings=array(
			WHITE=>self::get_rating($white, $type, $variant, $format),
			BLACK=>self::get_rating($black, $type, $variant, $format)
		);

		$new_ratings=array();

		$users=array(
			WHITE=>$white,
			BLACK=>$black
		);

		foreach($colours as $colour) {
			$opp_colour=opp_colour($colour);
			$plr=$users[$colour];
			$opp=$users[$opp_colour];
			$score=score($result, $colour);
			$opp_rating=$old_ratings[$opp_colour];
			$rating_old=$old_ratings[$colour];
			$rating_new=$rating_old;

			if(self::is_provisional($plr, $type, $variant, $format)) {
				switch($score) {
					case SCORE_WIN: {
						$rating_new+=PROVISIONAL_RATING_POINTS_WIN;

						break;
					}

					case SCORE_LOSS: {
						$rating_new+=PROVISIONAL_RATING_POINTS_LOSS;

						break;
					}

					case SCORE_DRAW: {
						$rating_new+=PROVISIONAL_RATING_POINTS_DRAW;

						break;
					}
				}
			}

			else {
				if(!self::is_provisional($opp, $type, $variant, $format)) {
					$rating_new=self::elo($rating_old, $opp_rating, $score);
				}
			}

			$new_ratings[$colour]=$rating_new;

			if($rating_new!==$rating_old) {
				self::update_rating($plr, $type, $variant, $format, $rating_new);
			}
		}

		if($game!==null) {
			$game->white_rating_old=$old_ratings[WHITE];
			$game->white_rating_new=$new_ratings[WHITE];
			$game->black_rating_old=$old_ratings[BLACK];
			$game->black_rating_new=$new_ratings[BLACK];
		}
	}

	public static function update_rating($user, $type, $variant, $format, $rating) {
		$db=Db::getinst();

		$db->insert_or_update("ratings", array(
			"user"=>$user,
			"type"=>$type,
			"variant"=>$variant,
			"format"=>$format,
			"rating"=>$rating
		));
	}

	public static function get_rating($user, $type, $variant, $format) {
		$db=Db::getinst();
		
		return $db->cell("select get_rating('$user', '$type', '$variant', '$format')");
	}

	/*
	NOTE provisional limit is currently set at 0 to avoid provisional
	ratings.  may be better off using Glicko system instead of
	provisional/elo.
	*/

	public static function is_provisional($user, $type, $variant, $format) {
		$db=Db::getinst();

		$where="(";

		$where.="white='$user' or black='$user' and type='$type' and variant='$variant'";

		if($format!==GAME_FORMAT_OVERALL) {
			$where.=" and format='$format'";
		}

		$where.=")";

		return ($db->cell("select count(*) from games where $where")<PROVISIONAL_LIMIT);
	}

	public static function elo($p, $o, $s) {
		return round(($p+((($p>-1 && $p<2100)?32:(($p>2099 && $p<2400)?24:16))*($s-(1/(1+(pow(10, (($o-$p)/400)))))))), 4);
	}
}
?>