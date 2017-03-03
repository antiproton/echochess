<?php
require_once "base_constants.php";

/*
number of milli/micro-seconds since epoch as an integer (requires 64bit)
*/

function utime() {
	return (int) (microtime(true)*USEC_PER_SEC);
}

function mtime() {
	return (int) (microtime(true)*MSEC_PER_SEC);
}

function is_cli() {
	return (PHP_SAPI==="cli");
}

function is_web() {
	return (PHP_SAPI==="apache2handler");
}

/*
get an absolute path.

saves one from thinking about whether the website is in a subdirectory of the
server or vitrual host's document root or not.  pass this function a path
written as though the website root is the server root, and it will return the
proper path whether it is or not.
*/

function ap($path="") {		//	subdirectory			virtualhost
	return WWW_DIR.$path;	//	/site/dir/file.html		/dir/file.html
}

/*
log - for debugging
*/

function msg($str, $logfile="/meta/log.txt") {
	//$file=fopen(WWWROOT.$logfile, "a+");
	//fwrite($file, "$str\n");
	//fclose($file);
}
?>