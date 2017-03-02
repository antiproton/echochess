var USEC_PER_SEC=1000000;
var USEC_PER_MSEC=1000;
var MSEC_PER_SEC=1000;

var	SET_TIMEOUT_MIN_DELAY=50; //below this, there's no point using setTimeout or setInterval

var X=0;
var Y=1;

var TOP=1;
var LEFT=2;
var BOTTOM=4;
var RIGHT=8;

var TYPE_ARRAY=0;
var TYPE_OBJECT=1;
var TYPE_STRING=2;
var TYPE_NUMBER=3;
var TYPE_BOOL=4;
var TYPE_NULL=5;
var TYPE_UNDEFINED=6;

var JSON_OBJ_OPEN="{";
var JSON_OBJ_CLOSE="}";
var JSON_STRING_DELIM="\"";
var JSON_PROP_DELIM="\"";
var JSON_ARRAY_OPEN="[";
var JSON_ARRAY_CLOSE="]";
var JSON_PROP_VAL_SEP=":";
var JSON_ITEM_SEP=",";
var JSON_ESCAPE_CHAR="\\";
var JSON_TRUE="true";
var JSON_FALSE="false";
var JSON_NULL="null";
var JSON_NUMBER_RE=/[\-0-9\.\+eE]+/;
var JSON_DEC_POINT=".";