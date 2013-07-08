<?php
require_once "define_functions.php";

//coords

define_range(["X", "Y"]);

/*
FEN
*/

define("FEN_PIECES", "pnbrqkPNBRQK");
define("FEN_SEPARATOR", " ");
define("FEN_POS_SEPARATOR", "/");
define("FEN_NONE", "-");
define("FEN_ACTIVE_WHITE", "w");
define("FEN_ACTIVE_BLACK", "b");

define("FEN_WHITE_CASTLE_KS", "K");
define("FEN_WHITE_CASTLE_QS", "Q");
define("FEN_BLACK_CASTLE_KS", "k");
define("FEN_BLACK_CASTLE_QS", "q");

define("FEN_WHITE_CASTLE_A", "A");
define("FEN_WHITE_CASTLE_B", "B");
define("FEN_WHITE_CASTLE_C", "C");
define("FEN_WHITE_CASTLE_D", "D");
define("FEN_WHITE_CASTLE_E", "E");
define("FEN_WHITE_CASTLE_F", "F");
define("FEN_WHITE_CASTLE_G", "G");
define("FEN_WHITE_CASTLE_H", "H");
define("FEN_BLACK_CASTLE_A", "a");
define("FEN_BLACK_CASTLE_B", "b");
define("FEN_BLACK_CASTLE_C", "c");
define("FEN_BLACK_CASTLE_D", "d");
define("FEN_BLACK_CASTLE_E", "e");
define("FEN_BLACK_CASTLE_F", "f");
define("FEN_BLACK_CASTLE_G", "g");
define("FEN_BLACK_CASTLE_H", "h");

define("WHITE_PIECES", "PNBRQK");
define("BLACK_PIECES", "pnbrqk");

define("FEN_INITIAL", "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1");

define_range(array(
	"POSITION",
	"ACTIVE",
	"CASTLING",
	"EP",
	"CLOCK",
	"FULLMOVE"),
"FEN_FIELD_");

define("RANK", "12345678");
define("FILE", "abcdefgh");

//results are db codes

define("WHITE", 0);
define("BLACK", 1);

define("SCORE_WIN", 1);
define("SCORE_DRAW", 0.5);
define("SCORE_LOSS", 0);

/*
binary pieces

$piece=BLACK_BISHOP;	//0xB	1011
$type=$piece&TYPE;		//0x3	0011
$colour=$piece>>COLOUR;	//0x1	0001
*/

define("PAWN", 0x1);
define("KNIGHT", 0x2);
define("BISHOP", 0x3);
define("ROOK", 0x4);
define("QUEEN", 0x5);
define("KING", 0x6);

define("SQ_EMPTY", 0x0);

define("WHITE_PAWN", 0x1);
define("WHITE_KNIGHT", 0x2);
define("WHITE_BISHOP", 0x3);
define("WHITE_ROOK", 0x4);
define("WHITE_QUEEN", 0x5);
define("WHITE_KING", 0x6);

define("BLACK_PAWN", 0x9);
define("BLACK_KNIGHT", 0xA);
define("BLACK_BISHOP", 0xB);
define("BLACK_ROOK", 0xC);
define("BLACK_QUEEN", 0xD);
define("BLACK_KING", 0xE);

//bit manipulation constants
//used in piece() (util.php)

define("TYPE", ~8);		//AND to unset colour bit, which gives you the type
define("COLOUR", 3);	//shift right by COLOUR to get colour

define("CHAR_SQ_EMPTY", "_");

define("CHAR_WHITE_PAWN", "P");
define("CHAR_WHITE_KNIGHT", "N");
define("CHAR_WHITE_BISHOP", "B");
define("CHAR_WHITE_ROOK", "R");
define("CHAR_WHITE_QUEEN", "Q");
define("CHAR_WHITE_KING", "K");

define("CHAR_BLACK_PAWN", "p");
define("CHAR_BLACK_KNIGHT", "n");
define("CHAR_BLACK_BISHOP", "b");
define("CHAR_BLACK_ROOK", "r");
define("CHAR_BLACK_QUEEN", "q");
define("CHAR_BLACK_KING", "k");

//general

define("SIGN_CAPTURE", "x");
define("SIGN_PROMOTE", "=");
define("SIGN_CHECK", "+");
define("SIGN_MATE", "#");
define("SIGN_CASTLE_KS", "O-O");
define("SIGN_CASTLE_QS", "O-O-O");
define("SIGN_BUGHOUSE_DROP", "@");

define("NOTE_BRILLIANT", "!!");
define("NOTE_GOOD", "!");
define("NOTE_INTERESTING", "!?");
define("NOTE_DUBIOUS", "?!");
define("NOTE_MISTAKE", "?");
define("NOTE_BLUNDER", "??");

define("FULLMOVE_DOT_WHITE", ".");
define("FULLMOVE_DOT_BLACK", "...");

/*
binary castling offsets.  the whole thing is reversed: FEN KQk = int 0111, FEN
Qk = int 0110.  the functions in util and fen do the reversing (3- ...)

WHITE/BLACK already set to 0/1.  must be multiplied by 2 to get offset (because
it needs to jump over 2 bits -- the KS bit and the QS bit).
*/

define("KINGSIDE", 0);
define("QUEENSIDE", 1);

define("CASTLING_NONE", 0);

//ratings

define("RATING_INITIAL", 1200);
?>