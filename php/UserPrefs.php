<?php
/*
UserPrefs
*/

require_once "php/db.php";

class UserPrefs {
	public $row;

	public function load($user) {
		$this->load_row(Db::row("select * from user_prefs where user='$user'"));
	}

	public function load_row($row) {
		$this->row=$row;
	}

	public static function get($username) {
		return Db::row("select * from user_prefs where user='$username'");
	}

	public static function get_defaults() {
		/*
		there is one row in the db where the user is NULL - this stores the
		default prefs.
		*/

		return Db::row("select * from user_prefs where user is null");
	}
}
?>