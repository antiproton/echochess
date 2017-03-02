function EventHandler(handler, event, fn) {
	this.handler = handler;
	this.event = event;
	this.fn = fn;
}

EventHandler.prototype.exec = function(data) {
	if(this.handler && this.fn) {
		return this.fn.call(this.handler, data, this.event.sender);
	}
}

function Event(sender) {
	this.sender = sender;
	this._handlers = new List();
}

Event.prototype.fire = function(data) {
	this._handlers.each(function(item) {
		var result = item.exec(data);

		if(result === true) { //the handler can return true to indicate that it is no longer needed
			this._handlers.remove(item);
		}
	}, this);

	return data;
}

Event.prototype.addHandler = function(handler, fn) {
	this._handlers.add(new EventHandler(handler, this, fn));

	return fn;
}

/*
removeHandler - remove all handlers an object (handler) has added, or just one of
them (fn)
*/

Event.prototype.removeHandler = function(handler, fn) {
	this._handlers.each(function(item) {
		if(item.handler === handler && (!fn || item.fn === fn)) {
			this._handlers.remove(item);
		}
	}, this);
}

Event.prototype.clearHandlers = function() {
	this._handlers.clear();
}

Event.prototype.setHandler = function(handler, fn) {
	this._handlers.clear();

	this.addHandler(handler, fn);

	return fn;
}