<?php
require_once "Db.php";

class Invites {
	public static function is_invited($table, $user) {
		$db=Db::getinst();

		return ($db->row("select user from invite where tables='$table' and user='$user'")!==false);
	}

	public static function invite($table, $user) {
		$db=Db::getinst();
		$success=false;

		$ins=$db->insert("invite", array(
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
		$db=Db::getinst();

		$db->remove("invite", array(
			"tables"=>$table,
			"user"=>$user
		));
	}
}
?>