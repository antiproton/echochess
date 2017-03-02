/*
some utilities for dealing with objects and extending classes.

also stuff for checking types (is_number etc)
*/

function obj_set(original, obj) {
	for(var p in obj) {
		if(obj.hasOwnProperty(p)) {
			original[p]=obj[p];
		}
	}
}

function clone(obj) {
	var temp=(obj instanceof Array)?[]:{};

	for(var p in obj) {
		if(obj[p] instanceof Object && !(obj[p] instanceof Function)) {
			temp[p]=clone(obj[p]);
		}

		else {
			temp[p]=obj[p];
		}
	}

	return temp;
}

function prototype_copy(parent, child, override) {
	for(var p in parent.prototype) {
		if(!(p in child) || override) {
			child[p]=parent.prototype[p];
		}
	}
}

/*
ChildConstructor.extend(ParentConstructor1, ...)

methods from parent constructors are copied into ChildConstructor's prototype
if they don't exist already.
*/

Function.prototype.extend=function() {
	for(var i=0; i<arguments.length; i++) {
		prototype_copy(arguments[i], this.prototype, false);
	}
}

/*
NOTE it would apparently make more sense to do:

Object.prototype.implement=function(parent) {

}

but then for..in would pick up "implement" every time unless
hasOwnProperty was used.

implement leaves existing methods alone; override overwrites them.
*/

Function.prototype.implement=function(obj) {
	prototype_copy(this, obj, false);
	this.call.apply(this, arguments);
}

Function.prototype.override=function(obj) {
	prototype_copy(this, obj, true);
	this.call.apply(this, arguments);
}

function Property(obj, get, set) {
	this.Get=function() {
		return get.apply(obj, arguments);
	}

	if(is_function(set)) {
		this.Set=function() {
			set.apply(obj, arguments);
		}
	}

	else {
		this.Set=function(value) {
			throw "Cannot change value from "+this.Get()+" to "+value+": property is read-only";
		};
	}
}

function is_object(value) {
	if(value!==undefined) {
		return (value instanceof Object && value!==null && !(value instanceof Number) && !(value instanceof String));
	}

	return false;
}

function is_string(value) {
	if(value!==undefined) {
		return (!!value.substring);
	}

	return false;
}

function is_number(value) {
	if(value!==undefined && value!==null && !isNaN(value)) {
		return (!!value.toFixed);
	}

	return false;
}

function is_null(value) {
	if(value!==undefined) {
		return (value===null);
	}

	return false;
}

function is_bool(value) {
	if(value!==undefined) {
		return (value===true || value===false);
	}

	return false;
}

function is_array(value) {
	if(value!==undefined) {
		return (value instanceof Array);
	}

	return false;
}

function is_undefined(value) {
	return (value===undefined);
}

function is_function(value) {
	if(value!==undefined) {
		return (value instanceof Function);
	}

	return false;
}

function type(value) {
	if(is_array(value)) {
		return TYPE_ARRAY;
	}

	if(is_null(value)) {
		return TYPE_NULL;
	}

	if(is_bool(value)) {
		return TYPE_BOOL;
	}

	if(is_number(value)) {
		return TYPE_NUMBER;
	}

	if(is_string(value)) {
		return TYPE_STRING;
	}

	if(is_undefined(value)) {
		return TYPE_UNDEFINED;
	}

	if(is_object(value)) {
		return TYPE_OBJECT;
	}
}

function is_empty_object(obj) {
	for(var p in obj) {
		if(obj.hasOwnProperty(p)) {
			return false;
		}
	}

	return true;
}