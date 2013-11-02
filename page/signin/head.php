<?php
require_once "Page.php";
require_once "php/User.php";

$page=Page::getinst();
$user=User::getinst();

if($page->url_path==="/signin" && $user->signedin) {
	$page->load("/");
}
?>