<?php
class PgnMove {
	public $label;
	public $comment_before=array();
	public $comment_after=array();

	public function __construct($label) {
		$this->label=$label;
	}
}
?>