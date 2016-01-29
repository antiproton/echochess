<?php
function stockfish($fen, $time=1000) {
	$stockfish_command=implode("\n", [
		"position fen $fen",
		"go movetime $time",
		""
	]);

	exec("echo \"$stockfish_command\" | stockfish", $output);

	/*
	NOTE when going through PHP, Stockfish seems to put another bollocks
	bestmove on the end for some reason (a3 when Qh5 is mate)
	*/

	$bestmove=null;
	$score=0;
	$score_type=null; //"cp" or "mate"

	//split each line by the space character to sort out the info
	//(the output of stockfish is mostly a bunch of stuff separated by spaces)

	$lines=[];

	foreach($output as $ln) {
		$lines[]=explode(" ", $ln);
	}

	foreach($lines as $ln) {
		if($ln[0]=="info" && $ln[3]=="seldepth") { //there will be a few of these, the last one is the most accurate apparently
			$score_type=$ln[6];
			$score=$ln[7];
		}

		else if($ln[0]=="bestmove" && $bestmove===null) {
			$bestmove=$ln[1];
		}
	}

	$result=[
		"move"=>$bestmove,
		"score"=>$score,
		"score_type"=>$score_type
	];

	return $result;
}
?>