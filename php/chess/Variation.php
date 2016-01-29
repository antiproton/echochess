<?php
require_once "php/chess/util.php";

class Variation {
	public $history;
	public $is_mainline;
	public $is_variation=true;
	public $line=[];
	public $length=0;
	public $auto_update_pointers=true;
	public $variation;
	public $previous_move;
	public $next_move;
	public $previous_variation;
	public $next_variation;
	public $previous_item;
	public $next_item;
	public $item_index;
	public $branch_move;
	public $first_move=null;
	public $last_move=null;

	public function clear() {
		$this->line=array();
	}

	public function reset_pointers() {
		$this->variation=null;
		$this->previous_move=null;
		$this->next_move=null;
		$this->previous_variation=null;
		$this->next_variation=null;
		$this->previous_item=null;
		$this->next_item=null;
		$this->item_index=null;
		$this->branch_move=null;
	}

	public function __construct($history, $is_mainline=false) {
		$this->history=$history;
		$this->is_mainline=$is_mainline;
		$this->reset_pointers();
	}

	public function add($item, $dont_update=false) {
		$this->insert($item, count($this->line), $dont_update);
	}

	public function insert($item, $index, $dont_update=false) {
		$update=$dont_update?false:$this->auto_update_pointers;

		array_splice($this->line, $index, 0, array($item));

		if($update) {
			$this->update_pointers();
		}
	}

	public function remove($item, $dont_update=false) {
		$update=$dont_update?false:$this->auto_update_pointers;
		$len=count($this->line);

		for($i=0; $i<$len; $i++) {
			if($item==$this->line[$i]) {
				array_splice($this->line, $i, 1);
				break;
			}
		}

		if($update) {
			$this->update_pointers();
		}
	}

	public function delete_move($move, $dont_update=false) {
		$update=$dont_update?false:$this->auto_update_pointers;
		$item=$move;

		while($item!==null) {
			$this->remove($item, true);
			$item=$item->next_item;
		}

		if($update) {
			$this->update_pointers();
		}
	}

	public function insert_after($item, $prev_item, $dont_update=false) {
		$index=count($this->line);

		if($prev_item===null) {
			$this->insert($item, $index, $dont_update);
		}

		else {
			foreach($this->line as $i=>$item) {
				if($item==$prev_item) {
					$index=$i+1;
				}
			}

			$this->insert($item, $index, $dont_update);
		}
	}

	public function insert_after_move($item, $prev_move, $dont_update=false) {
		$index=count($this->line);

		if($prev_move===null) {
			$this->insert($item, $index, $dont_update);
		}

		else {
			$prev_item=$prev_move;

			while($prev_item->next_variation!==null) {
				$prev_item=$prev_item->next_variation;
			}

			$this->insert_after($item, $prev_item, $dont_update);
		}
	}

	/*
	insert_before - don't think this is needed (see js version)
	*/

	public function update_pointers($recursive=false) {
		$this->length=count($this->line);
		$this->first_move=null;
		$this->last_move=null;

		if(count($this->line)>0) {
			$last_move=null;
			$last_variation=null;
			$last_item=null;
			$move_index=0;
			$halfmove=0;

			if($this->history->get_starting_colour()===BLACK) {
				$halfmove=1;
			}

			if(!$this->is_mainline) {
				$halfmove=$this->branch_move->halfmove;
			}

			$this->first_move=$this->line[0];

			foreach($this->line as $i=>$item) {
				$item->reset_pointers();
				$item->variation=$this;
				$item->item_index=$i;
				$item->previous_move=$last_move;
				$item->previous_item=$last_item;

				if($item->is_variation) {
					$item->branch_move=$last_move;

					if($recursive) {
						$item->update_pointers($recursive);
					}

					if($last_item!==null) {
						$last_item->next_variation=$item;
					}

					$last_variation=$item;
				}

				else {
					$this->last_move=$item;
					$item->previous_variation=$last_variation;
					$item->halfmove=$halfmove;
					$item->fullmove=fullmove($halfmove);
					$item->move_index=$move_index;
					$item->colour=hm_colour($halfmove);
					$item->dot=fullmove_dot($item->colour);
					$item->display_fullmove=($item->colour===WHITE || $item->move_index===0 || $last_variation!==null);

					if($last_item!==null) {
						$last_item->next_move=$item;
					}

					if($last_move!==null) {
						$last_move->next_move=$item;
					}

					$last_move=$item;
					$last_variation=null;
					$halfmove++;
					$move_index++;
				}

				if($last_item!==null) {
					$last_item->next_item=$item;
				}

				$last_item=$item;
			}
		}
	}
}
?>