<?php
/*
session.class.php - container for site-wide variables
*/

require_once "Clean.php";
require_once "php/User.php";
//require_once "vendor/autoload.php";

class Session {
	public $url_request;
	public $page;
	public $user;
	public $session;

	function __construct() {
		$this->user=User::get_session_instance();

		//use symfony memcached session handler to make it work with the websocket

		//$memcached=new Memcached();
		//$memcached->addServer("localhost", 11211);
		//$handler=new Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcachedSessionHandler($memcached);
		//$storage=new Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage([], $handler);
		//$this->session=new Symfony\Component\HttpFoundation\Session\Session($storage);
		//$this->session->start();
		//$this->user=new User();
		//$this->user->set_session($this->session);
	}
}
?>