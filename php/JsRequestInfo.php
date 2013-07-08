<?php
/*
general info that needs to be available to javascript as soon as the page
has loaded (to avoid having to send an xhr to get it).

whatever is put in here can be output in the form of javascript code
assigning the data in serialised form to a certain variable, which will
then be available.

this was done because directly outputting javascript code with PHP
became unviable because of the minification (no variable names, so no
way of PHP-generated and non-PHP-generated javascript code referring to
each other reliably).

the javascript variable name that gets output here has to be in the
obfuscation blacklist, and accessing the data in the variable once it's
unserialised on the client side has to be done with associative array
syntax.
*/

require_once "Data.php";

class JsRequestInfo {
	const JS_VARIABLE_NAME="Request";

	static $data=[];

	public static function output() {
		echo self::JS_VARIABLE_NAME."='".Data::serialise(self::$data)."';";
	}
}
?>