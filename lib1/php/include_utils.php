<?php
require_once "base_constants.php";

/*
utilities for including css and javascript into html pages.
*/

/*
include some files (css/js)

takes a filename or directory name, or array of them.

use this inside <script> or <style> tags - it outputs the contents of the files
bare.
*/

function load($file, $parent="") {
	if(is_string($file)) {
		$path="$parent$file";

		if(is_dir($path)) {
			$dir=scandir($path);

			foreach($dir as $node) {
				if($node!=="." && $node!=="..") {
					load("/$node", $path);
				}
			}
		}

		else {
			require_once $path;
		}

		echo "\n";
	}

	else if(is_array($file)) {
		foreach($file as $fn) {
			load($fn, $parent);
		}
	}
}

/*
shortcuts for loading files relative to the website's root and the server wide
document root
*/

function loads($file) { //load relative to server root (/var/www/html)
	load($file, SRVROOT);
}

function loadw($file) { //load relative to current website root (/var/www/html/sitename)
	load($file, WWWROOT);
}

/*
output an html tag defined by the given template with a single parameter, "path"

this isn't intended to be called directly - see script_tags_w etc below

$root indicates where to look for the files on the filesystem to check
whether they are directories.  it isn't included in the src attribute of the resulting
script tag (for the obvious reason that no urls have the /var/www bit in)

an apache alias takes care of actually finding the files if the given root is
outside the web root of the current virtualhost.

e.g. /lib is an alias for /var/www/lib
*/

function _tags($tag_template, $file, $root=WWWROOT, $parent="") {
	$path="$parent$file";

	if(is_string($file)) {
		if(is_dir("$root$path")) {
			$dir=scandir("$root$path");

			foreach($dir as $node) {
				if($node!=="." && $node!=="..") {
					_tags($tag_template, "/$node", $root, $path);
				}
			}
		}

		else if(file_exists("$root$path")) {
			echo str_replace("{path}", $path, $tag_template);
		}
	}

	else if(is_array($file)) {
		foreach($file as $fn) {
			_tags($tag_template, $fn, $root, $parent);
		}
	}
}

/*
shortcuts for inserting script/style tags in the web root and the main server document
root.
*/

function script_tags_w($file, $parent="") {
	_tags("<script type=\"text/javascript\" src=\"{path}\"></script>\n", $file, WWWROOT, $parent);
}

function script_tags_s($file, $parent="") {
	_tags("<script type=\"text/javascript\" src=\"{path}\"></script>\n", $file, SRVROOT, $parent);
}

function style_tags_w($file, $parent="") {
	_tags("<link rel=\"stylesheet\" type=\"text/css\" href=\"{path}\">\n", $file, WWWROOT, $parent);
}

function style_tags_s($file, $parent="") {
	_tags("<link rel=\"stylesheet\" type=\"text/css\" href=\"{path}\">\n", $file, SRVROOT, $parent);
}
?>