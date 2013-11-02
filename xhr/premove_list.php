<?php
/*
premove_list:

*gid

output:

list of premoves
*/

require_once "base.php";
require_once "Data.php";
require_once "php/init.php";
require_once "php/livechess/Premoves.php";

$result=false;

if($user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);
	$result=Premoves::get_premoves($q["gid"], $user->username);
}

echo Data::serialise($result);
?>