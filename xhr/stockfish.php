<?php
/*
computer_move

input:

gid

output:

true if move was made successfully, false otherwise
*/

require_once "base.php";
require_once "Data.php";
require_once "php/init.php";
require_once "php/chess/util.php";
require_once "php/chess/Fen.php";

$result=false;

if($user->signedin) {
	$fen=$db->cell("select fen from games where gid='{$q["gid"]}'");

	if($fen!==false) {
		/*
		set up the position for the engine - check whether the game has a particular starting fen
		and get a list of moves
		*/

		$position_cmd="position";

		if($fen===null) {
			$position_cmd.=" startpos";
		}

		else {
			$position_cmd.=" $fen";
		}

		$table=$db->table("select fs, ts, promote_to from moves where gid='{$q["gid"]}' order by move_index");
		$moves=[];

		foreach($table as $row) {
			$promote="";

			if($row["promote_to"]!==null) {
				$promote=Fen::piece_char(piece($row["promote_to"], BLACK));
			}

			$moves[]=alg_sq($row["fs"]).alq_sq($row["ts"]).$promote;
		}

		if(count($moves)>0) {
			$position_cmd.=" moves ".implode(" ", $moves);
		}

		$stockfish_command=implode("\n", [
			$position_cmd,
			"go"
		]);

		exec("echo \"$stockfish_command\" | stockfish", $output);

		echo implode("\n", $output);
	}
}

echo Data::serialise($result);
?>