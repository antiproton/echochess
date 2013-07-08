<?php
//captcha


if(!isset($_POST["register"])) {
	msg("register noform {$_SERVER["REMOTE_ADDR"]}");

	$row=Db::row("select * from captchas order by rand() limit 1");
	$_SESSION["captcha_fen"]=$row["fen"];
	$_SESSION["captcha_message"]=$row["message"];
	$_SESSION["captcha_answer"]=$row["answer"];
}

JsRequestInfo::$data["page"]=[
	"captcha"=>$_SESSION["captcha_fen"]
];

if(isset($_POST["register"])) {
	$errors=[];

	if(strlen($_POST["username"])<3) {
		msg("short user ".Data::serialise($_POST)." {$_SERVER["REMOTE_ADDR"]}");
		$errors[]="Usernames must be at least 3 characters long";
	}

	if(strlen($_POST["password"])<6) {
		msg("short pass ".Data::serialise($_POST)." {$_SERVER["REMOTE_ADDR"]}");
		$errors[]="Passwords must be at least 6 characters long";
	}

	if(strlen($_POST["username"])>0 && !preg_match("/^[\w\d_ \.]+$/", $_POST["username"])) {
		msg("inv user ".Data::serialise($_POST)." {$_SERVER["REMOTE_ADDR"]}");
		$errors[]="Username contains invalid characters";
	}

	if(preg_match("/[\\\\'\"]/", $_POST["password"])) {
		msg("inv pass ".Data::serialise($_POST)." {$_SERVER["REMOTE_ADDR"]}");
		$errors[]="Password contains invalid characters";
	}

	if($_POST["password"]!==$_POST["password_confirm"]) {
		msg("nomatch pass ".Data::serialise($_POST)." {$_SERVER["REMOTE_ADDR"]}");
		$errors[]="Password didn't match confirmation";
	}

	if($_POST["captcha"]!==$_SESSION["captcha_answer"]) {
		msg("bad captcha ".Data::serialise($_POST)." {$_SERVER["REMOTE_ADDR"]}");
		//$errors[]="Incorrect move for chess captcha";
	}

	if(Db::row("select username from users where username='".Clean::$post["username"]."'")!==false) {
		if(strtolower(Clean::$post["username"])==="taken") {
			$errors="Sorry, that username is already \"taken\".";
		}

		msg("user taken ".Data::serialise($_POST)." {$_SERVER["REMOTE_ADDR"]}");
		$errors[]="Username '".Clean::$post["username"]."' is not available.";
	}

	if(count($errors)===0) {
		Db::insert("users", [
			"username"=>Clean::$post["username"],
			"password"=>Clean::$post["password"],
			"email"=>Clean::$post["email"],
			"join_date"=>time()
		]);

		$prefs=Db::row("
			select
				board_style,
				piece_style,
				board_size,
				show_coords,
				sound,
				highlight_last_move,
				highlight_possible_moves,
				animate_moves,
				board_colour_light,
				board_colour_dark,
				premove,
				auto_queen
			from user_prefs
			where user is null
		");

		if($prefs!==false) {
			$prefs["user"]=Clean::$post["username"];

			Db::insert("user_prefs", $prefs);
		}

		$redir="/";

		if(isset($_SESSION["redir"])) {
			$redir=$_SESSION["redir"];
		}

		$session->page->load($redir);
		$session->user->sign_in(Clean::$post["username"], Clean::$post["password"]);
	}
}
?>