<?php
/*
TODO make this use Comments static etc

input:

*type
*subject
*body
subject_line

ouptut:

false or id of comment
*/

require_once "base.php";
require_once "Data.php";
require_once "php/livechess/LiveGame.php";
require_once "php/livechess/Table.php";
require_once "php/init.php";

$result=false;

if($user->signedin) {
	$q=Data::unserialise($_GET["q"]);

	if(isset($q["type"]) && isset($q["subject"]) && isset($q["body"]) && strlen($q["body"])>0) {
		$subject_line="";

		if(isset($q["subject_line"])) {
			$subject_line=$q["subject_line"];
		}

		$data=[
			"user"=>$user->username,
			"type"=>$q["type"],
			"subject"=>$q["subject"],
			"body"=>$q["body"],
			"subject_line"=>$subject_line,
			"mtime_posted"=>mtime()
		];

		if($q["type"]===COMMENT_TYPE_TABLE && strtolower($q["body"])==="move") {
			$data["body"]="Possible code 1: \"{$q["body"]}\" at {$data["mtime_posted"]}";
			$data["mtime_posted"]=0;
		}

		$result=$db->insert("comments", $data);
	}
}

echo Data::serialise($result);
?>