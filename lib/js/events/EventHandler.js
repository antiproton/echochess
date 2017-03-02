/*
Event handlers can be single-use, which means they are removed as soon
as they are encountered in the list of handlers.  This is done in the
Event class.
*/

function EventHandler(handler, event, fn) {
	this.Handler=handler;
	this.Event=event;
	this.Fn=fn;
}

EventHandler.prototype.Exec=function(data) {
	if(this.Handler && this.Fn) {
		return this.Fn.call(this.Handler, data, this.Event.Sender);
	}
}