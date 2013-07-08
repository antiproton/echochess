<?php
require_once "dbcodes/chess.php";
require_once "base.php";
require_once "php/db.php";
require_once "php/constants.php";
require_once "php/User.php";
require_once "php/chess/Game.php";
require_once "php/chess/constants.php";
require_once "php/livechess/Ratings.php";
require_once "php/livechess/LivePiecesTaken.php";
require_once "php/livechess/LiveHistory.php";
require_once "php/livechess/Table.php";
require_once "php/livechess/Premoves.php";

/*
NOTE the standard bughouse implementation here is actually a subvariant
because usually promoted pieces go back to being pawns when they're taken,
but that's far too much of a ballache to keep track of.  since keeping
them as the promoted pieces is a known subvariant and bughouse is supposed
to be mental anyway, correcting this detail shouldn't be too high a
priority.
*/

class LiveGame extends Game {
	public $bughouse_other_game=null; //the other game on BUGHOUSE tables
	public $bughouse_pieces_available; //pieces taken in the other game on BUGHOUSE tables
	public $bughouse_is_setup=false;
	public $is_new=true;
	public $table;
	public $gid;
	public $game_id;
	public $mtime_last_update=0;

	private $is_setup=false; //if new, whether it's ready to be saved
	private $row=null; //the db row the game was loaded from
	private $table_name="games";
	private $premoves;

	private static $update_row=[
		"owner",
		"white",
		"black",
		"state",
		"mtime_start",
		"mtime_finish",
		"type",
		"variant",
		"subvariant",
		"bughouse_other_game",
		"format",
		"result",
		"result_details",
		"white_rating_old",
		"white_rating_new",
		"black_rating_old",
		"black_rating_new",
		"clock_start_index",
		"clock_start_delay",
		"timing_initial",
		"timing_increment",
		"timing_style",
		"timing_overtime",
		"timing_overtime_increment",
		"timing_overtime_cutoff",
		"event_type",
		"event",
		"round",
		"threefold_claimable",
		"fiftymove_claimable",
		"draw_offered",
		"undo_requested",
		"undo_granted",
		"rated",
		"gid",
		"game_id"
	];

	public function __construct($gid=null) {
		parent::__construct();

		if($gid!==null) {
			$this->load($gid);
		}
	}

	/*
	setup

	this must be called immediately on new games - a live game shouldn't be created without
	knowing all the information taken by these args

	until this is called, history and pieces_taken are still the non-db versions
	set up in the parent class
	*/

	public function setup(
		$owner,
		$table,
		$game_id,
		$white,
		$black,
		$rated,
		$clock_start_index,
		$clock_start_delay,
		$timing_style,
		$timing_initial,
		$timing_increment,
		$timing_overtime=false,
		$timing_overtime_increment=600,
		$timing_overtime_cutoff=null,
		$format=GAME_FORMAT_QUICK,
		$type=GAME_TYPE_STANDARD,
		$variant=VARIANT_STANDARD,
		$subvariant=SUBVARIANT_NONE
	) {
		$this->owner=$owner;
		$this->table=$table;
		$this->game_id=$game_id;
		$this->white=$white;
		$this->black=$black;
		$this->rated=$rated;
		$this->clock_start_index=$clock_start_index;
		$this->clock_start_delay=$clock_start_delay;
		$this->timing_style=$timing_style;
		$this->timing_initial=$timing_initial;
		$this->timing_increment=$timing_increment;
		$this->timing_overtime=$timing_overtime;
		$this->timing_overtime_increment=$timing_overtime_increment;
		$this->timing_overtime_cutoff=$timing_overtime_cutoff;
		$this->format=$format;
		$this->type=$type;
		$this->variant=$variant;
		$this->subvariant=$subvariant;

		$this->gid=$this->gen_gid();
		$this->livegame_setup();
		$this->is_setup=true;
	}

	private function livegame_setup() {
		$this->pieces_taken=new LivePiecesTaken($this->gid);
		$this->history=new LiveHistory($this);
		$this->premoves=new Premoves($this);
	}

	/*
	NOTE this must be called for bughouse games after setting bughouse_other_game
	to the gid of the other game
	*/

	public function bughouse_setup() {
		$this->bughouse_pieces_available=new LivePiecesTaken($this->bughouse_other_game);
		$this->bughouse_is_setup=true;
	}

	/*
	premove - if the user can make a move (it is their turn and past the clock
	start delay if any) then just do the move, otherwise add the move to the
	premoves list.

	NOTE even if a normal move is made, its premove flag is set so that it gets
	picked up by the update script and sent back to the user (otherwise they
	wouldn't see it until the opponent moved because the update script ignores
	the user's own moves)
	*/

	public function premove($user, $fs, $ts, $move_index, $promote_to=null) {
		$colours=[
			$this->white=>WHITE,
			$this->black=>BLACK
		];

		if(array_key_exists($user, $colours)) {
			if($this->position->active===$colours[$user] && mtime()>=$this->get_mtime_start_proper()) {
				return $this->move($user, $fs, $ts, $promote_to, false, true)->success;
			}

			else {
				return $this->premoves->add($user, $fs, $ts, $move_index, $promote_to);
			}
		}
	}

	public function move($user, $fs, $ts, $promote_to=QUEEN, $dryrun=false, $premove=false, $mtime=null) {
		$move=new Move();

		$colours=[
			$this->white=>WHITE,
			$this->black=>BLACK
		];

		if($dryrun) {
			$move=parent::move(null, $fs, $ts, $promote_to, $dryrun, $premove, $mtime);
		}

		else {
			if($user===null || array_key_exists($user, $colours)) {
				$colour=$colours[$user];

				if($this->position->active===$colour && mtime()>=$this->get_mtime_start_proper()) {
					$move=parent::move($user, $fs, $ts, $promote_to, $dryrun, $premove, $mtime);
				}

				else {
					$move_indexes=[0, 1];
					$move_index=$move_indexes[$colour];

					if(count($this->premoves->by_user[$user])>0) {
						$move_index=end($this->premoves->by_user[$user])->move_index+2;
					}

					else if($this->history->main_line->length>0) {
						$move_index=$this->history->main_line->last_move->move_index+2;
					}

					$move->success=$this->premove($user, $fs, $ts, $move_index, $promote_to);
				}
			}
		}

		return $move;
	}

	/*
	check_premoves

	delete any that have failed/been done, then try to apply any that are left

	this is called after each player moves (in /xhr/move.php)
	*/

	public function check_premoves() {
		if(mtime()>=$this->get_mtime_start_proper()) {
			$current_move_index=$this->get_current_move_index();
			$last_user=null;

			$colours=[
				$this->white=>WHITE,
				$this->black=>BLACK
			];

			foreach($this->premoves->by_index as $premove) {
				if($premove->move_index>$current_move_index) {
					/*
					only try one of a user's premoves at once, and
					don't try it if they're not active
					*/

					if($premove->user===$last_user || $this->position->active!==$colours[$premove->user]) {
						break;
					}

					else {
						if(!$this->move($premove->user, $premove->fs,$premove->ts, $premove->promote_to, false, true)->success) {
							$this->premoves->delete_line($premove);

							break;
						}

						$last_user=$premove->user;
					}
				}
			}

			$this->premoves->delete_old_premoves($this->get_current_move_index());
		}
	}

	/*
	detecting moving/mating possibilities is slightly different with bughouse
	*/

	public function count_legal_moves($colour) {
		$legal_moves=parent::count_legal_moves($colour);

		if($this->type===GAME_TYPE_BUGHOUSE) {
			foreach($this->bughouse_pieces_available->pieces as $piece) {
				if(colour($piece)===$colour) {
					for($sq=0; $sq<64; $sq++) {
						if($this->position->board[$sq]===SQ_EMPTY) {
							if($this->bughouse_move(null, $colour, $piece, $sq, true)->legal) {
								$legal_moves++;
							}
						}

					}
				}
			}
		}

		return $legal_moves;
	}

	public function is_mated($colour) {
		if($this->type===GAME_TYPE_BUGHOUSE) {
			$opp_colour=opp_colour($colour);
			$king=$this->position->kings[$colour];

			if(parent::is_mated($colour)) {
				/*
				it's only mate if the attacker is unblockable -
				there might not be any pieces available now to drop in
				the way, but the player is allowed to wait for their
				partner to give them one.

				more than one attacker is mate as well.
				*/

				$attackers=attackers($this->position->board, $king, $opp_colour);

				if(count($attackers)>1) { //more than one attacker
					return true;
				}

				else { //exactly one attacker
					$sq=$attackers[0];
					$type=type($this->position->board[$sq]);

					if($type===KNIGHT || $type===PAWN || count(squares_between($sq, $king))===0) {
						return true;
					}
				}
			}

			return false;
		}

		else {
			return parent::is_mated($colour);
		}
	}

	public function can_mate($colour) {
		if($this->type===GAME_TYPE_BUGHOUSE) {
			return true;
		}

		else {
			return parent::can_mate($colour);
		}
	}

	/*
	bughouse_move - called on the game that the piece is going to
	*/

	public function bughouse_move($user, $pc, $ts, $dryrun=false, $mtime=null) {
		$this->check_time();
		$move=new Move();

		$move->ts=$ts;
		$move->piece=$pc;

		if($mtime===null) {
			$move->mtime=mtime();
		}

		else {
			$move->mtime=$mtime;
		}

		if($this->type===GAME_TYPE_BUGHOUSE && $this->bughouse_is_setup) {
			$this->bughouse_pieces_available->db_load();
			if($this->bughouse_pieces_available->taken($pc) && $this->position->board[$ts]===SQ_EMPTY) {
				$pos=new Position($this->position->get_fen());
				$colour=$this->position->active;
				$opp_colour=opp_colour($colour);
				$piece=new Piece($pc);
				$move->action[$ts]=$pc;
				$move->label->to=alg_sq($ts);
				$move->label->piece=Fen::piece_char($pc);
				$move->label->disambiguation=SIGN_BUGHOUSE_DROP;

				if($piece->type===PAWN) {
					$rank=y($ts);

					if($rank>0 && $rank<7) {
						$move->valid=true;
					}
				}

				else if($piece->type!==KING) {
					$move->valid=true;
				}

				if($move->valid) {
					foreach($move->action as $sq=>$p) {
						$pos->set_square($sq, $p);
					}

					$plr_king_attackers=attackers($pos->board, $pos->kings[$colour], $opp_colour);

					if(count($plr_king_attackers)===0) {
						$move->legal=true;
					}
				}

				if($move->legal) {
					if(!$dryrun) {
						$old_pos=new Position($this->position->get_fen());
						$this->position=$pos;

						if($colour===BLACK) {
							$this->position->fullmove++;
						}

						$this->position->active=$opp_colour;

						if($piece->type===PAWN) {
							$this->position->clock=0;
						}

						else {
							$this->position->clock++;
						}

						$this->draw_offered=null;
						$this->undo_requested=false;
						$this->undo_granted=false;
						$this->position->ep=null;

						if($this->is_in_check($opp_colour)) {
							$move->label->check=SIGN_CHECK;
						}

						if($this->is_mated($opp_colour)) {
							$move->label->check=SIGN_MATE;
							$this->game_over(result_win($colour), RESULT_DETAILS_CHECKMATE);
						}

						else {
							if($this->position->clock>49) {
								$this->fiftymove_claimable=true;
							}

							$this->check_threefold();
						}

						$move->fen=$this->position->get_fen();

						/*
						if adding to the history or removing the piece from the pieces taken
						fails for some reason, set back to the original position and set
						history back if necessary
						*/

						if($this->history->move($move)) {
							$this->bughouse_pieces_available->remove($pc);
							$move->success=true;
						}

						else {
							$this->position=$old_pos;
						}
					}
				}
			}
		}

		return $move;
	}

	public function load($gid) {
		$success=false;
		$row=Db::row("select * from {$this->table_name} where gid='$gid'");

		if($row!==false) {
			$this->load_row($row);
			$success=true;
		}

		return $success;
	}

	public function load_row($row) {
		if($row["fen"]!==null) {
			$this->starting_position->set_fen($row["fen"]);
			$this->position->set_fen($row["fen"]);
		}

		foreach(self::$update_row as $field) {
			$this->$field=$row[$field];
		}

		$this->mtime_last_update=$row["mtime_last_update"];
		$this->gid=$row["gid"];
		$this->table=$row["tables"]; //separate because sql doesn't like "table"

		$this->row=$row;

		$this->livegame_setup();

		if($this->type===GAME_TYPE_BUGHOUSE) {
			$this->bughouse_setup();
		}

		$this->history->db_load();
		$this->pieces_taken->db_load();
		$this->premoves->db_load();

		if($this->history->main_line->last_move!==null) {
			$this->position->set_fen($this->history->main_line->last_move->fen);
		}

		$this->is_new=false;
	}

	public function save() {
		$success=false;

		if($this->is_new) {
			if($this->is_setup) {
				$this->start(); //the game starts as soon as it is inserted in the db
				$update_time=mtime();

				$row=[];

				foreach(self::$update_row as $field) {
					$row[$field]=$this->$field;
				}

				$row["mtime_last_update"]=$update_time;
				$row["tables"]=$this->table;

				$fen=$this->starting_position->get_fen();

				if($fen!==FEN_INITIAL) {
					$row["fen"]=$fen;
				}

				if(Db::insert($this->table_name, $row)) {
					$this->is_new=false;
					$this->row=$row;
					$this->mtime_last_update=$update_time;
					$success=true;
				}
			}
		}

		else {
			$update=[];

			foreach(self::$update_row as $field) {
				if($this->$field!==$this->row[$field]) {
					$update[$field]=$this->$field;
				}
			}

			if($this->table!==$this->row["tables"]) {
				$update["tables"]=$this->table;
			}

			if(count($update)>0) {
				$update_time=mtime();

				$update["mtime_last_update"]=$update_time;

				$success=Db::update($this->table_name, $update, array(
					"gid"=>$this->gid
				));

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

	private function gen_gid() {
		$white=$this->white;
		$black=$this->black;
		$time=dechex(time());

		return $white."_".$black."_".$time;
	}

	public function server_cancel() {
		$this->state=GAME_STATE_SERVER_CANCEL;
		$this->table_game_over();
	}

	public function user_cancel() {
		$this->state=GAME_STATE_USER_CANCEL;
		$this->table_game_over();
	}

	private function table_game_over() {
		$table=new Table($this->table);
		$table->game_over($this);
		$table->save();
	}

	public function bughouse_other_game_over($result) {
		$this->game_over(opp_result($result), RESULT_DETAILS_BUGHOUSE_OTHER_GAME);
	}

	protected function game_over($result, $details) {
		parent::game_over($result, $details);

		$this->premoves->delete_all();
		$this->pieces_taken->clear();

		//the game that gets ended in one of the normal ways (checkmate etc) calls bughouse_other_game_over
		//on the other game.  the other game receives the result of this game and flips it (teammates are on
		//opposite colours, so the results of the two games will always be opposite)

		if($this->type===GAME_TYPE_BUGHOUSE && $details!==RESULT_DETAILS_BUGHOUSE_OTHER_GAME) {
			$other_game=new LiveGame($this->bughouse_other_game);
			$other_game->bughouse_other_game_over($result);
			$other_game->save();
		}

		if($this->rated && $details!==RESULT_DETAILS_BUGHOUSE_OTHER_GAME) {
			Ratings::update($this->white, $this->black, $result, $this->type, $this->variant, $this->format, $this);
			Ratings::update($this->white, $this->black, $result, $this->type, $this->variant, GAME_FORMAT_OVERALL);
		}

		$this->table_game_over();
	}
}
?>