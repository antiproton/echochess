<?php
/*
init.php - user information and basic requires for all pages and xhrs.
*/

require_once "base.php";
require_once "date.php";
require_once "Clean.php";
require_once "php/constants.php";
require_once "php/Session.php";
require_once "php/db.php";

session_start(); //NOTE only have this if not using symfony sessions

//the only global variable:

$session=new Session();
?>