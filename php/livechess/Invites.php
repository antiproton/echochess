<?php
require_once "php/db.php";

class Invites {
	public static function is_invited($table, $user) {
		return (Db::row("select user from invite where tables='$table' and user='$user'")!==false);
	}

	public static function invite($table, $user) {
		$success=false;

		$ins=Db::insert("invite", array(
			"table"=>$q["table"],
			"user"=>$q["user"],
			"mtime_created"=>mtime()
		));

		if($ins!==false) {
			$success=true;
		}

		return $success;
	}

	public function remove($table, $user) {
		Db::remove("invite", array(
			"tables"=>$table,
			"user"=>$user
		));
	}
}
?>