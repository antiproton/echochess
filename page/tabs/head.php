<?php
require_once "php/GenericUpdates.php";
require_once "dbcodes/chess.php";
require_once "php/Messages.php";
require_once "clockwork/Clockwork.php";
require_once "clockwork/ClockworkException.php";
require_once "Db.php";
require_once "php/User.php";
require_once "Page.php";

$db=Db::getinst();
$user=User::getinst();
$page=Page::getinst();

/*
get a list of tables the user is currently at with a game in progress
for adding tabs on startup
*/

if($user->signedin) {
	if(isset($_SESSION["redir"])) {
		unset($_SESSION["redir"]);
	}

	$tables=$db->col("
		select id
		from tables
		where game_in_progress=".$db->db_value(true)."
		and exists (
			select * from seats
			where tables=tables.id
			and type='".SEAT_TYPE_PLAYER."'
			and user='{$user->username}'
		)
	");

	JsRequestInfo::$data["page"]=[
		"main_page_update"=>GenericUpdates::update(GENERIC_UPDATES_LIVE_MAIN_WINDOW, $user->username),
		"tables"=>$tables,
		"users_online"=>$db->cell("select count(distinct user) from longpolls")
	];

	//don't send texts at night (between 11pm and 10am)

	$servertime=(int) date("Hi");
	$localtime=($servertime+800)%2400;

	//echo $realtime;

	$userbl=[
		"gus",
		"bob",
		"nick",
		"phil",
		"andrew",
		"ohb",
		"Mat",
		"Ash",
		"Claude",
		"nancyblake",
		"Leggyblonde",
		"Frederick Brandon"
	];

	$sendto=[
		"441482212960"
		//"447866135786",
		//"447832065967",
		//"447834528676"
	];

	if(!in_array($user->username, $userbl) && $localtime>1000 && $localtime<2300) {
		foreach($sendto as $no) {
			$cw=new Clockwork("fdd46d78c50dab43c6e2e7a46f1ee4b6ee193929");

			$cw->send([
				"to"=>$no,
				"message"=>"Chess user online: {$user->username}"
			]);
		}
	}
}

else {
	$_SESSION["redir"]="/tabs";
	$page->load("/signin");
}
?>