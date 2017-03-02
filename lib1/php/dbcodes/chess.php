<?php
/*
Codes from database "chess".

Generated by /home/gus/bin/dbcodes at 22:31, 09/06/2013.
*/

/*
Code types
*/

define("GAME_STATE", "GST");
define("GAME_FORMAT", "GFM");
define("GAME_TYPE", "GTP");
define("VARIANT", "VNT");
define("TIMING", "TIM");
define("RESULT_DETAILS", "RDT");
define("EVENT_TYPE", "EVT");
define("SEAT_TYPE", "STT");
define("MESSAGE_TYPE", "MSG");
define("RESULT", "RES");
define("COMMENT_TYPE", "CMT");
define("UPDATE_TYPE", "UPT");
define("CHESS960_RANDOMISE", "FRM");
define("SUBVARIANT", "SBV");
define("PERM_TYPE", "PRT");
define("PERM_LEVEL", "PRL");
define("RELATIONSHIP_TYPE", "REL");
define("BOARD_STYLE", "BRD");
define("PIECE_STYLE", "PST");
define("CHALLENGE_TYPE", "CHL");
define("GENERIC_UPDATES", "GUP");

/*
Codes
*/

define("BOARD_STYLE_TOURNAMENT", "TNM");

define("CHALLENGE_TYPE_CUSTOM", "CST");
define("CHALLENGE_TYPE_QUICK", "QCK");

define("COMMENT_TYPE_GAME", "GAM");
define("COMMENT_TYPE_MESSAGE", "MSG");
define("COMMENT_TYPE_TABLE", "TAB");

define("EVENT_TYPE_CASUAL", "CAS");
define("EVENT_TYPE_TOURNAMENT", "TNM");

define("CHESS960_RANDOMISE_EVERY_TIME", "EVT");
define("CHESS960_RANDOMISE_EVERY_OTHER", "EVO");
define("CHESS960_RANDOMISE_ONCE", "ONE");

define("GAME_FORMAT_BLITZ", "BLZ");
define("GAME_FORMAT_BULLET", "BUL");
define("GAME_FORMAT_CORRESPONDENCE", "CSP");
define("GAME_FORMAT_OVERALL", "ALL");
define("GAME_FORMAT_QUICK", "QCK");
define("GAME_FORMAT_STANDARD", "STD");

define("GAME_STATE_SERVER_CANCEL", "SVR");
define("GAME_STATE_USER_CANCEL", "USR");
define("GAME_STATE_FINISHED", "FIN");
define("GAME_STATE_IN_PROGRESS", "IPR");
define("GAME_STATE_PREGAME", "PRE");

define("GAME_TYPE_BUGHOUSE", "BGH");
define("GAME_TYPE_STANDARD", "STD");

define("GENERIC_UPDATES_LIVE_MAIN_WINDOW", "LMW");

define("MESSAGE_TYPE_DRAW_ACCEPT", "DRA");
define("MESSAGE_TYPE_TEAM_CHAT", "BTC");
define("MESSAGE_TYPE_REMATCH_CANCEL", "RCL");
define("MESSAGE_TYPE_DRAW_DECLINE", "DRD");
define("MESSAGE_TYPE_INVITE", "INV");
define("MESSAGE_TYPE_INVITE_DECLINE", "IDE");
define("MESSAGE_TYPE_OPPONENT_CONNECT", "OPC");
define("MESSAGE_TYPE_OPPONENT_DISCONNECT", "ODC");
define("MESSAGE_TYPE_REMATCH_DECLINE", "RDE");
define("MESSAGE_TYPE_REMATCH_OFFER", "REM");

define("PERM_LEVEL_ANYONE", "ANY");
define("PERM_LEVEL_FRIENDS", "FRD");
define("PERM_LEVEL_INVITED", "INV");

define("PERM_TYPE_PLAY", "PLY");
define("PERM_TYPE_WATCH", "WCH");

define("PIECE_STYLE_ALPHA", "ALP");
define("PIECE_STYLE_MERIDA", "MEP");

define("RESULT_DETAILS_DRAW_AGREED", "DRW");
define("RESULT_DETAILS_CHECKMATE", "CHK");
define("RESULT_DETAILS_RESIGNATION", "RES");
define("RESULT_DETAILS_FIFTY_MOVE", "50M");
define("RESULT_DETAILS_INSUFFICIENT", "INS");
define("RESULT_DETAILS_STALEMATE", "STL");
define("RESULT_DETAILS_THREEFOLD", "3FR");
define("RESULT_DETAILS_BUGHOUSE_OTHER_GAME", "BGH");
define("RESULT_DETAILS_TIMEOUT", "TIM");

define("RELATIONSHIP_TYPE_FRIENDS", "FRD");

define("RESULT_BLACK", "B");
define("RESULT_DRAW", "D");
define("RESULT_WHITE", "W");

define("SUBVARIANT_DOUBLE", "DBL");
define("SUBVARIANT_NONE", "NON");

define("SEAT_TYPE_PLAYER", "PLR");
define("SEAT_TYPE_SPECTATOR", "SPC");

define("TIMING_BRONSTEIN_DELAY", "BRN");
define("TIMING_FISCHER", "FCH");
define("TIMING_FISCHER_AFTER", "FAF");
define("TIMING_HOURGLASS", "HRG");
define("TIMING_PER_MOVE", "PER");
define("TIMING_SIMPLE_DELAY", "SDL");
define("TIMING_SUDDEN_DEATH", "SDD");
define("TIMING_NONE", "NON");

define("UPDATE_TYPE_SEAT", "SAT");
define("UPDATE_TYPE_COMMENTS", "CMT");
define("UPDATE_TYPE_DIRECT_CHALLENGE", "DRC");
define("UPDATE_TYPE_GAME", "GAM");
define("UPDATE_TYPE_GENERIC_UPDATES", "GEN");
define("UPDATE_TYPE_HISTORY", "HST");
define("UPDATE_TYPE_MESSAGES", "MSG");
define("UPDATE_TYPE_PIECES_TAKEN", "PCS");
define("UPDATE_TYPE_PREMOVES", "PRE");
define("UPDATE_TYPE_TABLE", "TAB");
define("UPDATE_TYPE_TIME", "TIM");
define("UPDATE_TYPE_UNDO", "TBK");

define("VARIANT_960", "960");
define("VARIANT_STANDARD", "STD");
?>