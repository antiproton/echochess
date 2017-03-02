<?php
/*
define_range - define a sequence of integer constants e.g. array keys.
if they have a common prefix it can be supplied in the second argument
*/

function define_range($arr, $prefix="") {
	foreach($arr as $i=>$name) {
		define($prefix.$name, $i);
	}
}

function define_assoc($arr, $prefix="") {
	foreach($arr as $name=>$value) {
		define($prefix.$name, $value);
	}
}
?>
