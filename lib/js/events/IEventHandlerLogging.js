/*
IEventHandlerLogging

when an object adds a handler for an event, the event will check whether
the object has EventsHandled.  if it does, the event will add itself to
this array.  then later on, if an object wants to un-handle all the events
it has handled, it just has to loop through the list.
*/

function IEventHandlerLogging() {
	if(!this.EventsHandled) { //to stop it clearing it out if it has already been implemented
		this.EventsHandled=[];
	}
}

/*
an object can clear all of the event handlers it's added (no argument) or
specify which object to deregister event handlers for (events that are sent
by that object)
*/

IEventHandlerLogging.prototype.ClearEventHandlers=function(obj) {
	for(var i=0; i<this.EventsHandled.length; i++) {
		if(!obj || this.EventsHandled[i].Sender===obj) {
			this.EventsHandled[i].RemoveHandler(this);
		}
	}
}