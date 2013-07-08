<?php
/*
ready:

*table
*ready (boolean)

output:

true or false
*/

require_once "base.php";
require_once "Data.php";
require_once "dbcodes/chess.php";
require_once "php/init.php";
require_once "php/livechess/Table.php";

$result=false;

if($session->user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	if(isset($q["table"])) {
		$table=new Table($q["table"]);
		$result=$table->ready($session->user->username, $q["ready"]) && $table->save();
	}
}

echo Data::serialise($result);
?>