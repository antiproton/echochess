<?php
/*
invite:

user
table

output:

true or false
*/

/*
TODO probably want to delete these after a bit
*/

require_once "base.php";
require_once "Db.php";
require_once "Data.php";
require_once "php/init.php";

$result=false;

if($session->user->signedin) {
	$q=Data::serialise($_GET["q"]);

	if(isset($q["table"]) && isset($q["user"])) {
		$owner=Db::row("select id from tables where id='{$q["table"]}' and owner='{$session->user->username}'");

		if($owner!==false) {
			$result=Invites::invite($q["table"], $q["user"]);
		}
	}
}

echo Data::serialise($result);
?>