<?php
class MoveLabel {
	/*
	sign is "x" for captures.  special is used for castling and promotion.
	check is blank, + or #.
	*/

	public $piece="";
	public $disambiguation="";
	public $sign=""; //"x" for captures
	public $to="";
	public $special=""; //promotion ("=N") and castling ("O-O")
	public $check="";
	public $notes=""; //!, ? etc

	public function get_label() {
		return $this->piece.$this->disambiguation.$this->sign.$this->to.$this->special.$this->check.$this->notes;
	}
}
?>