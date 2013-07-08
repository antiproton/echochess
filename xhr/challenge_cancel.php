<?php
/*
challenge_cancel:

cancel any open challenges.  the user is supposed to only have one
open at a time anyway, so there shouldn't be any danger of deleting
the wrong one.
*/

require_once "base.php";
require_once "Data.php";
require_once "php/init.php";
require_once "php/livechess/Table.php";

$result=false;

if($session->user->signedin) {
	$result=Table::cancel_open_challenges($session->user->username);
}

echo Data::serialise($result);
?>