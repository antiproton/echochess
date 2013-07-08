<?php
/*
init_page.php - init.php plus some extra code specific to viewable pages
*/

require_once "base.php";
require_once "Page.php";
require_once "php/init.php";
require_once "php/JsRequestInfo.php";
require_once "php/UserPrefs.php";

$session->page=new Page(WWWROOT, "/page");
$session->page->index="tabs";
$session->page->load(REL_REQ);

if(isset(Clean::$post["signin"])) {
	$session->user->sign_in(Clean::$post["username"], Clean::$post["password"]);
}

JsRequestInfo::$data["www_dir"]=WWW_DIR;
?>