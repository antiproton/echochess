<?php
class Clean {
	static $post;
	static $get;
	
	public static function init() {
		self::$get = $_GET;
		self::$post = $_POST;
	}
}
