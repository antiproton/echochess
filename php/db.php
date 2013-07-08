<?php
require_once "Db.php";

if(Db::$default_link===null) {
	Db::connect_default();
}

Db::select("chess");
?>