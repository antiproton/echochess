<?php
/*
UserPrefs
*/

require_once "Db.php";

class UserPrefs {
	public $row;

	public function load($user) {
		$db=Db::getinst();
		$this->load_row($db->row("select * from user_prefs where user='$user'"));
	}

	public function load_row($row) {
		$db=Db::getinst();
		$this->row=$row;
	}

	public static function get($username) {
		$db=Db::getinst();
		return $db->row("select * from user_prefs where user='$username'");
	}

	public static function get_defaults() {
		/*
		there is one row in the db where the user is NULL - this stores the
		default prefs.
		*/

		$db=Db::getinst();
		return $db->row("select * from user_prefs where user is null");
	}
}
?>