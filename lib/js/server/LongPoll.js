/*
This is based around two events - GatheringClientState and HaveUpdates.

GatheringClientState is fired to indicate that a request for updates is about
to be sent to the server.  Handler functions for this event should take one
argument, "update", which will point to an Update object (see Update.js).
To register for updates, objects should call update.AddClientData, passing a
reference to themselves, and some other information including an update type
(code type UPDATE_TYPE).

HaveUpdates is fired when the server responds with data, indicating that
updates are available.  The same Update object is passed to handlers of this
event as to GatheringClientState handlers.  The method to call this time
is update.GetUpdates, again passing "this".  If there are updates available
for the object, they will be returned, otherwise null is returned.

That's pretty much it.  To get this going just set its Url prop to the
serverside update script and call Start.  Objects can then (or before, it
doesn't matter) start adding handlers for the events - the first request
will be sent as soon as GatheringClientState has a handler.

Details of what the serverside script should output can be found in Update.js,
but it's basically a serialised object containing the server time and a list
of update info indexed by an internal id for each object which is maintained
by Update.  The actual update info is arbitrary; the details of what each
update should look like just depends on the client side code.

NOTE the browser will not send an XMLHttpRequest if there is another in
progress for the same url with the same query string.

Also, chrome will keep the loading icon spinning forever if it is started
before or on window.load.  It needs a small setTimeout first (50ms seems to
be enough)

NOTE the request is re-sent every time a new handler is added to the
GatheringClientState event, which means sending everything again.  To prevent
a snowball effect occuring when multiple handlers are added (such as when a
LiveTable sets up four Seats for a bughouse game): call Stop, set everything
up, then if it was running before, call Start.  That way there will only be
one extra request instead of four.
*/

function LongPoll() {
	this.GatheringClientState=new Event(this);
	this.HaveUpdates=new Event(this);
	this.Update=null;
	this.url=null;

	/*
	if the server crashes or keeps responding instantly for some reason,
	these short_poll_ variables allow the requests to be throttled accordingly
	*/

	this.short_poll_threshold=200; //if it returns in less time than this it's too short
	this.short_polls=0; //for detecting a build-up of instant responses, which could mean trouble
	this.short_poll_limit=3;
	this.short_poll_timeout_period=1000; //wait for this long before sending again if too many have built up

	this.Url=new Property(this, function() {
		return this.url;
	}, function(value) {
		this.url=value;

		if(this.poll) {
			this.send();
		}
	});

	this.Running=new Property(this, function() {
		return this.poll;
	});

	/*
	send again if the client state changes
	*/

	this.GatheringClientState.HandlerAdded.AddHandler(this, function(handler) {
		this.Resend();
	});

	this.poll=false; //allow mutual recursion between send and recv (recv calls send if true)
	this.req_id=0; //recvs called with outdated req_ids don't call send
}

LongPoll.prototype.Pause = function(fn, bind) {
	this.poll = false;
	fn.call(bind);
	this.poll = true;
	this.send();
}

LongPoll.prototype.Start=function() {
	this.poll=true;
	this.send();
}

LongPoll.prototype.Stop=function() {
	this.poll=false;
}

LongPoll.prototype.Resend=function() {
	if(this.poll) {
		this.send();
	}
}

LongPoll.prototype.send=function() {
	if(this.poll) {
		var self=this;
		this.req_id++;
		var req_id=this.req_id;

		/*
		let everyone who has registered interest know that there's a server
		update about to be requested so they can add their parameters.
		*/

		this.Update=new Update();

		this.GatheringClientState.Fire(this.Update);

		if(this.Update.HasClientState()) {
			Xhr.GetAsync(this.url, function(response) {
				self.recv(response, req_id);
			}, {
				"q": Data.Serialise(this.Update.ClientState), //the query
				"ts": mtime(), //stops caching
				"id": window.self.name //(name persists on refresh, so only one longpoll is kept per tab)
			});

			this.Update.SendTime=mtime();
		}
	}
}

LongPoll.prototype.recv=function(response, req_id) {
	var data=Data.Unserialise(response);
	var self=this;

	/*
	req_id check - makes sure only the last (most up-to-date query)
	req sent gets its result processed
	*/

	if(req_id===this.req_id) {
		if(data===null) { //server error, lost connection, apache restart etc
			if(this.poll) {
				setTimeout(function() {
					self.send();
				}, this.short_poll_timeout_period);
			}
		}

		else {
			this.Update.LoadResponse(data["data"]);
			this.Update.ServerTime=data["server_time"];
			this.Update.RecvTime=mtime();
			this.Update.CalculateTime();
			this.HaveUpdates.Fire(this.Update);

			if(this.poll) {
				if(this.Update.RoundTripTime<this.short_poll_threshold) {
					this.short_polls++;

					if(this.short_polls>this.short_poll_limit) {
						setTimeout(function() {
							self.send();
						}, this.short_poll_timeout_period);
					}

					else {
						this.send();
					}
				}

				else {
					this.send();
				}
			}
		}
	}
}