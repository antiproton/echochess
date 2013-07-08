<?php
/*
restore the contents of MEMORY tables after a crash/restart

do anything else necessary to get the site back up and running

pieces_taken from capture moves
tables and seats from games
auto inc fields will be going from 1 again, so update references

also after just apache restarting, remove dead longpolls (they will
be gone anyway if mysql restarts as well)
*/
?>