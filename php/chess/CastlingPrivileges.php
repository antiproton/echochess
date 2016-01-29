<?php
require_once "php/chess/constants.php";

class CastlingPrivileges {
	private $privs_side=array();
	private $privs_file=array();

	const MODE_SIDE=0;
	const MODE_FILE=1;

	static $side_chars=array(
		WHITE=>array(
			KINGSIDE=>FEN_WHITE_CASTLE_KS,
			QUEENSIDE=>FEN_WHITE_CASTLE_QS
		),
		BLACK=>array(
			KINGSIDE=>FEN_BLACK_CASTLE_KS,
			QUEENSIDE=>FEN_BLACK_CASTLE_QS
		)
	);

	static $file_chars=array(
		WHITE=>array(
			FEN_WHITE_CASTLE_A,
			FEN_WHITE_CASTLE_B,
			FEN_WHITE_CASTLE_C,
			FEN_WHITE_CASTLE_D,
			FEN_WHITE_CASTLE_E,
			FEN_WHITE_CASTLE_F,
			FEN_WHITE_CASTLE_G,
			FEN_WHITE_CASTLE_H
		),
		 BLACK=>array(
			FEN_BLACK_CASTLE_A,
			FEN_BLACK_CASTLE_B,
			FEN_BLACK_CASTLE_C,
			FEN_BLACK_CASTLE_D,
			FEN_BLACK_CASTLE_E,
			FEN_BLACK_CASTLE_F,
			FEN_BLACK_CASTLE_G,
			FEN_BLACK_CASTLE_H
		)
	);

	static $file_to_side=array(
		0=>QUEENSIDE,
		7=>KINGSIDE
	);

	static $side_to_file=array(
		KINGSIDE=>7,
		QUEENSIDE=>0
	);

	/*
	initialise with no privileges
	*/

	public function __construct() {
		$colours=array(WHITE, BLACK);
		$sides=array(KINGSIDE, QUEENSIDE);

		foreach($colours as $colour) {
			foreach($sides as $side) {
				if(!isset($this->privs_side[$colour])) {
					$this->privs_side[$colour]=array();
				}

				$this->privs_side[$colour][$side]=false;
			}

			for($file=0; $file<8; $file++) {
				if(!isset($this->privs_file[$colour])) {
					$this->privs_file[$colour]=array();
				}

				$this->privs_file[$colour][$file]=false;
			}
		}
	}

	public function set($colour, $index, $allow, $mode=self::MODE_SIDE) {
		switch($mode) {
			case self::MODE_SIDE: {
				$this->privs_side[$colour][$index]=$allow;
				$this->privs_file[$colour][self::$side_to_file[$index]]=$allow;

				break;
			}

			case self::MODE_FILE: {
				$this->privs_file[$colour][$index]=$allow;

				if(array_key_exists($index, self::$file_to_side)) {
					$this->privs_side[$colour][self::$file_to_side[$index]]=$allow;
				}

				break;
			}
		}
	}

	public function get($colour, $index, $mode=self::MODE_SIDE) {
		switch($mode) {
			case self::MODE_SIDE: {
				return $this->privs_side[$colour][$index];
			}

			case self::MODE_FILE: {
				return $this->privs_file[$colour][$index];
			}
		}
	}

	public function set_str($str) {
		$arr=str_split($str);

		foreach($arr as $char) {
			$lower_char=strtolower($char);
			$upper_char=strtoupper($char);

			$colour=($char===$upper_char)?WHITE:BLACK;
			$mode=(strpos(FILE, $lower_char)!==false)?self::MODE_FILE:self::MODE_SIDE;

			switch($mode) {
				case self::MODE_SIDE: {
					$index=strpos(FEN_BLACK_CASTLE_KS.FEN_BLACK_CASTLE_QS, $lower_char);

					break;
				}

				case self::MODE_FILE: {
					$index=strpos(FILE, $lower_char);

					break;
				}
			}

			$this->set($colour, $index, true, $mode);
		}
	}

	/*
	go through the files, adding the kingside/queenside char if the file
	matches the kingside/queenside rook position, or the file char it it
	doesn't
	*/

	public function get_str() {
		$colours=array(WHITE, BLACK);
		$sides=array(KINGSIDE, QUEENSIDE);

		$files=array(
			KINGSIDE=>7,
			QUEENSIDE=>0
		);

		$privs=array(
			WHITE=>array(),
			BLACK=>array()
		);

		foreach($colours as $colour) {
			for($file=0; $file<8; $file++) {
				if($this->privs_file[$colour][$file]) {
					$char=self::$file_chars[$colour][$file];

					if(array_key_exists($file, self::$file_to_side)) {
						$char=self::$side_chars[$colour][self::$file_to_side[$file]];
					}

					$privs[$colour][]=$char;
				}
			}
		}

		return implode("", $privs[WHITE]).implode("", $privs[BLACK]);
	}
}
?>