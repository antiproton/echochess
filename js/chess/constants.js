var FEN_PIECES = "pnbrqkPNBRQK";
var FEN_SEPARATOR = " ";
var FEN_POS_SEPARATOR = "/";
var FEN_NONE = "-";
var FEN_ACTIVE_WHITE = "w";
var FEN_ACTIVE_BLACK = "b";

var FEN_WHITE_CASTLE_KS = "K";
var FEN_WHITE_CASTLE_QS = "Q";
var FEN_BLACK_CASTLE_KS = "k";
var FEN_BLACK_CASTLE_QS = "q";

var FEN_WHITE_CASTLE_A = "A";
var FEN_WHITE_CASTLE_B = "B";
var FEN_WHITE_CASTLE_C = "C";
var FEN_WHITE_CASTLE_D = "D";
var FEN_WHITE_CASTLE_E = "E";
var FEN_WHITE_CASTLE_F = "F";
var FEN_WHITE_CASTLE_G = "G";
var FEN_WHITE_CASTLE_H = "H";
var FEN_BLACK_CASTLE_A = "a";
var FEN_BLACK_CASTLE_B = "b";
var FEN_BLACK_CASTLE_C = "c";
var FEN_BLACK_CASTLE_D = "d";
var FEN_BLACK_CASTLE_E = "e";
var FEN_BLACK_CASTLE_F = "f";
var FEN_BLACK_CASTLE_G = "g";
var FEN_BLACK_CASTLE_H = "h";

var SIGN_CASTLE_KS = "O-O";
var SIGN_CASTLE_QS = "O-O-O";
var SIGN_CAPTURE = "x";
var SIGN_CHECK = "+";
var SIGN_MATE = "#";
var SIGN_PROMOTE = " = ";
var SIGN_BUGHOUSE_DROP = "@";

var WHITE_PIECES = "PNBRQK";
var BLACK_PIECES = "pnbrqk";

var FEN_INITIAL = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";

var FEN_FIELD_POSITION = 0;
var FEN_FIELD_ACTIVE = 1;
var FEN_FIELD_CASTLING = 2;
var FEN_FIELD_EP = 3;
var FEN_FIELD_CLOCK = 4;
var FEN_FIELD_FULLMOVE = 5;

var RANK = "12345678";
var FILE = "abcdefgh";

var WHITE = 0;
var BLACK = 1;
var SCORE_DRAW = 0.5;

var SQ_EMPTY = 0x0;

var PAWN = 0x1;
var KNIGHT = 0x2;
var BISHOP = 0x3;
var ROOK = 0x4;
var QUEEN = 0x5;
var KING = 0x6;

var WHITE_PAWN = 0x1;
var WHITE_KNIGHT = 0x2;
var WHITE_BISHOP = 0x3;
var WHITE_ROOK = 0x4;
var WHITE_QUEEN = 0x5;
var WHITE_KING = 0x6;

var BLACK_PAWN = 0x9;
var BLACK_KNIGHT = 0xA;
var BLACK_BISHOP = 0xB;
var BLACK_ROOK = 0xC;
var BLACK_QUEEN = 0xD;
var BLACK_KING = 0xE;

var TYPE = ~8;
var COLOUR = 3;

var KINGSIDE = 0;
var QUEENSIDE = 1;

var CASTLING_NONE = 0;