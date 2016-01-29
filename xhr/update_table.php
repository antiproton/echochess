<?php
/*
update table:

*id
variant
subvariant
fen
timing_initial
timing_increment
timing_delay
timing_style
alternate_colours
chess960_randomise_mode
permissions_watch
permissions_play
rated

output:

true or false
*/

require_once "base.php";
require_once "Data.php";
require_once "dbcodes/chess.php";
require_once "php/init.php";
require_once "php/livechess/Table.php";

$result=false;

if($user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	if(isset($q["id"])) {
		$table=new Table();
		$table->load($q["id"]);
		$table->update($q);
		$result=$table->save();
	}
}

echo Data::serialise($result);
?>