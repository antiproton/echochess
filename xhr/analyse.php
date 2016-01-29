<?php
/*
analyse

input:

fen
movetime

output:

scores and best move, and whether there is mate in n
*/

require_once "base.php";
require_once "Data.php";
require_once "php/livechess/LiveGame.php";
require_once "php/init.php";
require_once "php/chess/util.php";
require_once "php/chess/Fen.php";
require_once "php/constants.php";
require_once "php/chess/constants.php";
require_once "php/stockfish.php";

$result=false;

if($user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	$fen=FEN_INITIAL;
	$movetime=1000;

	if(isset($q["fen"]) && Fen::is_valid($q["fen"])) {
		$fen=$q["fen"];
	}

	if(isset($q["movetime"])) {
		if($q["movetime"]<=ANALYSIS_MOVETIME_MAX) {
			$movetime=(int) $q["movetime"];
		}

		else {
			$movetime=ANALYSIS_MOVETIME_MAX;
		}
	}

	$result=stockfish($fen, $movetime);
}

echo Data::serialise($result);
?>