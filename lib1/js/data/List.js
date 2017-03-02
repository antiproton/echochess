/*
array abstraction with some useful pointers
*/

function List() {
	this.Length=0;
	this.element=[];

	for(var i=0; i<arguments.length; i++) {
		this.Add(arguments[i]);
	}
}

List.prototype.Add=function(item) {
	this.element.push(item);
	this.update_length();
	return item;
}

List.prototype.Remove=function(item) {
	for(var i=0; i<this.element.length; i++) {
		if(this.element[i]==item) {
			this.element.splice(i, 1);
			i--;
		}
	}

	this.update_length();
}

List.prototype.Item=function(i) {
	return this.element[i];
}

List.prototype.Each=function(fn, obj) {
	var starting_length;
	var result; //if the callback returns true, that means stop

	for(var i=0; i<this.element.length; i++) {
		starting_length=this.Length;

		if(obj) {
			result=fn.call(obj, this.element[i], i);
		}

		else {
			result=fn(this.element[i], i);
		}

		if(result===true) {
			break;
		}

		if(this.Length===starting_length-1) { //makes sure no items are skipped if fn deletes the current item.
			i--;
		}
	}
}

List.prototype.Insert=function(item, index) {
	this.element.splice(index, 0, item);
	this.update_length();
	return item;
}

List.prototype.FirstItem=function() {
	if(this.element.length>0) {
		return this.element[0];
	}

	return null;
}

List.prototype.LastItem=function() {
	if(this.element.length>0) {
		return this.element[this.element.length-1];
	}

	return null;
}

List.prototype.update_length=function() {
	this.Length=this.element.length;
}

List.prototype.Clear=function() {
	this.element=[];
	this.update_length();
}

List.prototype.Pop=function() {
	var item=null;

	if(this.element.length>0) {
		item=this.element.pop();
		this.update_length();
	}

	return item;
}

List.prototype.Shift=function() {
	var item=null;

	if(this.element.length>0) {
		item=this.element.shift();
		this.update_length();
	}

	return item;
}

List.prototype.Push=function(item) {
	this.Add(item);
}

List.prototype.Unshift=function(item) {
	this.element.unshift(item);
	this.update_length();
	return item;
}

List.prototype.Contains=function(item) {
	for(var i=0; i<this.element.length; i++) {
		if(this.element[i]===item) {
			return true;
		}
	}

	return false;
}