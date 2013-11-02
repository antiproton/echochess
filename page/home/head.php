<?php
require_once "phpUser.php";

$user=User::getinst();

if($page->url_path==="/register" && $user->signedin) {
	msg("register success {$_SERVER["REMOTE_ADDR"]}");
}
?>