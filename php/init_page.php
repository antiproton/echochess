<?php
/*
init_page.php - init.php plus some extra code specific to viewable pages
*/

require_once "base.php";
require_once "Page.php";
require_once "User.php";
require_once "JsRequestInfo.php";
require_once "php/init.php";

$page=Page::getinst();
$page->docroot=WWWROOT;
$page->prefix="/page";
$page->index="tabs";
$page->load(REL_REQ);

if(isset($_POST["signin"])) {
	$user=User::getinst();
	$user->sign_in($_POST["username"], $_POST["password"]);
}

JsRequestInfo::$data["www_dir"]=WWW_DIR;
?>