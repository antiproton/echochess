<?php
/*
TODO json_encode seems to escape tabs and newlines - see if this causes
problems and if so unescape them here
*/

class Data {
	public static function serialise($obj) {
		return json_encode($obj);
	}

	public static function unserialise($str) {
		return json_decode($str, true);
	}

	public static function unserialise_clean($str) {
		/*
		user-submitted json is cleaned the following way -
		backslash-doublequote is replaced with a marker for later; all
		backslashes are removed so that no escapes can be un-escaped
		by user-submitted backslashes; single quotes are replaced with
		backslash-singlequote so that they don't disrupt mysql query
		strings; backslash-double-quote markers are replaced with a
		real backslash-double-quote to return the string to normal and
		allow user json to safely contain the double quote character.
		*/

		$str=str_replace("\\\"", "[DBLQUOTE]", $str);
		$str=str_replace("\\", "", $str);
		$str=str_replace("'", "\\\\'", $str);
		$str=str_replace("[DBLQUOTE]", "\\\"", $str);

		return json_decode($str, true);
	}
}
?>