<?php
if($session->page->url_path==="/register" && $session->user->signedin) {
	msg("register success {$_SERVER["REMOTE_ADDR"]}");
}
?>