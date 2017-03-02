<?php
require_once "define_functions.php";

/*
Paths

Use these constants to build include paths and link hrefs which work
independently of whether the site is in its own virtualhost and any
dirs it might be nested in.  The CUSTOM: things come from Apache SetEnv
directives.
*/

/*
Constant		Value in subdir			Value in vhost

SRVROOT			/var/www/html			/var/www/html
DOCROOT			/var/www/html			/var/www/html/site
WWW_DIR			/site
WWWROOT			/var/www/html/site		/var/www/html/site
REL_REQ			/dir/file.html			/dir/file.html
*/

if(PHP_SAPI!=="cli") { //only if running in as a web server plugin
	define("SRVROOT", $_SERVER["CUSTOM:SRVROOT"]);
	define("DOCROOT", $_SERVER["DOCUMENT_ROOT"]);
	define("WWW_DIR", $_SERVER["CUSTOM:WWW_DIR"]);
	define("WWWROOT", DOCROOT.WWW_DIR);
	define("REL_REQ", substr($_SERVER["REQUEST_URI"], strlen(WWW_DIR)));
}

/*
time
*/

define("USEC_PER_SEC", 1000000);
define("MSEC_PER_SEC", 1000);
define("USEC_PER_MSEC", 1000);
?>