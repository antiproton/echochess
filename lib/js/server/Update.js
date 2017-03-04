/*
here's what this is for:

LongPoll fires the GatheringClientState event, which contains
an Update.

Objects that handle GatheringClientState call
data.Update.AddClientData(this, some_data).

The update is sent to the server.

The serverside code looks at the query, which will be an assoc
array of object ids (for internal use by the clientside Update)
and the some_data passed to Update.AddClientData.

some_data will contain a procedure and some parameters (e.g. {
	procedure: "game_update",
	id: 123
}.

The serverside code creates another assoc array, indexed by the
internal ids, containing the responses to each request from the
original array.

The new array is received back on the clientside by LongPoll, which
sends the result to the Update by calling LoadResponse, and then fires
HaveUpdates, passing the Update again.

Hopefully the same objects that handled GatheringClientState have
handled HaveUpdates as well.

When they get this event they call data.Update.GetUpdates(this)
to get the data meant for them (or null if there isn't any).

The update can calculate the round trip time and make an estimate
of how long each leg took (half of the round trip time), but its
SendTime and RecvTime have to be set from outside for this to
work (this and a few other things point to LongPoll and Update
being merged into the same thing, but that's for later).

TODO the times should be called *Mtime
*/

function Update() {
	this.ServerState={};
	this.ClientState={};
	this.ClientObjects={}; //to get references back to the original objects from their ids
	this.last_id=0;
	this.SendTime=0;
	this.RecvTime=0;
	this.RoundTripTime=0;
	this.ServerTime=null;
	this.EstimatedLag=0;
}

Update.prototype.get_id=function() {
	return "_"+(++this.last_id);
}

Update.prototype.AddClientData=function(obj, type, params) {
	params=params||{};
	params.t = type;
	var id=this.get_id();
	this.ClientState[id]=params;
	this.ClientObjects[id]=obj;
}

Update.prototype.LoadResponse=function(response) {
	this.ServerState=response;
}

Update.prototype.GetUpdates=function(obj) {
	for(var id in this.ClientObjects) {
		if(this.ClientObjects[id]==obj && (id in this.ServerState)) {
			return this.ServerState[id];
		}
	}

	return null;
}

Update.prototype.HasClientState=function() {
	return !is_empty_object(this.ClientState);
}

Update.prototype.CalculateTime=function() {
	this.RoundTripTime=this.RecvTime-this.SendTime;
	this.EstimatedLag=Math.round(this.RoundTripTime/2);
}