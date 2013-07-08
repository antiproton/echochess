<?php
/*
for maintaining a record of when a particular thing was last updated,
the last time an action was performed, etc.  the types of thing are
in codes under the type GENERIC_UPDATE

a typical use for this would be for pages where each user should only
have one open at a time
*/

class GenericUpdates {
	public static function update($type, $user) {
		$mtime=mtime();

		Db::insert_or_update("generic_updates", [
			"type"=>$type,
			"user"=>$user,
			"last_updated"=>$mtime
		]);

		return $mtime;
	}

	public static function get_last_update($type, $user) {
		return Db::cell("select last_updated from generic_updates where user='$user' and type='$type'");
	}
}
?>