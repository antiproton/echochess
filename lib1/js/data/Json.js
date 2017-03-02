function JsonDecoder(str) {
	this.current_char="";
	this.i=-1;
	this.characters=[];
	this.escaped=false;
	this.Error=null;
	this.Success=true;
	this.SetString(str);
}

JsonDecoder.prototype.SetString=function(str) {
	if(str!=undefined && str!="" && str!=null) {
		this.characters=str.split("");
	}

	else {
		this.characters=[];
	}

	this.reset();
}

JsonDecoder.prototype.reset=function() {
	this.i=-1;
	this.current_char="";
}

JsonDecoder.prototype.Decode=function() {
	this.Success=true;
	this.reset();
	this.next();

	var result=null;

	if(this.characters.length>0) {
		try {
			result=this.eval();
		}

		catch(e) {
			this.Error=e;
			this.Success=false;
		}
	}

	return result;
}

JsonDecoder.prototype.eval=function() {
	this.skip_whitespace();

	if(this.eq(JSON_OBJ_OPEN)) {
		return this.eval_object();
	}

	else if(this.eq(JSON_ARRAY_OPEN)) {
		return this.eval_array();
	}

	else if(this.match(JSON_NUMBER_RE)) {
		return this.eval_number();
	}

	else if(this.eq(JSON_STRING_DELIM)) {
		return this.eval_string();
	}

	else if(this.lookahead(JSON_TRUE)) {
		return this.eval_true();
	}

	else if(this.lookahead(JSON_FALSE)) {
		return this.eval_false();
	}

	else if(this.lookahead(JSON_NULL)) {
		return this.eval_null();
	}

	else {
		throw "Unexpected character: "+this.current_char+" at "+this.i;
	}
}

/*
NOTE array and object need to check for JSON_ARRAY_CLOSE/JSON_OBJ_CLOSE
twice to handle both empty and non-empty arrays/objects.  this doesn't
seem right but has been left in for now
*/

JsonDecoder.prototype.eval_object=function() {
	this.skip_whitespace();

	var obj={};
	var prop;

	this.skip_whitespace();
	this.skip(JSON_OBJ_OPEN);

	while(!this.eof()) {
		this.skip_whitespace();

		if(this.eq(JSON_OBJ_CLOSE)) {
			break;
		}

		prop=this.read_prop_name();
		this.skip(JSON_PROP_VAL_SEP);
		obj[prop]=this.eval();
		this.skip_whitespace();

		if(this.eq(JSON_OBJ_CLOSE)) {
			break;
		}

		this.skip_whitespace();
		this.skip(JSON_ITEM_SEP);
	}

	this.skip(JSON_OBJ_CLOSE);

	return obj;
}

JsonDecoder.prototype.eval_array=function() {
	this.skip_whitespace();
	var arr=[];

	this.skip(JSON_ARRAY_OPEN);

	while(true) {
		this.skip_whitespace();

		if(this.eq(JSON_ARRAY_CLOSE)) {
			break;
		}

		arr.push(this.eval());

		this.skip_whitespace();

		if(this.eq(JSON_ARRAY_CLOSE)) {
			break;
		}

		this.skip_whitespace();
		this.skip(JSON_ITEM_SEP);
	}

	this.skip(JSON_ARRAY_CLOSE);

	return arr;
}

JsonDecoder.prototype.eval_number=function() {
	this.skip_whitespace();

	var str="";

	while(this.match(JSON_NUMBER_RE)) {
		str+=this.read();
	}

	var n;

	if(str.indexOf(JSON_DEC_POINT)!==-1) {
		n=parseFloat(str);
	}

	else {
		n=parseInt(str);
	}

	return n;
}

JsonDecoder.prototype.eval_string=function() {
	this.skip_whitespace();

	var str="";

	this.skip(JSON_STRING_DELIM);

	while(this.escaped || !this.eq(JSON_STRING_DELIM)) {
		str+=this.read();
	}

	this.skip(JSON_STRING_DELIM);

	return str;
}

JsonDecoder.prototype.eval_true=function() {
	this.skip_whitespace();

	for(var i=0; i<JSON_TRUE.length; i++) {
		this.skip(JSON_TRUE.charAt(i));
	}

	return true;
}

JsonDecoder.prototype.eval_false=function() {
	this.skip_whitespace();

	for(var i=0; i<JSON_FALSE.length; i++) {
		this.skip(JSON_FALSE.charAt(i));
	}

	return false;
}

JsonDecoder.prototype.eval_null=function() {
	this.skip_whitespace();

	for(var i=0; i<JSON_NULL.length; i++) {
		this.skip(JSON_NULL.charAt(i));
	}

	return null;
}

JsonDecoder.prototype.read_prop_name=function() {
	var str="";

	this.skip_whitespace();
	this.skip(JSON_STRING_DELIM);

	while(!this.eq(JSON_STRING_DELIM)) {
		str+=this.read();
	}

	this.skip(JSON_STRING_DELIM);

	return str;
}

JsonDecoder.prototype.read=function() {
	var str=this.current_char;
	this.next();
	return str;
}

JsonDecoder.prototype.eof=function() {
	return (this.i>=this.characters.length);
}

JsonDecoder.prototype.eq=function(str) {
	return (this.current_char==str);
}

JsonDecoder.prototype.next=function(escaped) {
	escaped=!!escaped;

	this.escaped=escaped;
	this.i++;

	if(this.eof()) {
		this.current_char="";
	}

	else {
		this.current_char=this.characters[this.i];
	}

	if(!escaped && this.eq(JSON_ESCAPE_CHAR)) {
		this.next(true);
	}
}

JsonDecoder.prototype.skip_whitespace=function() {
	while(this.eq(" ") || this.eq("\t") || this.eq("\n")) {
		this.next();
	}
}

JsonDecoder.prototype.skip=function(ch) {
	this.skip_whitespace();

	if(this.current_char==ch) {
		this.next();
	}

	else {
		throw "Expecting "+ch+" at "+this.i;
	}
}

JsonDecoder.prototype.match=function(re) {
	return (this.current_char.match(re)!=null);
}

JsonDecoder.prototype.lookahead=function(str) {
	var current_str="";

	for(var i=this.i; i<this.i+str.length; i++) {
		current_str+=this.characters[i];
	}

	return (current_str==str);
}

function json_encode(value) {
	var encoder={};

	encoder[TYPE_ARRAY]=function(value) {
		var str="";
		var list=[];

		str+=JSON_ARRAY_OPEN;

		for(var i=0; i<value.length; i++) {
			list.push(json_encode(value[i]));
		}

		str+=list.join(JSON_ITEM_SEP);
		str+=JSON_ARRAY_CLOSE;

		return str;
	};

	encoder[TYPE_OBJECT]=function(value) {
		var str="";
		var list=[];

		str+=JSON_OBJ_OPEN;

		for(var p in value) {
			if(value.hasOwnProperty(p)) {
				list.push(JSON_PROP_DELIM+p+JSON_PROP_DELIM+JSON_PROP_VAL_SEP+json_encode(value[p]));
			}
		}

		str+=list.join(JSON_ITEM_SEP);
		str+=JSON_OBJ_CLOSE;

		return str;
	};

	encoder[TYPE_NUMBER]=function(value) {
		return value.toString();
	};

	encoder[TYPE_BOOL]=function(value) {
		return value?JSON_TRUE:JSON_FALSE;
	};

	encoder[TYPE_NULL]=function(value) {
		return JSON_NULL;
	}

	encoder[TYPE_UNDEFINED]=function(value) {
		return JSON_NULL;
	}

	encoder[TYPE_STRING]=function(value) {
		return JSON_STRING_DELIM+value.replace(JSON_STRING_DELIM, JSON_ESCAPE_CHAR+JSON_STRING_DELIM)+JSON_STRING_DELIM;
	}

	return encoder[type(value)](value);
}

function json_decode(str) {
	return (new JsonDecoder(str)).Decode();
}