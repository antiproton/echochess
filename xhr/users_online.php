<?php
/*
ouptut:

list of online users (from longpolls) and ratings
*/

require_once "base.php";
require_once "Data.php";
require_once "php/init.php";
require_once "dbcodes/chess.php";

$result=false;

if($session->user->signedin) {
	$result=Db::table("
		select
			distinct user,
			round(get_rating(user, '".GAME_TYPE_STANDARD."', '".VARIANT_STANDARD."', '".GAME_FORMAT_OVERALL."')) as rating
		from longpolls
		where user!='{$session->user->username}'
	");
}

echo Data::serialise($result);
?>