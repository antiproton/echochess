<?php
/*
save_prefs

[whichever prefs it sends]

output:

true or false
*/

require_once "base.php";
require_once "Data.php";
require_once "php/init.php";

$result=false;

if($user->signedin) {
	$q=Data::unserialise_clean($_GET["q"]);

	$update=$db->update("user_prefs", $q, [
		"user"=>$user->username
	]);

	if($update!==false) {
		$result=true;
	}
}

echo Data::serialise($result);
?>