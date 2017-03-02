/*
Events have an event (HandlerAdded), which is fired when a handler is
added for the event.  the HandlerAdded event doesn't have any events.

Events can be fired "at" a particular object so that only that object's
event handlers get executed.

The handler function can return true to signal that the event handler is
no longer needed.  This functionality replaces the old single_use parameter
which wasn't as flexible.
*/

function Event(sender, is_handler_added) {
	this.Sender=sender;
	this.HandlerAdded=null;
	this.handlers=new List();

	if(!is_handler_added) {
		this.HandlerAdded=new Event(this, true); //is_handler_added to avoid inf. recursion
	}
}

Event.prototype.Fire=function(data, at) {
	at=at||null;

	this.handlers.Each(function(item) {
		if(at===null || item.Handler==at) {
			var result=item.Exec(data);

			if(result===true) {
				this.handlers.Remove(item);
			}
		}
	}, this);
}

Event.prototype.AddHandler=function(handler, fn) {
	this.handlers.Add(new EventHandler(handler, this, fn));

	if(this.HandlerAdded instanceof Event) {
		this.HandlerAdded.Fire(handler);
	}

	if(handler.EventsHandled) {
		handler.EventsHandled.push(this);
	}
}

/*
RemoveHandler - pass a handler and a function or just a handler.  if just
a handler is passed, all its event handlers will be deleted.  if a function
is passed, only that one will be deleted.
*/

Event.prototype.RemoveHandler=function(handler, fn) {
	this.handlers.Each(function(item) {
		if(item.Handler===handler && (!(fn instanceof Function) || item.Fn===fn)) {
			this.handlers.Remove(item);
		}
	}, this);
}

Event.prototype.ClearHandlers=function() {
	this.handlers.Clear();
}

Event.prototype.SetHandler=function(handler, fn) {
	this.handlers.Clear();
	this.AddHandler(handler, fn);
}