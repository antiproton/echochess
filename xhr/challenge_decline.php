<?php
/*
challenge_decline:

decline a quick challenge

*table

output: nothing
*/

require_once "base.php";
require_once "Data.php";
require_once "php/init.php";
require_once "php/livechess/Table.php";

if($session->user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	if(isset($q["table"])) {
		$table=new Table($q["table"]);
		$table->challenge_decline($session->user->username);
		$result=$table->save();
	}
}
?>