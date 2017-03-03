/*
obj_to_get - convert an object to a query string
*/

function obj_to_get(obj) {
	var pair=[];
	var value;

	for(var p in obj) {
		if(obj.hasOwnProperty(p)) {
			pair.push(p+"="+encodeURIComponent(obj[p]));
		}
	}

	return pair.join("&");
}

function btoi(b) {
	return b-0;
}

function time() {
	return Math.round(mtime()/MSEC_PER_SEC);
}

function mtime() {
	return Math.round((new Date()).valueOf());
}

function utime() {
	return mtime()*USEC_PER_MSEC;
}

function end(array) {
	return array[array.length-1];
}

/*
this can be used on "arguments" as well as normal arrays
*/

function in_array(item, array) {
	for(var i=0; i<array.length; i++) {
		if(array[i]===item) {
			return true;
		}
	}

	return false;
}

//recursive join method for arrays
//implode([[1, 2], [3, 4]], [".", ","]) returns "1,2.3,4"
//TODO not working

function implode(array, delim, level) {
	if(!level) {
		level=0;
	}

	if(level<delim.length-1) {
		array=[];

		for(var i=0;i<array.length;i++) {
			if(typeof array[i]=="object") {
				array[i]=implode(array[i], delim, ++level);
			}
		}
	}

	return array.join(delim[level]);
}

//recursive split for strings
//explode("st-r/i-ng", ["/", "-"]) returns [["st", "r"], ["i", "ng"]]
//TODO not working

function explode(string, delim, level) {
	if(!level) {
		level=0;
	}

	var current=delim[level];

	var array=string.split(current);

	if (level>=delim.length-1) {
		return array;
	}

	for(var i=0; i<array.length; i++) {
		array[i]=explode(array[i], delim, ++level);
	}

	return array;
}

function ordinal(n) {
	return n+["th", "st", "nd", "rd"][!(n%10>3||Math.floor(n%100/10)==1)*n%10];
}

function csv(obj) {
	var pair=[];

	for(var i in obj) {
		pair.push(i+":"+obj[i]);
	}

	return pair.join(",");
}

//define a range going up by 1 each time (0, 1, 2, 3, ...)

function define_range(arr, prefix) {
	prefix=prefix||"";

	for(var i=0; i<arr.length; i++) {
		this[prefix+arr[i]]=i;
	}
}

//define a range multiplying by 2 each time (1, 2, 4, 8, 16, ...)
//this one is more useful if bitwise operations will be applied

function define_range_exp(arr, prefix) {
	prefix=prefix||"";

	for(var i=0; i<arr.length; i++) {
		this[prefix+arr[i]]=Math.pow(2, i);
	}
}

function define_assoc(obj, prefix) {
	prefix=prefix||"";

	for(var i in obj) {
		this[prefix+i]=obj[i];
	}
}

function s(n) {
	if(n === 1) {
		return "";
	}
	return "s";
}