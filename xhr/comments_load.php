<?php
/*
TODO make this use Comments static etc

input:

*type
*subject

ouptut:

list of comments
*/

require_once "base.php";
require_once "Data.php";
require_once "php/livechess/LiveGame.php";
require_once "php/init.php";

$result=false;


$q=Data::unserialise_clean($_GET["q"]);

if(isset($q["type"]) && isset($q["subject"])) {
	$result=$db->table("
		select user, body, subject_line, mtime_posted
		from comments
		where type='{$q["type"]}'
		and subject='{$q["subject"]}'
	");
}

echo Data::serialise($result);
?>