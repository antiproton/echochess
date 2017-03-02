<?php
class ParseError extends Exception {
	public function __construct($parser, $error) {
		$line=0;
		$col=0;
		$ch="";

		for($i=0; $i<=$parser->i; $i++) {
			$col++;
			$ch=$parser->characters[$i];

			if($ch=="\n") {
				$line++;
				$col=0;
			}
		}

		$this->message="Parse error: \"$error\" at line $line, col $col";
	}
}
?>