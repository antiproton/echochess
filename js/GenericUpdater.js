/*
give this a generic update type and the latest update time, and it
will tell you when the update time for that type changes.  this is
literally all it does, no other information is provided.  it is
useful for (and was originally designed as a more generic form of)
checking whether another window of a certain page has been opened
since the current one loaded.
*/

function GenericUpdater(type, last_update) {
	this.Type = type;
	this.LastUpdate = last_update;
	this.Updated = new Event(this);
	this.start_updates();
}

GenericUpdater.prototype.start_updates = function() {
	Base.LongPoll.GatheringClientState.AddHandler(this, function(update) {
		update.AddClientData(this, UPDATE_TYPE_GENERIC_UPDATES, {
			"type": this.Type,
			"last_update": this.LastUpdate
		});
	});

	Base.LongPoll.HaveUpdates.AddHandler(this, function(update) {
		var data = update.GetUpdates(this);

		if(data !== null) {
			if(data["last_update"] > this.LastUpdate) {
				this.LastUpdate = data["update"];
				this.Updated.Fire();
			}
		}
	});
}