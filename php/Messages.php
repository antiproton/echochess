<?php
/*
Messages - simple non-critical messages to and from users

invites, rematch offers etc.  all messages are deleted as soon as they
have been received, and old messages are deleted periodically

the messages table is a memory table so all messages are lost on reboot

the subject field should be an id, e.g. for rematch offers the subject
would be the table.

the body can be any note attached to the message (char 255).  it can be
used for chat messages that don't need to be kept after they're received.
*/

require_once "base.php";
require_once "db.php";

class Messages {
	const MESSAGE_MAX_AGE_SECONDS=5;

	/*
	send a message
	*/

	public static function send($from, $to, $type, $subject=null, $body=null) {
		return Db::insert("messages", [
			"sender"=>$from,
			"recipient"=>$to,
			"type"=>$type,
			"subject"=>$subject,
			"body"=>$body,
			"mtime_sent"=>mtime()
		]);
	}

	/*
	get a list of messages for a user, and then delete them from
	the database if there are any
	*/

	public static function retrieve($user, $type=null, $subject=null, $sender=null) {
		$where=[
			"recipient"=>$user
		];

		if($type!==null) {
			$where["type"]=$type;
		}

		if($subject!==null) {
			$where["subject"]=$subject;
		}

		if($sender!==null) {
			$where["sender"]=$sender;
		}

		$msgs=Db::table("
			select sender, type, subject, body, mtime_sent
			from messages
			where sender!='$user' and ".Db::where_string($where, false)."
		");

		if($msgs!==false && count($msgs)>0) {
			self::delete($user, $type, $subject, $sender);
		}

		return $msgs;
	}

	/*
	delete a particular user's messages
	*/

	public static function delete($user, $type=null, $subject=null, $sender=null) {
		$where=[
			"recipient"=>$user
		];

		if($type!==null) {
			$where["type"]=$type;
		}

		if($subject!==null) {
			$where["subject"]=$subject;
		}

		if($sender!==null) {
			$where["sender"]=$sender;
		}

		Db::remove("messages", $where);
	}

	/*
	delete messages over a certain age
	*/

	public static function cleanup() {
		$cutoff=mtime()-(self::MESSAGE_MAX_AGE_SECONDS*MSEC_PER_SEC);
		Db::query("delete from messages where mtime_sent<$cutoff");
	}
}
?>