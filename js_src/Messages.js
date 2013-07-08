/*
give it a message type and a subject and wait for Updates

NOTE

a=new Messages();
b=new Messages(MESSAGE_TYPE_SOMETHING);

a will receive all messages; b won't receive anything

b=new Messages(MESSAGE_TYPE_SOMETHING);
a=new Messages();

a receives all messages where there isn't already a handler
added for the specific type
*/

function Messages(type, subject) {
	IEventHandlerLogging.implement(this);

	this.type=type||null;
	this.subject=subject||null;

	this.Update=new Event(this);

	this.start_updates();
}

Messages.prototype.start_updates=function() {
	Base.LongPoll.GatheringClientState.AddHandler(this, function(update) {
		update.AddClientData(this, UPDATE_TYPE_MESSAGES, {
			"type": this.type,
			"subject": this.subject
		});
	});

	Base.LongPoll.HaveUpdates.AddHandler(this, function(update) {
		var data=update.GetUpdates(this);

		if(data!==null) {
			this.Update.Fire({
				Data: data
			});
		}
	});
}

Messages.prototype.Die=function() {
	this.ClearEventHandlers();
}