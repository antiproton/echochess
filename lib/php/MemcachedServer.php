<?php
/*
Memcached singleton with a server already set up
*/

require_once "Singleton.php";

if(class_exists("Memcached")) {
	class MemcachedServer extends Memcached {
		use Singleton;

		public static $instance=null;

		public static function create_instance() {
			$inst=new self();
			$inst->addServer("localhost", 11211);

			return $inst;
		}
	}
}
?>