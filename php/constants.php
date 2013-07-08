<?php
require_once "define_functions.php";

define("OPENING_STATS_MAX_DEPTH", 20);
define("ANALYSIS_MOVETIME_MAX", 5000);

define("QUICK_CHALLENGE_SEEK_TIMEOUT", 10);

/*
how many rows to display in the table lists
*/

define("TABLE_LIST_LIMIT_QUICK", 20);
define("TABLE_LIST_LIMIT_CUSTOM", 20);

//NOTE not sure where the 40/-40 figures have come from
//probably better not to bother with provisional ratings anyway

define("PROVISIONAL_LIMIT", 0); //number of games someone has to play to get proper rating
define("PROVISIONAL_RATING_POINTS_WIN", 40);
define("PROVISIONAL_RATING_POINTS_DRAW", 0);
define("PROVISIONAL_RATING_POINTS_LOSS", -40);

//NOTE LONGPOLL_DELAY also used for quick challenge accepted check

define("LONGPOLL_DELAY", 0.1);
define("LONGPOLL_TIMEOUT", 60);

/*
number of seconds someone has to be disconnected for their
opponent to force them to resign

there is also a limit to how long a game can be before the user
can just quit without being forced to resign (you shouldn't have
to keep the browser open all the time for a correspondence game)
*/

define("MIN_DC_TIME_TO_FORCE_RESIGN", 30);
define("LONGEST_GAME_TO_RESIGN_IF_QUIT", 60*60*2);

/*
prop name to use for update type parameter - used in updates.php,
must match Updates.TYPE_PROP on the client and be something that
isn't used for other params that the objects might want to add.
*/

define("UPDATES_TYPE_PROP", "t");

define("BUGHOUSE_CLOCK_START_DELAY", 2);
?>