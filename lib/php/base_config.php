<?php
require_once "base_constants.php";
require_once "base_util.php";

/*
put useful directories on the include path
*/

if(!is_cli()) {
	ini_set("include_path", ini_get("include_path").":".WWWROOT.":".DOCROOT.":".SRVROOT);
}
?>