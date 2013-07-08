<?php
/*
NOTE this class is used for both authentication and general user functionality
(updating ratings etc)

NOTE this can use both get_session_instance to get an instance of the object
out of the session (keyed by the class name) or set_session to give it a symfony
session, where it will look for a username under the key defined in $session_key
*/

require_once "date.php";
require_once "Db.php";
require_once "php/chess/util.php";

class User {
	public $signedin=false;
	public $username;
	public $password;
	public $email;
	public $join_date;
	public $quick_challenges_as_white=0;
	public $quick_challenges_as_black=0;
	public $id;

	public $session; //symfony2 session
	public $session_key="user";

	private $is_new=true;
	private $row;
	private $table_name="users";

	public static function get_session_instance() {
		if(!isset($_SESSION[__CLASS__])) {
			$_SESSION[__CLASS__]=new self();
		}

		return $_SESSION[__CLASS__];
	}

	public function __construct($username=null) {
		if($username!==null) {
			$this->load_by_username($username);
		}
	}

	/*
	use the supplied symfony2 session to see if the user is signed in

	the session will be kept and used for signing out
	*/

	public function set_session($session) {
		$this->session=$session;
		$user=$this->session->get($this->session_key);

		if($user) {
			$this->sign_in_noauth($user);
		}
	}

	public function load($id) {
		$this->load_row(Db::row("select * from {$this->table_name} where id='$id'"));
	}

	public function load_by_username($username) {
		$this->load_row(Db::row("select * from {$this->table_name} where username='$username'"));
	}

	private function load_row($row) {
		$id=$row["id"];

		$this->row=$row;
		$this->username=$row["username"];
		$this->password=$row["password"];
		$this->email=$row["email"];
		$this->join_date=$row["join_date"];
		$this->quick_challenges_as_white=$row["quick_challenges_as_white"];
		$this->quick_challenges_as_black=$row["quick_challenges_as_black"];
		$this->id=$id;

		$this->is_new=false;
	}

	public function friends_with($user) {
		return self::friends($this->username, $user);
	}

	public static function friends($user1, $user2) {
		return !!Db::row("
			select usera
			from relationships
			where type='".RELATIONSHIP_TYPE_FRIENDS."'
			and (
				(usera='$user1' and userb='$user2')
				or (usera='$user2' and userb='$user1')
			)
		");
	}

	public function save() {
		$success=false;

		if($this->is_new) {
			$this->join_date=time();

			$row=[
				"username"=>$this->username,
				"password"=>$this->password,
				"email"=>$this->email,
				"join_date"=>$this->join_date,
				"quick_challenges_as_white"=>$this->quick_challenges_as_white,
				"quick_challenges_as_black"=>$this->quick_challenges_as_black
			];

			$insert_id=Db::insert($this->table_name, $row);

			if($insert_id!==false) {
				$this->id=$insert_id;
				$this->row=$row;
				$this->is_new=false;
				$success=true;
			}
		}

		else {
			$update=[];

			if($this->password!==$this->row["password"]) {
				$update["password"]=$this->password;
			}

			if($this->email!==$this->row["email"]) {
				$update["email"]=$this->email;
			}

			if($this->quick_challenges_as_white!==$this->row["quick_challenges_as_white"]) {
				$update["quick_challenges_as_white"]=$this->quick_challenges_as_white;
			}

			if($this->quick_challenges_as_black!==$this->row["quick_challenges_as_black"]) {
				$update["quick_challenges_as_black"]=$this->quick_challenges_as_black;
			}

			$success=Db::update($this->table_name, $update, [
				"id"=>$this->id
			]);
		}

		return $success;
	}

	public function sign_in_noauth($user) {
		$row=Db::row("select * from {$this->table_name} where username='$user'");

		if($row!==false) {
			$this->load_row($row);
			$this->signedin=true;
		}
	}

	public function sign_in($user, $pass) {
		$row=Db::row("select * from {$this->table_name} where username='$user' and password='$pass'");

		if($row!==false) {
			$this->load_row($row);
			$this->signedin=true;

			if($this->session!==null) {
				$this->session->set($this->session_key, $user);
			}
		}
	}

	public function sign_out() {
		$this->signedin=false;

		if($this->session!==null) {
			$this->session->remove($this->session_key);
		}
	}
}
?>