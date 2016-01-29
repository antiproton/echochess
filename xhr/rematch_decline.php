<?php
/*
rematch_decline:

decline a rematch (quick challenges)

*table

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

	if(isset($q["table"])) {
		$table=new Table($q["table"]);
		$table->rematch_decline($user->username);
		$result=$table->save();
	}
}

echo Data::serialise($result);
?>