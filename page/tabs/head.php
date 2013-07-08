<?php
require_once "php/GenericUpdates.php";
require_once "dbcodes/chess.php";
require_once "php/Messages.php";

/*
get a list of tables the user is currently at with a game in progress
for adding tabs on startup
*/

if($session->user->signedin) {
	if(isset($_SESSION["redir"])) {
		unset($_SESSION["redir"]);
	}

	$tables=Db::col("
		select id
		from tables
		where game_in_progress=".Db::BOOL_TRUE."
		and exists (
			select * from seats
			where tables=tables.id
			and type='".SEAT_TYPE_PLAYER."'
			and user='{$session->user->username}'
		)
	");

	JsRequestInfo::$data["page"]=[
		"main_page_update"=>GenericUpdates::update(GENERIC_UPDATES_LIVE_MAIN_WINDOW, $session->user->username),
		"tables"=>$tables,
		"users_online"=>Db::cell("select count(distinct user) from longpolls")
	];
}

else {
	$_SESSION["redir"]="/tabs";
	$session->page->load("/signin");
}
?>