<?php
/*
ouptut:

list of online users (from longpolls) and ratings
*/

require_once "base.php";
require_once "Data.php";
require_once "Db.php";
require_once "php/User.php";
require_once "php/init.php";
require_once "dbcodes/chess.php";

$user=User::getinst();
$db=$db->getinst();
$result=false;

if($user->signedin) {
	$result=$db->table("
		select
			distinct user,
			1200 as rating
		from longpolls
		where user!='{$user->username}'
	");
}

echo Data::serialise($result);
?>