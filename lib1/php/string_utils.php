<?php
/*
for adding s onto plurals
*/

function s($n) {
	return $n==1?"":"s";
}

/*
does $str end with $end?
*/

function endswith($str, $end) {
	return (substr($str, strlen($str)-strlen($end))===$end);
}

/*
does $str start with $beginning?
*/

function startswith($str, $beginning) {
	return (substr($str, 0, strlen($beginning))===$beginning);
}
?>