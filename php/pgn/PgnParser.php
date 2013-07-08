<?php
/*
no private classes, so these three are kept in here to keep
things neat
*/

require_once "PgnMove.php";
require_once "PgnLine.php";

/*
PgnParser

there are 3 main kinds of method this class uses for parsing the PGN string:

skip_whitespace advances to the first non-whitespace character. the other
skip_ functions are there to confirm that the next non-whitespace character
is the one specified, and to move to the character immediately after it.
if the character is necessary a PgnParseError will be thrown if it is not
found.

read_ functions read some text and then return it.

get_ functions create PGN elements (moves, tags etc) and add them to the result
*/

class PgnParser extends Parser{
	const TAG_OPEN="[";
	const TAG_CLOSE="]";
	const COMMENT_OPEN="{";
	const COMMENT_CLOSE="}";
	const VARIATION_OPEN="(";
	const VARIATION_CLOSE=")";
	const TAG_VALUE_DELIM="\"";
	const DOT=".";
	const RESULT_WHITE="1-0";
	const RESULT_BLACK="0-1";
	const RESULT_DRAW="1/2-1/2";
	const RESULT_UNKNOWN="*";

	public $tags=array();
	public $main_line=null;
	public $error=null;
	public $result=null;

	private $last_move=null;
	private $last_comment=array();

	public static function get_move_info($label) { //TODO
		$chars=str_split($label);
		$end=count($chars)-1;

		for($i=$end; $i>=0; $i--) {

		}
	}

	public function __construct($str) {
		parent::__construct($str);

		$this->result="*";
	}

	public function parse() {
		$success=true;
		$this->reset();
		$this->next();
		$this->main_line=new PgnLine();

		try {
			$this->get_tags();
			$this->get_movetext();
		}

		catch(ParseError $e) {
			$this->error=$e;
			$success=false;
		}

		return $success;
	}

	private function get_tags() {
		$this->skip_whitespace(true);

		while(!$this->eof() && $this->eq(self::TAG_OPEN)) {
			$this->get_tag();
			$this->skip_whitespace(true);
		}
	}

	private function get_tag() {
		$this->skip(self::TAG_OPEN);

		$tag_name=$this->read_tag_name();
		$tag_value=$this->read_tag_value();

		$this->tags[$tag_name]=$tag_value;

		$this->skip(self::TAG_CLOSE);
	}

	private function read_tag_name() {
		$this->skip_whitespace();
		$tag_name="";

		while($this->match("/\w/")) {
			$tag_name.=$this->read();
		}

		if(strlen($tag_name)===0) {
			throw new ParseError($this, "expecting PGN tag name");
		}

		return $tag_name;
	}

	private function read_tag_value() {
		$this->skip_whitespace();
		$this->skip(self::TAG_VALUE_DELIM);
		$tag_value="";

		while(!$this->eof()) {
			if($this->eq(self::TAG_VALUE_DELIM)) {
				$this->skip(self::TAG_VALUE_DELIM);
				break;
			}

			else {
				$tag_value.=$this->read();
			}
		}

		return $tag_value;
	}

	private function get_movetext() {
		$this->get_variation($this->main_line);
	}

	private function get_move(PgnLine $line) {
		$this->skip_whitespace(true);
		$this->skip_move_number();
		$this->skip_whitespace();

		$label=$this->read_move_label();
		$move=new PgnMove($label);
		$this->last_move=$move;

		if($this->last_comment!==null) { //comment waiting to be assigned (because it appeared before any moves)
			$move->comment_before=$this->last_comment;
			$this->last_comment=array();
		}

		$line->add_move($move);
	}

	private function read_move_label() {
		$label="";

		while($this->match("/[\w\d\+\#\=\!\?]/")) {
			$label.=$this->read();
		}

		return $label;
	}

	private function get_variation($line) {
		while(!$this->eof()) {
			$this->skip_whitespace(true);

			if($this->lookahead_result()) {
				$this->get_result($line);
			}

			elseif($this->match("/[\w\d]/")) {
				$this->get_move($line);
			}

			elseif($this->eq(self::COMMENT_OPEN)) {
				$this->get_comment();
			}

			elseif($this->eq(self::VARIATION_OPEN)) {
				$variation=new PgnLine();
				$line->add_variation($variation);
				$this->skip(self::VARIATION_OPEN);
				$this->get_variation($variation);
			}

			elseif($this->eq(self::VARIATION_CLOSE)) {
				$this->next();
				break;
			}

			else {
				//bad characters, try just ignoring
				$this->next();
			}
		}
	}

	private function get_comment() {
		$this->skip(self::COMMENT_OPEN);

		$comment=$this->read_comment_body();

		if($this->last_move===null) { //no move to assign the comment to (because the comment appears before any moves)
			$this->last_comment[]=$comment;
		}

		else {
			$this->last_move->comment_after[]=$comment;
		}

		$this->skip(self::COMMENT_CLOSE);
	}

	private function read_comment_body() {
		$text="";

		while($this->escaped || (!$this->eq(self::COMMENT_CLOSE) && !$this->eq(self::COMMENT_OPEN))) {
			$text.=$this->read();
		}

		return $text;
	}

	private function get_result() {
		$this->skip_whitespace(true);
		$this->result=$this->read_result();
	}

	private function read_result() {
		$result="";

		while($this->match("/[\d\/\-\*]/")) {
			$result.=$this->read();
		}

		return $result;
	}

	private function skip_move_number() {
		while($this->match("/\d/")) {
			$this->next();
		}

		while($this->eq(self::DOT)) {
			$this->next();
		}
	}

	private function lookahead_result() {
		return ($this->lookahead(self::RESULT_WHITE) || $this->lookahead(self::RESULT_BLACK) || $this->lookahead(self::RESULT_DRAW) || $this->lookahead(self::RESULT_UNKNOWN));
	}
}
?>