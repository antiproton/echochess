<?php
/*
Piece - generate a container if a lot of access will be needed to the piece's
type and colour; this saves on type() and colour() calls.
*/

require_once "php/chess/util.php";

class Piece {
	function __construct($piece) {
		$this->type=type($piece);
		$this->colour=colour($piece);
	}
}
?>