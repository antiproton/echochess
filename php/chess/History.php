<?php
require_once "php/chess/constants.php";
require_once "php/chess/Variation.php";

class History {
	public $game;
	public $main_line; //same as js .RootVariation

	private $starting_colour=WHITE; //get/set

	public function __construct($game) {
		$this->game=$game;
		$this->main_line=new Variation($this, true);
		$this->set_starting_colour($this->game->starting_position->active);
	}

	public function move($move) {
		$this->main_line->add($move);
	}

	public function undo() {
		$move=$this->main_line->last_move;
		$this->main_line->remove($move);
	}

	public function set_starting_colour($colour) {
		$this->starting_colour=$colour;
		$this->main_line->update_pointers();
	}

	public function get_starting_colour() {
		return $this->starting_colour;
	}
}
?>