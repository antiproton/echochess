<?php
/*
check premoves:

*gid

output:

nothing

premove_push asks for a request to this to be sent at the game start
proper time (after the clock start delay) if the game has not started
yet when the premove is made.

NOTE security shouldn't be a problem here (someone would have to have
"chess" in their hosts and then guess the full path)
*/

require_once "base.php";
require_once "php/livechess/LiveGame.php";

$gid=$_GET["gid"];

$game=new LiveGame($gid);

$now=mtime();
$game_start=$game->mtime_start+($game->clock_start_delay*MSEC_PER_SEC);
$timeout=$game_start-$now;

if($timeout>0) {
	usleep($timeout*USEC_PER_MSEC);
}

$game->check_premoves();
$game->save();
?>