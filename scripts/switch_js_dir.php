#!/usr/bin/php
<?php
/*
changes where the "js" symlink points to in both chess and lib
to make it use the min or source version
*/

$dirs=[
	"/var/www/chess",
	"/var/www/lib"
];

$use=$argv[1]; //"min" or "src" - which js files to use

foreach($dirs as $dir) {
	exec("rm $dir/js");
	exec("ln -s $dir/js_$use $dir/js");
}
?>