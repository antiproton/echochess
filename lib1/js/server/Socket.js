function Socket() {
	this.socket = null;
}

Socket.instance = null;

Socket.GetInst = function() {
	if(this.instance === null) {
		this.instance = new this();
	}

	return this.instance;
}

/*
create a new WebSocket to the specified URL and start listening for
messages.  the structure of the response is assumed to be an object where
each key is a channel identifier and its value is some data related to that
channel.  the channel identifiers are arbitrary, but since the websocket
connection represents the whole server, the idea is that channels fulfill
the role of URLs, e.g. /table/319 to get updates about table 319
*/

Socket.prototype.Connect = function(url) {
	var self = this;

	if(this.socket !== null) {
		this.socket.close();
	}

	this.socket = new WebSocket(url);

	this.socket.addEventListener("message", function(message) {
		var data = Data.Unserialise(message);

		for(var channel in data) {
			if(channel in this.handlers) {
				this.handlers[channel].Each(function(handler) {
					handler(data[channel]);
				});
			}
		}
	});
}

/*
register a handler for when the server sends a message on a particular channel.
*/

Socket.prototype.Subscribe = function(channel, handler) {
	this.do_when_open(function() {
		if(!(channel in this.handlers)) {
			this.handlers[channel] = new List();
		}

		this.handlers[channel].Add(handler);
		this.initial_request(channel);
	});

	return handler;
}

/*
unregister a handler
*/

Socket.prototype.Unsubscribe = function(channel, handler) {
	this.do_when_open(function() {
		if(channel in this.handlers) {
			this.handlers[channel].remove(handler);
		}
	});
}

/*
send some data to the server.  this accepts arbitrary unserialised data.
*/

Socket.prototype.Send = function(data) {
	this.do_when_open(function() {
		this.socket.send(Data.Serialise(data));
	});
}

/*
get all the data from a channel so that subsequent incremental updates make sense
*/

Socket.prototype.initial_request = function(channel) {
	this.Send(channel);
}

/*
do something, either now if the connection is open, or when it opens if not.
*/

Socket.prototype.do_when_open = function(callback) {
	var self = this;

	if(this.socket.readyState === WebSocket.OPEN) {
		callback.call(this);
	}

	else {
		this.socket.addEventListener("open", function() {
			callback.call(self);
		});
	}
}

Socket.buildChannel = function() {
	return "/"+Array.prototype.join.call(arguments, "/");
}