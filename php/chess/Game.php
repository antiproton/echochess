<?php
require_once "base.php";
require_once "php/chess/constants.php";
require_once "php/chess/util.php";
require_once "dbcodes/chess.php";
require_once "php/chess/Fen.php";
require_once "php/chess/Piece.php";
require_once "php/chess/Position.php";
require_once "php/chess/CastlingDetails.php";
require_once "php/chess/Move.php";
require_once "php/chess/History.php";
require_once "php/chess/PiecesTaken.php";

class Game {
	public $owner;
	public $white;
	public $black;
	public $state=GAME_STATE_PREGAME;
	public $mtime_start;
	public $mtime_finish;
	public $type=GAME_TYPE_STANDARD; //STANDARD, BUGHOUSE
	public $variant=VARIANT_STANDARD; //STANDARD, 960
	public $subvariant=SUBVARIANT_NONE;
	public $format; //CORRESPONDENCE, BLITZ, BULLET, STANDARD
	public $result;
	public $result_details;
	public $white_rating_old;
	public $white_rating_new;
	public $black_rating_old;
	public $black_rating_new;
	public $clock_start_index=1; //1 - white's clock starts after black's first move (use -1 to start white's clock immediately)
	public $clock_start_delay=0; //initial delay for clocks (useful for letting white premove their first move if the clock_start_index is 0)
	public $timing_initial;
	public $timing_increment;
	public $timing_style;
	public $timing_overtime=false;
	public $timing_overtime_increment=600;
	public $timing_overtime_cutoff=40;
	public $event_type=EVENT_TYPE_CASUAL;
	public $event;
	public $round;
	public $threefold_claimable=false;
	public $fiftymove_claimable=false;
	public $draw_offered=null;
	public $undo_requested=false;
	public $undo_granted=false;
	public $rated;
	public $history;
	public $position;
	public $pieces_taken;
	public $starting_position;

	public $time=[
		WHITE=>null,
		BLACK=>null
	];

	public function __construct() {
		$this->position=new Position();
		$this->starting_position=new Position();
		$this->history=new History($this);
		$this->pieces_taken=new PiecesTaken();
	}

	public function set_starting_fen($fen) {
		if($this->state===GAME_STATE_PREGAME) {
			$this->starting_position->set_fen($fen);
			$this->position->set_fen($fen);
		}
	}

	public function chess960_randomise() {
		$colours=array(WHITE, BLACK);
		$backrank=array();

		$backrank[WHITE]=$this->random_backrank();

		if($this->subvariant===SUBVARIANT_DOUBLE) {
			$backrank[BLACK]=$this->random_backrank();
		}

		else {
			$backrank[BLACK]=$backrank[WHITE];
		}

		foreach($colours as $colour) {
			$this->set_backrank($colour, $backrank[$colour]);
		}

		$this->reset_castling_privs();
	}

	/*
	generate a Chess960 backrank as an array of pieces
	*/

	private function random_backrank() {
		//initialise array of 8 empty squares

		$backrank=[];

		for($i=0; $i<8; $i++) {
			$backrank[]=SQ_EMPTY;
		}

		//place king and rook with king between rooks

		$rook_1=mt_rand(0, 5);
		$backrank[$rook_1]=ROOK;
		$king=mt_rand($rook_1+1, 6);
		$backrank[$king]=KING;
		$rook_2=mt_rand($king+1, 7);
		$backrank[$rook_2]=ROOK;

		//put a bishop on an empty square of each colour

		$squares=[];

		for($i=0; $i<8; $i++) {
			$squares[]=$i;
		}

		shuffle($squares);

		$colours=[0, 1];

		foreach($colours as $n) {
			foreach($squares as $sq) {
				if($backrank[$sq]===SQ_EMPTY && $sq%2===$n) {
					$backrank[$sq]=BISHOP;

					break;
				}
			}
		}

		//place the knights and queen randomly into the remaining empty squares

		$remaining_pieces=[KNIGHT, KNIGHT, QUEEN];

		shuffle($remaining_pieces);

		foreach($backrank as $sq=>$pc) {
			if($pc===SQ_EMPTY) {
				$backrank[$sq]=array_pop($remaining_pieces);
			}
		}

		return $backrank;
	}

	/*
	NOTE after doing this, the castling privileges should be updated
	*/

	private function set_backrank($colour, $pieces) {
		$starting_square=[
			WHITE=>0,
			BLACK=>56
		];

		for($sq=$starting_square[$colour]; $sq<$starting_square[$colour]+8; $sq++) {
			$this->starting_position->board[$sq]=piece(array_shift($pieces), $colour);
		}
	}

	private function reset_castling_privs() {
		$colours=[WHITE, BLACK];

		$ranks=[
			WHITE=>0,
			BLACK=>7
		];

		/*
		for each side or file, set castling to false.  then if there is a rook
		in the right place, set it to true.

		assumes that it is the right colour and that there are exactly two rooks
		on the back rank
		*/

		foreach($colours as $colour) {
			switch($this->variant) {
				case VARIANT_960: {
					for($file=0; $file<8; $file++) {
						$sq=coords_to_sq(array($file, $ranks[$colour]));
						$this->position->castling->set($colour, $file, false, CastlingPrivileges::MODE_FILE);

						if(type($this->position->board[$sq])===ROOK) {
							$this->position->castling->set($colour, $file, true, CastlingPrivileges::MODE_FILE);
						}
					}

					break;
				}

				case VARIANT_STANDARD: {
					$sides=array(KINGSIDE, QUEENSIDE);

					foreach($sides as $side) {
						$sq=coords_to_sq(array(CastlingPrivileges::$side_to_file[$side], $ranks[$colour]));
						$this->position->castling->set($colour, $side, false, CastlingPrivileges::MODE_SIDE);

						if(type($this->position->board[$sq])===ROOK) {
							$this->position->castling->set($colour, $side, true, CastlingPrivileges::MODE_SIDE);
						}
					}

					break;
				}
			}
		}
	}

	public function get_starting_fen() {
		return $this->starting_position->get_fen();
	}

	/*
	start - set to in progress, set mtime_start
	*/

	public function start() {
		$this->state=GAME_STATE_IN_PROGRESS;
		$this->mtime_start=mtime();
	}

	/*
	timeout - check whether $colour has timed out and end the game if necessary
	*/

	protected function timeout($time, $colour) {
		if($time<1) {
			$opp_colour=opp_colour($colour);
			$result=$this->can_mate($opp_colour)?result_win($opp_colour):RESULT_DRAW;
			$this->game_over($result, RESULT_DETAILS_TIMEOUT);

			return true;
		}

		return false;
	}

	/*
	get the mtime of the game starting properly.  for tournament games where there is
	a delay before any moves can be made, this will return the mtime after the delay.
	for normal games it is the same as mtime_start.
	*/

	protected function get_mtime_start_proper() {
		return $this->mtime_start+($this->clock_start_delay*MSEC_PER_SEC);
	}

	/*
	check_time - go through the moves calculating the players' remaining
	time based on the timing style, start time, move times and current time.

	returns true if someone has timed out.

	this is called check_time and the clientside one is called calculate_time
	because that's for displaying an estimation of the current time situation
	and this is for checking whether someone's clock has run out.

	the time won't always be checked exactly on timeout, so this also modifies
	->time so that the amount of time since timeout can be checked to adjust
	the finish time in game_over (times can be negative)
	*/

	public function check_time() {
		$timeout=false;

		$now=mtime();
		$mtime_initial=$this->timing_initial*MSEC_PER_SEC;
		$increment=$this->timing_increment*MSEC_PER_SEC;
		$clock_start_delay=$this->clock_start_delay*MSEC_PER_SEC;
		$first_timed_move_index=$this->clock_start_index+1;
		$last_move_index=count($this->history->main_line->line)-1;

		$time=[
			WHITE=>$mtime_initial,
			BLACK=>$mtime_initial
		];

		if($this->timing_style!==TIMING_NONE) {
			$opp_colour=$this->starting_position->active;
			$colour=opp_colour($opp_colour);

			if($this->clock_start_index===-1 || $last_move_index<0) {
				$offset=$this->mtime_start+$clock_start_delay;
			}

			else if($last_move_index>=$this->clock_start_index) {
				$offset=$this->history->main_line->line[$this->clock_start_index]->mtime+$clock_start_delay;
			}

			for($move_index=$first_timed_move_index; $move_index<count($this->history->main_line->line); $move_index++) {
				$move=$this->history->main_line->line[$move_index];
				$colour=$move->colour;
				$opp_colour=opp_colour($colour);
				$thinking_time=$move->mtime-$offset;

				switch($this->timing_style) {
					case TIMING_FISCHER: {
						$time[$colour]+=$increment;
						$time[$colour]-=$thinking_time;
						$timeout=$this->timeout($time[$colour], $colour);

						break;
					}

					case TIMING_FISCHER_AFTER: {
						$time[$colour]-=$thinking_time;
						$timeout=$this->timeout($time[$colour], $colour);
						$time[$colour]+=$increment;

						break;
					}

					case TIMING_BRONSTEIN_DELAY: {
						$time[$colour]-=$thinking_time;
						$timeout=$this->timeout($time[$colour], $colour);
						$time[$colour]+=min($thinking_time, $increment);

						break;
					}

					case TIMING_SIMPLE_DELAY: {
						$time[$colour]-=max(0, $thinking_time-$increment);
						$timeout=$this->timeout($time[$colour], $colour);

						break;
					}

					case TIMING_SUDDEN_DEATH: {
						$time[$colour]-=$thinking_time;
						$timeout=$this->timeout($time[$colour], $colour);

						break;
					}

					case TIMING_HOURGLASS: {
						$time[$colour]-=$thinking_time;
						$time[$opp_colour]+=$thinking_time;
						$timeout=$this->timeout($time[$colour], $colour);

						break;
					}

					case TIMING_PER_MOVE: {
						$time[$colour]-=$thinking_time;
						$timeout=$this->timeout($time[$colour], $colour);
						$time[$colour]=$mtime_initial;

						break;
					}
				}

				if($this->timing_overtime && fullmove($move_index)===$this->timing_overtime_cutoff) {
					$time[$colour]+=($this->timing_overtime_increment*MSEC_PER_SEC);
				}

				$offset=$move->mtime;
			}

			/*
			now do the currently active player (opponent of the last move made,
			so use opp_colour)
			*/

			if($last_move_index>$this->clock_start_index-1) {
				$delay=0;

				if($this->timing_style===TIMING_SIMPLE_DELAY) { //bronstein delay starts counting immediately
					$delay=$increment;
				}

				$thinking_time=$now-$offset;
				$time[$opp_colour]-=max(0, $thinking_time-$delay);

				if($this->timing_style===TIMING_HOURGLASS) {
					$time[$colour]+=$thinking_time;
				}

				if($this->timing_style===TIMING_FISCHER) {
					$time[$opp_colour]+=$increment;
				}

				$timeout=$this->timeout($time[$opp_colour], $opp_colour);
			}
		}

		$this->time=$time;

		return $timeout;
	}

	/*
	threefold repetition check.
	*/

	protected function check_threefold() {
		$fen=$this->position->get_fen();
		$fen_arr=Fen::fen_to_array($fen);
		$limit=3;
		$n=0;

		if($fen===$this->starting_position->get_fen()) {
			$limit--; //starting position isn't recorded as a move, so n will only be 2 if it has appeared 3 times in total
		}

		$check_fields=[
			FEN_FIELD_POSITION,
			FEN_FIELD_ACTIVE,
			FEN_FIELD_CASTLING,
			FEN_FIELD_EP
		];

		foreach($this->history->main_line->line as $move) {
			$move_fen=Fen::fen_to_array($move->fen);
			$match=true;

			foreach($check_fields as $field) {
				if($move_fen[$field]!==$fen_arr[$field]) {
					$match=false;

					break;
				}
			}

			if($match) {
				$n++;
			}
		}

		$this->threefold_claimable=($n>=$limit);
	}

	/*
	count_legal_moves - count the moves available to $colour.
	*/

	public function count_legal_moves($colour) {
		$legal_moves=0;

		foreach($this->position->board as $sq=>$piece) {
			if($piece!==SQ_EMPTY && colour($piece)===$colour) {
				$available=moves_available(type($piece), $sq, $colour);

				foreach($available as $n) {
					if($this->move(null, $sq, $n, QUEEN, true)->legal) {
						$legal_moves++;
					}
				}
			}
		}

		return $legal_moves;
	}

	/*
	move

	from, to and optional piece type for promotion moves.  also dryrun
	(to check a move for legality without applying it to the game).

	returns true on success or false on failure

	if premove is true, the mtime of the move will be set at either the
	game's mtime_start (if it is the first move) or the mtime of the last
	move, to make sure players don't lose any time for premoves due to
	processing time.

	NOTE because there isn't a premove feature on non-live games, moves
	tried during the clock start delay will just fail.

	NOTE user can be a username (and one is usually passed for live games)
	but currently it isn't used here and can be null.
	*/

	public function move($user, $fs, $ts, $promote_to=QUEEN, $dryrun=false, $premove=false, $mtime=null) {
		$this->check_time();

		$move=new Move();

		if($premove) { //premove time is artificially set to be 0ms after last move
			if(count($this->history->main_line->line)>0) {
				$move->mtime=$this->history->main_line->last_move->mtime;
			}

			else {
				$move->mtime=$this->get_mtime_start_proper();
			}
		}

		else {
			if($mtime===null) {
				$move->mtime=mtime();
			}

			else {
				$move->mtime=$mtime;
			}
		}

		if($this->state===GAME_STATE_IN_PROGRESS && mtime()>=$this->get_mtime_start_proper()) {
			$colour=$this->position->active;
			$piece=new Piece($this->position->board[$fs]);
			$moveto=new Piece($this->position->board[$ts]);

			if(on_board($fs) && on_board($ts) && $piece->type!==SQ_EMPTY && $piece->colour===$colour) {
				$pos=new Position($this->position->get_fen());
				$move->fs=$fs;
				$move->ts=$ts;
				$move->promote_to=$promote_to;
				$move->premove=$premove;
				$move->label->piece=Fen::piece_char(piece($piece->type, WHITE));
				$move->label->to=alg_sq($ts);
				$move->action[$fs]=SQ_EMPTY;
				$move->action[$ts]=$this->position->board[$fs];
				$fc=sq_to_coords($fs);
				$tc=sq_to_coords($ts);
				$relfs=rel_sq_no($fs, $colour);
				$relts=rel_sq_no($ts, $colour);
				$opp_colour=opp_colour($colour);
				$unobstructed=(!blocked($this->position->board, $fs, $ts) && ($moveto->type===SQ_EMPTY || $moveto->colour!==$colour));

				//disambiguation for the move label - specify file and/or rank if necessary
				//not applicable to pawns or kings

				if($piece->type!==PAWN && $piece->type!==KING) {
					$move->label->disambiguation=disambiguate($this->position->board, $piece->type, $colour, $fs, $ts);
				}

				//capture

				if($moveto->colour===$opp_colour && $moveto->type!==SQ_EMPTY) {
					$move->label->sign=SIGN_CAPTURE;
					$move->capture=$this->position->board[$ts];
				}

				//regular/special move

				/*
				the following if..else if.. consists of (if regular move, else if
				(all the pawn moves), else if castling), and is the final step in
				determining if the move is valid.

				regular_move checks knights, bishops, rooks, queens and regular
				king moves.  all pawn moves are checked specifically.

				rook and king moves can be blocked or landing on own pieces to
				indicate castling in chess960, so that check can't be done
				further up anymore.
				*/

				if(regular_move($piece->type, $fc, $tc) && $unobstructed) {
					$move->valid=true;
				}

				else if($piece->type===PAWN && $unobstructed) {
					$capturing=pawn_move_capture($relfs, $relts);
					$valid_promotion=true;

					if($capturing) {
						$move->label->disambiguation=file_str($fs);
						$move->label->sign=SIGN_CAPTURE;
					}

					$move->label->piece="";

					if(pawn_move_promote($relts)) {
						$valid_promotion=false;

						if($promote_to>=KNIGHT && $promote_to<=QUEEN) {
							$move->action[$ts]=piece($promote_to, $colour);
							$move->label->special=SIGN_PROMOTE.Fen::piece_char(piece($promote_to, WHITE));
							$valid_promotion=true;
						}
					}

					if($valid_promotion===true) { //valid promotion, or not a promote move
						if($moveto->type===SQ_EMPTY) {
							if(pawn_move_double($relfs, $relts)) {
								$pos->ep=rel_sq_no($relts-8, $colour);
								$move->valid=true;
							}

							else if(pawn_move($relfs, $relts)) {
								$move->valid=true;
							}

							else if($capturing && $ts===$this->position->ep) {
								$move->action[ep_pawn($fs, $ts)]=SQ_EMPTY;
								$move->label->sign=SIGN_CAPTURE;
								$move->capture=piece(PAWN, $opp_colour);
								$move->valid=true;
							}
						}

						else if($capturing) {
							$move->valid=true;
						}
					}
				}

				else if(($piece->type===KING || $piece->type===ROOK) && !$this->is_in_check($colour)) {
					$move->castling=true;

					/*
					standard and 960 castling are different enough that it is worth having them
					completely separate.

					the default block now contains the original standard chess castling code.
					*/

					switch($this->variant) {
						case VARIANT_960: {
							$backranks=array(
								WHITE=>0,
								BLACK=>7
							);

							$backrank=$backranks[$colour];

							if(y($fs)===$backrank && y($ts)===$backrank) {
								/*
								blocked - get furthest in and furthest out squares out of the king/rook
								start/end positions - there can't be anything but the king and rook
								between them (inclusive)
								*/

								/*
								through check - king start, king end and anything between
								*/

								$rook_dest_files=array(
									KINGSIDE=>5,
									QUEENSIDE=>3
								);

								$king_dest_files=array(
									KINGSIDE=>6,
									QUEENSIDE=>2
								);

								$edges=array(
									KINGSIDE=>7,
									QUEENSIDE=>0
								);

								$king_sq=$this->position->kings[$colour];
								$rook_sq=null;

								//find out whether it's kingside or queenside based on move direction

								if($piece->type===ROOK) {
									$side=(x($fs)<x($ts))?QUEENSIDE:KINGSIDE;
								}

								else if($piece->type===KING) {
									$side=(x($fs)>x($ts))?QUEENSIDE:KINGSIDE;
								}

								$rook_dest_file=$rook_dest_files[$side];
								$king_dest_file=$king_dest_files[$side];
								$edge=$edges[$side];

								//if king move, look for the rook between the edge and the king

								if($piece->type===ROOK) {
									$rook_sq=$fs;
								}

								else {
									$rook_squares=squares_between(coords_to_sq(array($edge, $backrank)), $king_sq, true);

									foreach($rook_squares as $sq) {
										if($this->position->board[$sq]===piece(ROOK, $colour)) {
											$rook_sq=$sq;
											break;
										}
									}
								}

								//this bit finds out which squares to check to see that the only 2 pieces
								//on the bit of the back rank used for castling are the king and the rook

								if($rook_sq!==null) {
									$king_dest_sq=coords_to_sq(array($king_dest_file, $backrank));
									$rook_dest_sq=coords_to_sq(array($rook_dest_file, $backrank));

									$king_file=x($king_sq);
									$rook_file=x($rook_sq);

									$outermost_sq=$king_sq;
									$innermost_sq=$rook_sq;

									if(abs($edge-$rook_dest_file)>abs($edge-$king_file)) { //rook dest is further out
										$outermost_sq=$rook_dest_sq;
									}

									if(abs($edge-$king_dest_file)<abs($edge-$rook_file)) { //king dest is further in
										$innermost_sq=$king_dest_sq;
									}

									$squares=squares_between($innermost_sq, $outermost_sq, true);

									$kings=0;
									$rooks=0;
									$others=0;

									foreach($squares as $sq) {
										$pc=$this->position->board[$sq];

										if($pc!==SQ_EMPTY) {
											if($pc===piece(ROOK, $colour)) {
												$rooks++;
											}

											else if($pc===piece(KING, $colour)) {
												$kings++;
											}

											else {
												$others++;
												break;
											}
										}
									}

									if($kings===1 && $rooks===1 && $others===0) {
										$through_check=false;
										$between=squares_between($king_sq, $king_dest_sq);

										foreach($between as $n) {
											if(count(attackers($this->position->board, $n, $opp_colour))>0) {
												$through_check=true;
												break;
											}
										}

										if(!$through_check) {
											$move->label->piece="";
											$move->label->to="";
											$move->label->disambiguation="";
											$move->label->special=CastlingDetails::$signs[$side];
											$move->action=array();
											$move->action[$king_sq]=SQ_EMPTY;
											$move->action[$rook_sq]=SQ_EMPTY;
											$move->action[$king_dest_sq]=piece(KING, $colour);
											$move->action[$rook_dest_sq]=piece(ROOK, $colour);
											$move->valid=true;
										}
									}
								}
							}

							break;
						}

						default: { //standard (could be GAME_TYPE_STANDARD or just null)
							if($piece->type===KING && $unobstructed) {

								$castling=new CastlingDetails($fs, $ts);

								if($castling->valid && $this->position->castling->get($colour, $castling->side)) {
									//not blocked or through check

									$through_check=false;

									$between=squares_between($fs, $ts);

									foreach($between as $n) {
										if(count(attackers($this->position->board, $n, $opp_colour))>0) {
											$through_check=true;
											break;
										}
									}

									if(!blocked($this->position->board, $fs, $castling->rook_start_pos) && !$through_check) {
										$move->label->piece="";
										$move->label->to="";
										$move->label->special=$castling->sign;
										$move->action[$castling->rook_start_pos]=SQ_EMPTY;
										$move->action[$castling->rook_end_pos]=piece(ROOK, $colour);
										$move->valid=true;
									}
								}
							}

							break;
						}
					}
				}

				/*
				move is valid, now see if it puts the player in check

				if not, it's legal
				*/

				if($move->valid) {
					foreach($move->action as $sq=>$pc) {
						$pos->set_square($sq, $pc);
					}

					//test whether the player is in check on temporary board

					$plr_king_attackers=attackers($pos->board, $pos->kings[$colour], $opp_colour);

					if(count($plr_king_attackers)===0) {
						$move->legal=true;
					}
				}

				if($move->legal) {
					if(!$dryrun) {
						$old_pos=new Position($this->position->get_fen());
						$this->position=$pos;

						//increment fullmove

						if($colour===BLACK) {
							$this->position->fullmove++;
						}

						//switch active colour

						$this->position->active=$opp_colour;

						//50-move

						if($move->capture!==null || $piece->type===PAWN) {
							$this->position->clock=0;
						}

						else {
							$this->position->clock++;
						}

						//reject any open draw offers or undo requests

						$this->draw_offered=null;
						$this->undo_requested=false;
						$this->undo_granted=false;

						//ep

						if($piece->type!==PAWN || !pawn_move_double($relfs, $relts)) {
							$this->position->ep=null;
						}

						/*
						disable castling

						for simplicity this is done in "file" mode (where the file of the rook is given,
						as opposed to KINGSIDE or QUEENSIDE) regardless of variant.  CastlingPrivileges
						knows to disable QUEENSIDE if the file is 0 etc.
						*/

						if($piece->type===KING || $move->castling) { //might be a rook-based castle (960)
							for($file=0; $file<8; $file++) {
								$this->position->castling->set($colour, $file, false, CastlingPrivileges::MODE_FILE);
							}
						}

						else if($piece->type===ROOK) { //rook move, not castling
							$this->position->castling->set($colour, x($fs), false, CastlingPrivileges::MODE_FILE);
						}

						/*
						check, mate and stalemate
						*/

						if($this->is_in_check($opp_colour)) {
							$move->label->check=SIGN_CHECK;
						}

						if($this->is_mated($opp_colour)) { //checkmate
							$move->label->check=SIGN_MATE;
							$this->game_over(result_win($colour), RESULT_DETAILS_CHECKMATE);
						}

						else {
							//insufficient mating material
							//games are automatically drawn only if mate is impossible, not if it's just not forceable.
							//this should really check for un-unblockable pawns, etc to be completely correct

							if(!$this->can_mate(WHITE) && !$this->can_mate(BLACK)) {
								$this->game_over(RESULT_DRAW, RESULT_DETAILS_INSUFFICIENT);
							}

							//no moves

							/*
							moves available will sometimes return 0 in bughouse games, e.g.
							when the player would be mated normally but can wait to put a
							piece in the way, so stalemate by being unable to move has been
							left out for bughouse.  obviously the best way would be to also
							check whether it's possible that pieces will become available,
							but that's too much of a performance hit (on the server at least).
							*/

							if($this->count_legal_moves($opp_colour)===0 && $this->type!==GAME_TYPE_BUGHOUSE) {
								$this->game_over(RESULT_DRAW, RESULT_DETAILS_STALEMATE);
							}

							//fifty move

							if($this->position->clock>49) {
								$this->fiftymove_claimable=true;
							}

							//threefold

							$this->check_threefold();
						}

						/*
						add to history and pieces_taken and set last move time
						*/

						if($move->capture!==null) {
							$this->pieces_taken->add($move->capture);
						}

						$move->fen=$this->position->get_fen();

						if($this->history->move($move)) {
							$move->success=true;
						}

						else { //if adding to the history fails for some reason, set back to the original position
							$this->position=$old_pos;
						}
					}
				}
			}
		}

		return $move;
	}

	/*
	the index of the last move made, or -1 if there aren't any moves
	*/

	public function get_current_move_index() {
		if($this->history->main_line->length>0) {
			return $this->history->main_line->last_move->move_index;
		}

		return -1;
	}

	/*
	is $colour in check
	*/

	public function is_in_check($colour) {
		return (count(attackers($this->position->board, $this->position->kings[$colour], opp_colour($colour)))>0);
	}

	/*
	is $colour checkmated
	*/

	public function is_mated($colour) {
		return ($this->is_in_check($colour)===true && $this->count_legal_moves($colour)===0);
	}

	/*
	can_mate - is it possible to arrange the current pieces
	so that $colour has won by checkmate.  may or may not
	be forceable.
	*/

	public function can_mate($colour) {
		/*
		 if..
			$colour has any pawns, queens or rooks
			or
			both players have at least one bishop on different colours
			or
			there is at least one bishop and one knight on the board
			or
			there are 3 or more knights on the board (one or more of which belongs to $colour)
		.. then $colour can mate
		*/

		$pieces=array(
			KNIGHT=>0,
			BISHOP=>0
		);

		$bishops=array(
			WHITE=>0,
			BLACK=>0
		);

		$knights=array(
			WHITE=>0,
			BLACK=>0
		);

		foreach($this->position->board as $sq=>$piece) {
			$piece_colour=colour($piece);
			$piece_type=type($piece);

			if($piece_type!==SQ_EMPTY && $piece_type!==KING) {
				if($piece_colour===$colour && ($piece_type===PAWN || $piece_type===ROOK || $piece_type===QUEEN)) {
					return true;
				}

				if($piece_type===BISHOP) {
					$bishops[$piece_colour]++;
				}

				if($piece_type===KNIGHT) {
					$knights[$piece_colour]++;
				}

				if($piece_type===KNIGHT || $piece_type===BISHOP) {
					$pieces[$piece_type]++;
				}
			}
		}

		if(($bishops[WHITE]>0 && $bishops[BLACK]>0) || ($pieces[BISHOP]>0 && $pieces[KNIGHT]>0) || ($pieces[KNIGHT]>2 && $knights[$colour]>0)) {
			return true;
		}

		return false;
	}

	/*
	does $colour have the minimum material required to force
	a mate, regardless of the position - currently this is
	set at a pawn, a rook, a queen or 3 minor pieces.
	*/

	public function can_force_mate($colour) {
		$minor_pieces=0;

		foreach($this->position->board as $piece) {
			$piece_colour=colour($piece);
			$piece_type=type($piece);

			if($piece_colour===$colour) {
				if($piece_type===PAWN || $piece_type===ROOK || $piece_type===QUEEN) {
					return true;
				}

				if($piece_type===KNIGHT || $piece_type===BISHOP) {
					$minor_pieces++;
				}

				if($minor_pieces>=3) {
					return true;
				}
			}
		}

		return false;
	}

	public function resign($colour) {
		$this->game_over(result_win(opp_colour($colour)), RESULT_DETAILS_RESIGNATION);

		return true;
	}

	public function offer_draw($colour) {
		if($this->history->main_line->length>0 && $this->position->active!==$colour) {
			$this->draw_offered=$colour;

			return true;
		}

		return false;
	}

	public function accept_draw($colour) {
		if($this->draw_offered===opp_colour($colour)) {
			$this->game_over(RESULT_DRAW, RESULT_DETAILS_DRAW_AGREED);

			return true;
		}

		return false;
	}

	/*
	there is no "reject draw" function - draw offers get rejected implicitly, and
	the draw offer is cancelled, when the next move is made.  for live games the
	opponent can be notified of the rejection using the messaging system.
	*/

	public function claim_threefold($colour) {
		if($this->position->active===$colour && $this->threefold_claimable) {
			$this->game_over(RESULT_DRAW, RESULT_DETAILS_THREEFOLD);

			return true;
		}

		return false;
	}

	public function claim_fiftymove($colour) {
		if($this->position->active===$colour && $this->fiftymove_claimable) {
			$this->game_over(RESULT_DRAW, RESULT_DETAILS_FIFTY_MOVE);

			return true;
		}

		return false;
	}

	public function request_undo($colour) {
		if($this->history->main_line->length>0 && $this->position->active!==$colour) {
			$this->undo_granted=false;
			$this->undo_requested=true;

			return true;
		}

		return false;
	}

	public function grant_undo($colour) {
		$success=false;

		if($this->undo_requested && $this->position->active===$colour) {
			if($this->history->undo()) {
				$this->undo_granted=true;
				$this->undo_requested=false;
				$success=true;
				$this->update_last_move_index();
			}
		}

		return $success;
	}

	public function deny_undo() {
		$this->undo_requested=false;

		return true;
	}

	protected function game_over($result, $details) {
		$now=mtime();

		if($details===RESULT_DETAILS_TIMEOUT) {
			foreach($this->time as $time) {
				if($time<=0) {
					$this->mtime_finish=$now-$time;

					break;
				}
			}
		}

		else {
			$this->mtime_finish=$now;
		}

		$this->draw_offered=null;
		$this->undo_granted=false;
		$this->undo_requested=false;
		$this->result=$result;
		$this->result_details=$details;
		$this->state=GAME_STATE_FINISHED;
		$this->threefold_claimable=false;
		$this->fiftymove_claimable=false;
	}
}
?>