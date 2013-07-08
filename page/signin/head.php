<?php
if($session->page->url_path==="/signin" && $session->user->signedin) {
	$session->page->load("/");
}
?>