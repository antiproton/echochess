function Comments(type, subject) {
	IEventHandlerLogging.implement(this);

	this.comments = [];
	this.mtime_last_post = 0;
	this.Type = type;
	this.Subject = subject;
	this.CommentReceived = new Event(this);
	this.init_load();
}

Comments.prototype.init_load = function() {
	Xhr.QueryAsync(ap("/xhr/comments_load.php"), function(response) {
		if(is_array(response)) {
			var row;

			for(var i = 0; i < response.length; i++) {
				row = response[i];

				this.comments.push(row);
				this.mtime_last_post = row["mtime_posted"];
				this.CommentReceived.Fire(row);
			}
		}

		this.start_updates();
	}, {
		"type": this.Type,
		"subject": this.Subject
	}, this);
}

Comments.prototype.start_updates = function() {
	Base.LongPoll.GatheringClientState.AddHandler(this, function(update) {
		update.AddClientData(this, UPDATE_TYPE_COMMENTS, {
			"type": this.Type,
			"subject": this.Subject,
			"mtime_last_post": this.mtime_last_post
		});
	});

	Base.LongPoll.HaveUpdates.AddHandler(this, function(update) {
		var data = update.GetUpdates(this);

		if(data !== null) {
			var row;

			for(var i = 0; i < data.length; i++) {
				row = data[i];

				this.comments.push(row);
				this.mtime_last_post = row["mtime_posted"];
				this.CommentReceived.Fire(row);
			}
		}
	});
}

Comments.prototype.Post = function(body, subject_line) {
	subject_line = subject_line||"";

	Xhr.RunQueryAsync(ap("/xhr/comment_post.php"), {
		"type": this.Type,
		"subject": this.Subject,
		"body": body,
		"subject_line": subject_line
	});
}

Comments.prototype.Die = function() {
	this.ClearEventHandlers();
}