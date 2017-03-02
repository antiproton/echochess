function List() {
	this.length = 0;
	this._elements = [];

	for(var i = 0; i < arguments.length; i++) {
		this.add(arguments[i]);
	}
}

List.prototype.add = function(item) {
	this._elements.push(item);
	this._updateLength();
	return item;
}

List.prototype.remove = function(item) {
	for(var i = 0; i < this._elements.length; i++) {
		if(this._elements[i] === item) {
			this._elements.splice(i, 1);
			i--;
		}
	}

	this._updateLength();
}

List.prototype.item = function(i) {
	if(i in this._elements) {
		return this._elements[i];
	}

	return null;
}

List.prototype.each = function(fn, obj) {
	var startingLength;
	var result; //if the callback returns true, that means stop

	for(var i = 0; i < this._elements.length; i++) {
		startingLength = this.length;

		if(obj) {
			result = fn.call(obj, this._elements[i], i);
		}

		else {
			result = fn(this._elements[i], i);
		}

		if(result === true) {
			break;
		}

		if(this.length === startingLength-1) { //makes sure no items are skipped if fn deletes the current item.
			i--;
		}
	}
}

List.prototype.insert = function(item, index) {
	this._elements.splice(index, 0, item);
	this._updateLength();
	return item;
}

List.prototype.firstItem = function() {
	if(this._elements.length > 0) {
		return this._elements[0];
	}

	return null;
}

List.prototype.lastItem = function() {
	if(this._elements.length > 0) {
		return this._elements[this._elements.length-1];
	}

	return null;
}

List.prototype._updateLength = function() {
	this.length = this._elements.length;
}

List.prototype.clear = function() {
	this._elements = [];
	this._updateLength();
}

List.prototype.pop = function() {
	var item = null;

	if(this._elements.length > 0) {
		item = this._elements.pop();

		this._updateLength();
	}

	return item;
}

List.prototype.shift = function() {
	var item = null;

	if(this._elements.length > 0) {
		item = this._elements.shift();

		this._updateLength();
	}

	return item;
}

List.prototype.push = function(item) {
	this.add(item);
}

List.prototype.unshift = function(item) {
	this._elements.unshift(item);
	this._updateLength();
	
	return item;
}

List.prototype.contains = function(item) {
	for(var i = 0; i < this._elements.length; i++) {
		if(this._elements[i] === item) {
			return true;
		}
	}

	return false;
}