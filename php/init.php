<?php
/*
init.php - user information and basic requires for all pages and xhrs.
*/

require_once "base.php";
require_once "date.php";
require_once "Db.php";
require_once "php/User.php";
require_once "php/constants.php";

$db=Db::getinst();

$db->connect("localhost", "root", "q");
$db->select("chess");

$user=User::getinst();

$user->session_login();
?>