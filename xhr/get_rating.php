<?php
/*
get_rating:

*user
*type
*variant
*format

output:

the user's rating (rounded) or false
*/

require_once "base.php";
require_once "Data.php";
require_once "php/db.php";

$result=false;

$q=Data::unserialise_clean($_GET["q"]);

if(isset($q["user"]) && isset($q["type"]) && isset($q["variant"]) && isset($q["format"])) {
	$result=Db::cell("select round(get_rating('{$q["user"]}', '{$q["type"]}', '{$q["variant"]}', '{$q["format"]}'))");
}

echo Data::serialise($result);
?>