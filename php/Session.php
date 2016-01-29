<?php
//require_once "MemcachedServer.php";
require_once "vendor/autoload.php";
require_once "Singleton.php";

class Session extends Symfony\Component\HttpFoundation\Session\Session {
	use Singleton;

	public static $instance=null;

	function create_instance() {
		//$memcached=MemcachedServer::getinst();
		//$handler=new Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcachedSessionHandler($memcached);
		$handler=new Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler();
		$storage=new Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage([], $handler);

		$inst=new self($storage);

		return $inst;
	}
}
?>