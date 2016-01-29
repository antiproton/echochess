<?php
class PiecesTaken {
	public $pieces=array();

	public function add($piece) {
		$this->pieces[]=$piece;
	}

	public function remove($piece) {
		$success=false;

		foreach($this->pieces as $i=>$pc) {
			if($pc===$piece) {
				array_splice($this->pieces, $i, 1);
				$success=true;
				
				break;
			}
		}

		return $success;
	}

	public function taken($piece) {
		return (array_search($piece, $this->pieces)!==false);
	}

	public function clear() {
		$this->pieces=[];
	}
}
?>