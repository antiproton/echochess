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
require_once "php/init.php";
require_once "Data.php";
require_once "Db.php";

$result=false;

$q=Data::unserialise_clean($_GET["q"]);

if(isset($q["user"]) && isset($q["type"]) && isset($q["variant"]) && isset($q["format"])) {
	$result=$db->cell("select 1200");
}

echo Data::serialise($result);
?>