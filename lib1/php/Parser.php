<?php
require_once "ParseError.php";

abstract class Parser {
	const ESCAPE_CHAR="\\";

	public $current_char="";
	public $i=-1;
	public $words=[];
	public $characters=[];

	protected $length=0;
	protected $escaped=false;
	protected $last_non_whitespace_char="";
	protected $result="";

	/*
	perform the parsing
	*/

	abstract public function parse();

	public function __construct($str="") {
		$this->set_string($str);
	}

	public function set_string($str) {
		$this->characters=str_split($str);
		$this->reset();
		$this->length=count($this->characters);
	}

	protected function next($escaped=false) {
		if(!$this->eq(" ") && !$this->eq("\t") && !$this->eq("\n")) {
			$this->last_non_whitespace_char=$this->current_char;
		}

		$this->escaped=$escaped;
		$this->i++;

		if($this->eof()) {
			$this->current_char="";
		}

		else {
			$this->current_char=$this->characters[$this->i];
		}

		if(!$escaped && $this->eq(self::ESCAPE_CHAR)) {
			$this->next(true);
		}
	}

	protected function read() {
		$str=$this->current_char;
		$this->next();

		return $str;
	}

	protected function reset() {
		$this->i=-1;
		$this->current_char="";
	}

	protected function match($pattern, $str=null) {
		if($str===null) {
			$str=$this->current_char;
		}

		return (preg_match($pattern, $str)===1);
	}

	protected function eq($str) {
		return ($this->current_char===$str);
	}

	protected function eof() {
		return ($this->i>=$this->length);
	}

	protected function lookahead($str) {
		return (implode("", array_slice($this->characters, $this->i, strlen($str)))===$str);
	}

	protected function skip_whitespace($skip_newlines=false) {
		while(($this->eq(" ") || $this->eq("\t")) || ($skip_newlines && $this->eq("\n"))) {
			$this->next();
		}
	}

	protected function skip($ch) {
		$this->skip_whitespace();

		if($this->eq($ch)) {
			$this->next();
		}

		else {
			throw new ParseError($this, "expecting $ch");
		}
	}

	/*
	all the "skip" things should have an equivalent "get" that adds whatever it
	is straight to the result

	NOTE all this didn't seem to work very well when tested with JsObfuscator
	*/

	protected function get($ch) {
		$this->get_whitespace();

		if($this->eq($ch)) {
			$this->getchar();
		}

		else {
			throw new ParseError($this, "expecting $ch");
		}
	}

	protected function get_whitespace($skip_newlines=false) {
		while(($this->eq(" ") || $this->eq("\t")) || ($skip_newlines && $this->eq("\n"))) {
			$this->getchar();
		}
	}

	protected function getchar() {
		$str=$this->read();

		/*
		if the current char is escaped, the escape char itself has been skipped so it needs
		to be added to the output
		*/

		if($this->escaped) {
			$str=self::ESCAPE_CHAR.$str;
		}

		$this->result.=$str;
	}
}
?>