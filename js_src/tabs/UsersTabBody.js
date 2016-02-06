function UsersTabBody(parent) {
	Control.implement(this, parent);

	var self = this;

	this.last_xhr = null; //only update if the xhr coming back was the last one to be sent
	this.current_challenge_link = null; //last challenge link clicked

	this.cols = [
		{
			Title: "Username",
			Width: 200,
			Value: "user"
		},
		{
			Title: "Overall rating (standard games)",
			Width: 250,
			Value: "rating"
		},
		{
			Title: "Challenge",
			Width: 200,
			Value: function(row) {
				var challenge_link = $("*a");

				Dom.AddEventHandler(challenge_link, "click", function() {
					this.challenge_user(row["user"], challenge_link);
				}, self);

				challenge_link.href = "javascript:void(0)";
				challenge_link.innerHTML = "Challenge";
				challenge_link.style.color = "#3f3f3f";

				return challenge_link;
			}
		}
	];

	this.SetupHtml();
	this.Update();
}

UsersTabBody.prototype.SetupHtml = function() {
	Dom.Style(this.Node, {
		//padding: 2
	});

	Dom.AddClass(this.Node, "noselect");

	var tmp, con;

	this.panel_container = div(this.Node);

	this.ButtonReload = new Button(this.panel_container, "Reload");

	this.ButtonReload.Click.AddHandler(this, function() {
		this.Update();
	});

	this.LabelUsersOnline = new Label(this.panel_container);

	Dom.Style(this.panel_container, {
		fontSize: 11,
		padding: 3
	});

	this.Grid = new Grid(this.Node, this.cols);

	this.Grid.BeforeRowDraw.AddHandler(this, function(data) {
		Dom.AddClass(data.RowDiv, "inactive");
	});

	//challenge user form

	this.sb_quick_challenge_form = new SpeechBubbleBox(TOP, {
		ArrowHeight: 8,
		Width: 220,
		BorderColour: "#808080"
	});

	this.sb_quick_challenge_form.Display.Set(false);

	this.quick_challenge_form = new QuickChallengeForm(this.sb_quick_challenge_form.Inner);

	this.quick_challenge_form.Width.Set(220);
	this.quick_challenge_form.Padding.Set(5);

	this.quick_challenge_form.Done.AddHandler(this, function(data, sender) {
		if(data.Info === QuickChallenge.SUCCESS) {
			Base.App.OpenTable(data.Table);
			this.sb_quick_challenge_form.Display.Set(false);
		}
	});

	Base.App.BodyClick.AddHandler(this, function() {
		if(!Base.App.ClickedObjects.Contains(this.current_challenge_link) && !Base.App.ClickedObjects.Contains(this.sb_quick_challenge_form)) {
			if(!this.quick_challenge_form.ChallengeWaiting.Get()) {
				this.sb_quick_challenge_form.Display.Set(false);
			}
		}
	});

	Base.App.HashChange.AddHandler(this, function() {
		this.sb_quick_challenge_form.Display.Set(false);
	});
}

UsersTabBody.prototype.challenge_user = function(user, link) {
	this.current_challenge_link = link;
	Base.App.ClickedObjects.Add(link);

	var os = Dom.GetOffsets(link);
	var dim = [link.offsetWidth, link.offsetHeight];

	this.sb_quick_challenge_form.Display.Set(true);
	this.sb_quick_challenge_form.SetArrowLocation(os[X]+Math.round(dim[X]/2), os[Y]+dim[Y]+5);
	this.quick_challenge_form.ChallengeTo.Set(user);
	this.quick_challenge_form.Init();
}

UsersTabBody.prototype.Update = function() {
	this.ButtonReload.Disable();
	this.LabelUsersOnline.Hide();

	this.last_xhr = Xhr.QueryAsync(ap("/xhr/users_online.php"), function(data, rtt, xhr) {
		this.ButtonReload.Enable();
		this.LabelUsersOnline.Show();

		if(xhr === this.last_xhr && is_array(data)) {
			var users = data.length;
			var str;

			if(users === 0) {
				str = "There are no other users online";
			}

			else if(users === 1) {
				str = "There is one other user online";
			}

			else {
				str = "There are "+data.length+" other users online";
			}

			this.LabelUsersOnline.Text.Set("&nbsp;"+str+".");
			this.Grid.Update(data);
		}
	}, {}, this);
}