<?php
/*
init.php - user information and basic requires for all pages and xhrs.
*/

session_start();

require_once "base.php";
require_once "date.php";
require_once "Db.php";
require_once "Clean.php";
require_once "php/User.php";
require_once "php/constants.php";

Clean::init();

$db=Db::getinst();

$db->connect("localhost", "root", "q");
$db->select("echochess");

$user=User::getinst();

$user->session_login();
?>