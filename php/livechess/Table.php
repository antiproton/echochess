<?php
require_once "base.php";
require_once "dbcodes/chess.php";
require_once "Data.php";
require_once "Db.php";
require_once "php/constants.php";
require_once "php/chess/constants.php";
require_once "php/chess/Timing.php";
require_once "php/livechess/Seat.php";
require_once "php/livechess/LiveGame.php";
require_once "php/livechess/Invites.php";
require_once "php/Messages.php";

class Table{
	public $owner;
	public $owner_rating; //used in quick challenges to avoid looking up the rating every time
	public $accept_rating_min=null; //quick challenges
	public $accept_rating_max=null;
	public $choose_colour=false;
	public $challenge_colour=WHITE; //quick challenges
	public $challenge_type=CHALLENGE_TYPE_QUICK;
	public $challenge_to;
	public $challenge_accepted=false;
	public $challenge_declined=false;
	public $owner_rematch_ready=false;
	public $guest_rematch_ready=false;
	public $type=GAME_TYPE_STANDARD;
	public $variant=VARIANT_STANDARD;
	public $subvariant=SUBVARIANT_NONE;
	public $last_starting_fen=null;
	public $score_owner=0; //quick challenges
	public $score_guest=0;
	public $event_type;
	public $event=null;
	public $fen=null;
	public $timing_initial=600;
	public $timing_increment=0;
	public $timing_style=TIMING_SUDDEN_DEATH;
	public $timing_overtime=false;
	public $timing_overtime_cutoff=40;
	public $timing_overtime_increment=600;
	public $format=GAME_FORMAT_QUICK;
	public $alternate_colours=true;
	public $chess960_randomise_mode=CHESS960_RANDOMISE_EVERY_OTHER;
	public $permissions_watch=PERM_LEVEL_ANYONE;
	public $permissions_play=PERM_LEVEL_ANYONE;
	public $rated=true;
	public $game_in_progress=false;
	public $games_played=0;
	public $id;
	public $is_new=true;
	public $games=array();
	public $seats=array();
	public $mtime_created=null;
	public $mtime_last_update=0;

	private $is_setup=false;
	private $row=null;
	private $table_name="tables";
	private $db;

	private static $update_row=[
		"owner",
		"owner_rating",
		"accept_rating_min",
		"accept_rating_max",
		"choose_colour",
		"challenge_colour",
		"challenge_accepted",
		"challenge_declined",
		"challenge_type",
		"challenge_to",
		"owner_rematch_ready",
		"guest_rematch_ready",
		"type",
		"variant",
		"subvariant",
		"last_starting_fen",
		"score_owner",
		"score_guest",
		"event_type",
		"event",
		"fen",
		"timing_initial",
		"timing_increment",
		"timing_style",
		"timing_overtime",
		"timing_overtime_cutoff",
		"timing_overtime_increment",
		"format",
		"alternate_colours",
		"chess960_randomise_mode",
		"permissions_watch",
		"permissions_play",
		"rated",
		"game_in_progress",
		"games_played",
		"mtime_created"
	];

	public static $max_game_id=[
		GAME_TYPE_STANDARD=>0,
		GAME_TYPE_BUGHOUSE=>1
	];

	public function __construct($id=null) {
		$this->db=Db::getinst();

		if($id!==null) {
			$this->load($id);
		}
	}

	public function setup($owner, $type, $rated, $event_type, $challenge_type, $choose_colour=false, $challenge_colour=WHITE, $challenge_to=null, $fen=null) {
		$success=true;

		$this->owner=$owner;
		$this->type=$type;
		$this->rated=$rated;
		$this->event_type=$event_type;
		$this->challenge_type=$challenge_type;
		$this->choose_colour=$choose_colour;
		$this->challenge_colour=$challenge_colour; //if chosen, what the owner wants to play as
		$this->challenge_to=$challenge_to;

		if($fen!==null) {
			if(Fen::is_valid($fen)) {
				$this->fen=$fen;
			}

			else {
				$success=false;
			}
		}

		if($challenge_type===CHALLENGE_TYPE_QUICK) {
			$this->type=GAME_TYPE_STANDARD;
			$this->timing_style=TIMING_FISCHER_AFTER;

			if($choose_colour) {
				$this->rated=false;
			}
		}

		if($success) {
			$this->is_setup=true;
		}

		return $success;
	}

	/*
	quick challenges - challenge_accept, rematch, rematch_cancel, rematch_decline

	quick challenge functionality is built on top of custom table functionality
	(e.g. offering a rematch just sets "ready" and sends the opponent a message)
	*/

	/*
	challenge_accept - seat players and start game
	*/

	public function challenge_accept($user) {
		if($this->challenge_type===CHALLENGE_TYPE_QUICK && !$this->challenge_accepted) {
			if($this->choose_colour) {
				$this->sit($this->owner, $this->challenge_colour);
				$this->sit($user, opp_colour($this->challenge_colour));
			}

			else {
				/*
				fair colour assignment

				check the ratios of each player playing as white to black,
				then seat the one with the lowest ratio as white
				*/

				$players=[
					"owner"=>$this->owner,
					"guest"=>$user
				];

				$ratios=[];

				foreach($players as $key=>$plr) { //a minimum value of 1 is used to avoid div by 0
					$ratio[$key]=$this->db->cell("
						select round(quick_challenges_as_white/greatest(1, quick_challenges_as_black), 4)
						from users
						where username='$plr'
					");
				}

				if($ratio["owner"]>$ratio["guest"]) {
					$this->sit($this->owner, BLACK);
					$this->sit($user, WHITE);
				}

				else { //equal ratio goes in favour of owner
					$this->sit($this->owner, WHITE);
					$this->sit($user, BLACK);
				}
			}

			$this->challenge_accepted=true;
			$this->start_game();
			$this->save();
		}
	}

	public function challenge_decline($user) {
		if($this->challenge_type===CHALLENGE_TYPE_QUICK && $this->challenge_accepted===false && $this->challenge_to===$user) {
			$this->challenge_declined=true;
		}
	}

	/*
	accept or offer a rematch

	the two are basically the same, only difference is that a message is sent
	to the opponent if they're not ready yet
	*/

	public function rematch($user) {
		if($this->challenge_type===CHALLENGE_TYPE_QUICK && !$this->game_in_progress && $this->is_seated($user)) {
			if(!$this->is_ready($user)) {
				if($user===$this->owner) {
					$this->owner_rematch_ready=true;
				}

				else {
					$this->guest_rematch_ready=true;
				}

				$this->ready($user, true);
			}
		}
	}

	public function rematch_decline($user) {
		if($this->challenge_type===CHALLENGE_TYPE_QUICK && !$this->game_in_progress && $this->is_seated($user)) {
			$this->owner_rematch_ready=false;
			$this->guest_rematch_ready=false;
			$this->unready_all_seats();

			$opp_seat=null;

			foreach($this->seats as $seat) {
				if($seat->type===SEAT_TYPE_PLAYER && $seat->user!==$user) {
					$opp_seat=$seat;

					break;
				}
			}

			if($opp_seat!==null && !$opp_seat->ready) {
				Messages::send($user, $opp_seat->user, MESSAGE_TYPE_REMATCH_DECLINE, $this->id);
			}
		}
	}

	public function rematch_cancel($user) {
		if($this->challenge_type===CHALLENGE_TYPE_QUICK && !$this->game_in_progress && $this->is_seated($user)) {
			$this->owner_rematch_ready=false;
			$this->guest_rematch_ready=false;
			$this->seats[$user]->ready=false;
			$this->seats[$user]->save();

			$opp_seat=null;

			foreach($this->seats as $seat) {
				if($seat->type===SEAT_TYPE_PLAYER && $seat->user!==$user) {
					$opp_seat=$seat;

					break;
				}
			}

			if($opp_seat!==null) {
				Messages::send($user, $opp_seat->user, MESSAGE_TYPE_REMATCH_CANCEL, $this->id);
			}
		}
	}

	/*
	watch - this currently isn't being used.
	*/

	public function watch($user) {
		$success=false;
		$allowed=true;
		$by_invite=false;

		switch($this->permissions_watch) {
			case PERM_LEVEL_INVITED: {
				$allowed=Invites::is_invited($this->id, $user);
				$by_invite=true;

				break;
			}

			case PERM_LEVEL_FRIENDS: {
				$allowed=User::friends($user, $this->owner);

				break;
			}
		}

		if($allowed) {
			$seat=new Seat;
			$seat->setup($user, $this->id, SEAT_TYPE_SPECTATOR);

			if($seat->save()) {
				$this->seats[$seat->user]=$seat;
				$success=true;
			}

			if($by_invite) {
				Invites::remove($this->id, $user);
			}
		}

		return $success;
	}

	public function leave($user) {
		$success=false;

		if(array_key_exists($user, $this->seats)) {
			$seat=$this->seats[$user];

			if($seat->type===SEAT_TYPE_PLAYER) {
				$this->games_played=0;

				if($this->game_in_progress) {
					$game=new LiveGame($seat->gid);
					$game->resign($seat->colour);
					$game->save();
				}
			}

			$success=$seat->leave();

			unset($this->seats[$user]);
		}

		if(count($this->seats)===0) {
			$this->delete(); //NOTE if someone has the table open now, they've got problems
		}

		return $success;
	}

	public function ready($user, $ready) {
		$success=false;

		if(array_key_exists($user, $this->seats)) {
			$seat=$this->seats[$user];
			$seat->ready=$ready;

			if($seat->save()) {
				if($ready) {
					$this->check_ready();
				}

				$success=true;
			}
		}

		return $success;
	}

	public function sit($user, $colour, $game_id=0) {
		$success=false;

		if(!$this->game_in_progress) {
			$allowed=true;
			$has_invite=Invites::is_invited($this->id, $user);

			switch($this->permissions_play) {
				case PERM_LEVEL_INVITED: {
					$allowed=$has_invite;

					break;
				}

				case PERM_LEVEL_FRIENDS: {
					$allowed=User::friends($user, $this->owner);

					break;
				}
			}

			if($allowed && $this->seat_available($colour, $game_id)) {
				if(array_key_exists($user, $this->seats)) {
					$this->seats[$user]->colour=$colour;
					$this->seats[$user]->game_id=$game_id;
					$this->seats[$user]->type=SEAT_TYPE_PLAYER;
					$success=$this->seats[$user]->save();
				}

				else {
					$seat=new Seat();
					$seat->setup($user, $this->id, SEAT_TYPE_PLAYER, $colour, $game_id);

					if($this->challenge_type===CHALLENGE_TYPE_QUICK) {
						$seat->ready=true;
					}

					if($seat->save()) {
						if($this->type!==GAME_TYPE_BUGHOUSE && $this->challenge_type!==CHALLENGE_TYPE_QUICK) {
							$this->unready_all_seats();
						}

						$this->seats[$user]=$seat;
						$success=true;
					}

					if($has_invite) {
						Invites::remove($this->id, $user);
					}
				}
			}
		}

		return $success;
	}

	public function seat_available($colour, $game_id=0) {
		$game_id=min($game_id, self::$max_game_id[$this->type]);

		foreach($this->seats as $seat) {
			if($seat->type===SEAT_TYPE_PLAYER && $seat->colour===$colour && $seat->game_id===$game_id) {
				return false;
			}
		}

		return true;
	}

	public function is_seated($user) {
		return (array_key_exists($user, $this->seats) && $this->seats[$user]->type===SEAT_TYPE_PLAYER);
	}

	public function stand($user) {
		$success=false;

		if(array_key_exists($user, $this->seats)) {
			$seat=$this->seats[$user];

			if($seat->stand() && $seat->save()) {
				$success=true;

				if($this->type!==GAME_TYPE_BUGHOUSE && $this->challenge_type!==CHALLENGE_TYPE_QUICK) {
					$this->unready_all_seats();
				}

				if($this->seats[$user]->type===SEAT_TYPE_PLAYER) {
					$this->games_played=0;
				}
			}
		}

		return $success;
	}

	/*
	if all the players are ready (either by offering/accepting
	a rematch or by clicking the ready button), start the game
	*/

	public function check_ready() {
		$ready=0;

		switch($this->type) {
			case GAME_TYPE_BUGHOUSE: {
				$needed=4;

				break;
			}

			default: {
				$needed=2;

				break;
			}
		}

		foreach($this->seats as $seat) {
			if($seat->type===SEAT_TYPE_PLAYER && $seat->ready) {
				$ready++;
			}
		}

		if($ready===$needed) {
			$this->start_game();
		}
	}

	public function is_ready($user) {
		if($this->is_seated($user)) {
			return $this->seats[$user]->ready;
		}

		return false;
	}

	public function load($id) {
		$success=false;
		$row=$this->db->row("select * from {$this->table_name} where id='$id'");

		if($row!==false) {
			$this->load_row($row);
			$success=true;
		}

		return $success;
	}

	public function load_row(&$row) {
		$this->row=$row;

		$this->update($row);

		$this->mtime_last_update=$row["mtime_last_update"];
		$this->id=$row["id"];
		$this->is_new=false;

		$this->load_seats();
	}

	public function update($row) {
		foreach(self::$update_row as $field) {
			if(isset($row[$field])) {
				$this->$field=$row[$field];
			}
		}

		$this->update_timing_format();
	}

	public function save() {
		$success=false;

		if($this->is_new) {
			if($this->is_setup) {
				$update_time=mtime();
				$row=[];

				$this->mtime_created=$update_time;
				$this->update_timing_format();

				foreach(self::$update_row as $field) {
					$row[$field]=$this->$field;
				}

				$row["mtime_last_update"]=$update_time;

				$id=$this->db->insert($this->table_name, $row);

				if($id!==false) {
					$this->id=$id;
					$this->row=$row;
					$this->is_new=false;
					$this->mtime_last_update=$update_time;
					$success=true;
				}
			}
		}

		else {
			$update=[];

			$this->update_timing_format();

			if($this->variant!==$this->row["variant"]) {
				$this->games_played=0; //so that board is always randomised after first switching to chess960
			}

			foreach(self::$update_row as $field) {
				if($this->$field!==$this->row[$field]) {
					$update[$field]=$this->$field;
				}
			}

			if(count($update)>0) {
				$update_time=mtime();
				$update["mtime_last_update"]=$update_time;

				$success=$this->db->update($this->table_name, $update, [
					"id"=>$this->id
				]);

				if($success) {
					$this->mtime_last_update=$update_time;
				}
			}

			else {
				$success=true;
			}
		}

		return $success;
	}

	public function delete() {
		if(!$this->is_new) {
			$this->db->remove($this->table_name, [
				"id"=>$this->id
			]);
		}
	}

	private function update_timing_format() {
		$this->format=Timing::get_format($this->timing_style, $this->timing_initial, $this->timing_increment);
	}

	private function start_game() {
		$success=false;
		$players=[];
		$colours=[WHITE, BLACK];

		switch($this->type) {
			case GAME_TYPE_BUGHOUSE: {
				$games=2;

				break;
			}

			default: {
				$games=1;

				break;
			}
		}

		//switch colours

		if($this->games_played>0 && $this->alternate_colours) {
			foreach($this->seats as $seat) {
				$seat->colour=opp_colour($seat->colour);
				$seat->save();
			}
		}

		foreach($this->seats as $seat) {
			if($seat->type===SEAT_TYPE_PLAYER) {
				$id=$seat->game_id;

				if(!isset($players[$id])) {
					$players[$id]=[];
				}

				$players[$id][$seat->colour]=$seat->user;
			}
		}

		/*
		NOTE the clock start delay is supposed to be used only to give white
		a chance to premove at the start of tournament games.  Using a delay
		with clock start indexes other than -1 doesn't make any sense and may
		produce weird results.
		*/

		$clock_start_index=1; //white's clock starts after black's first move
		$clock_start_delay=0;

		if($this->type===GAME_TYPE_BUGHOUSE) {
			$clock_start_index=-1; //white's clock starts immediately
			$clock_start_delay=BUGHOUSE_CLOCK_START_DELAY;
		}

		/*
		TODO for tournaments, start white's clock on first move but after a delay
		NOTE the tournament managing code will need to get the games to check their premoves
		as soon as the clock start delay is up.
		*/

		$format=Timing::get_format($this->timing_style, $this->timing_initial, $this->timing_increment);
		$last_game=null; //for pointing the bughouse games at eachother

		for($id=0; $id<$games; $id++) {
			$game=new LiveGame();

			$game->setup(
				$this->owner,
				$this->id,
				$id,
				$players[$id][WHITE],
				$players[$id][BLACK],
				$this->rated,
				$clock_start_index,
				$clock_start_delay,
				$this->timing_style,
				$this->timing_initial,
				$this->timing_increment,
				$this->timing_overtime,
				$this->timing_overtime_increment,
				$this->timing_overtime_cutoff,
				$format,
				$this->type,
				$this->variant,
				$this->subvariant
			);

			if($this->variant===VARIANT_960) {
				//the game handles the subvariant (both sides random if it's DOUBLE)

				if($this->games_played===0) {
					$game->chess960_randomise();
				}

				else {
					if($this->chess960_randomise_mode===CHESS960_RANDOMISE_EVERY_TIME) {
						$game->chess960_randomise();
					}

					else {
						if($this->chess960_randomise_mode===CHESS960_RANDOMISE_EVERY_OTHER && $this->games_played%2===0) {
							$game->chess960_randomise();
						}

						else { //ONCE or haven't had 2 games with current position yet
							if($this->last_starting_fen!==null) {
								$game->set_starting_fen($this->last_starting_fen);
							}
						}
					}
				}
			}

			$this->games[$game->gid]=$game;

			foreach($colours as $colour) {
				$seat=$this->seats[$players[$id][$colour]];
				$seat->gid=$game->gid;
				$seat->save();
			}

			if($this->type===GAME_TYPE_BUGHOUSE) {
				if($last_game!==null) {
					$last_game->bughouse_other_game=$game->gid;
					$game->bughouse_other_game=$last_game->gid;
					$last_game->bughouse_setup();
					$game->bughouse_setup();

					$success=($last_game->save() && $game->save());
				}

				$last_game=$game;
			}

			else {
				$success=$game->save();
			}
		}

		if($success) {
			$this->game_in_progress=true;
			$this->owner_rematch_ready=false;
			$this->guest_rematch_ready=false;
			$success=$this->save();

			$this->db->remove("live_table_quit", [
				"tables"=>$this->id
			]);
		}
	}

	public function game_over($game) {
		if($game->state===GAME_STATE_FINISHED) {
			if($this->challenge_type===CHALLENGE_TYPE_QUICK) {
				$white=new User($game->white);
				$black=new User($game->black);
				$white->quick_challenges_as_white++;
				$black->quick_challenges_as_black++;
				$white->save();
				$black->save();

				$players=[
					WHITE=>$game->white,
					BLACK=>$game->black
				];

				foreach($players as $colour=>$username) {
					$score=score($game->result, $colour);

					if($this->owner===$username) {
						$this->score_owner+=$score;
					}

					else {
						$this->score_guest+=$score;
					}
				}
			}

			if($game->result_details!==RESULT_DETAILS_BUGHOUSE_OTHER_GAME) {
				$this->last_starting_fen=$game->starting_position->get_fen();
				$this->games_played++; //only increment once for bughouse games

				$this->db->remove("live_table_quit", [
					"tables"=>$this->id
				]);
			}
		}

		$this->owner_rematch_ready=false;
		$this->guest_rematch_ready=false;
		$this->game_in_progress=false;
		$this->unready_all_seats();
	}

	private function unready_all_seats() {
		foreach($this->seats as $seat) {
			if($seat->type===SEAT_TYPE_PLAYER) {
				$seat->ready=false;
				$seat->save();
			}
		}
	}

	private function load_seats() {
		if(!$this->is_new) {
			$table=$this->db->table("select * from seats where tables='{$this->id}'");

			foreach($table as $row) {
				$seat=new Seat();
				$seat->load_row($row);
				$this->seats[$seat->user]=$seat;
			}
		}
	}

	public static function cancel_open_challenges($user) {
		return $this->db->remove("tables", [
			"owner"=>$user,
			"challenge_type"=>CHALLENGE_TYPE_QUICK,
			"challenge_accepted"=>false
		]);
	}

	public function load_games() { //NOTE not sure if this will ever be used, might be useful for debugging though
		if(!$this->is_new) {
			$table=$this->db->table("select * from games where tables='{$this->id}'");

			foreach($table as $row) {
				$game=new LiveGame();
				$game->load_row($row);
				$game->games[$game->gid]=$game;
			}
		}
	}
}
?>