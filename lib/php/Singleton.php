<?php
trait Singleton {
	public static $instance=null;

	public static function getinst() {
		if(self::$instance===null) {
			self::$instance=self::create_instance();
		}

		return self::$instance;
	}

	/*
	override this to do any initial set up on the instance
	*/

	protected static function create_instance() {
		return new self();
	}
}
?>