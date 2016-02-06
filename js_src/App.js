var App = {
	init: function() { //constructor
		var self = this;

		this.img_url = "http://img.echochess.com";

		this.User = new User();
		this.UndoAction = new Event(this);
		this.FocusedObject = null; //so only one thing catches the UndoAction event
		this.BodyClick = new Event(this); //fires when the user clicks somewhere on the page (useful for dismissing dialog boxes etc)
		this.UserJoinTable = new Event(this);
		this.UserOpenTable = new Event(this);
		this.UserQuickChallengeUpdate = new Event(this); //update the quick challenge list immediately so the user sees theirs
		this.HashChange = new Event(this);

		/*
		NOTE UserAcceptChallenge is no longer a thing; stuff calls QuickChallenge.Accept
		which now takes a callback, which should be a function that calls OpenTable if
		the accept was successful.
		*/

		/*
		ClickedObjects - dialogs need to close if the user clicks somewhere
		(indicated by the < body > receiving a click event) but not if the click landed
		on the dialog itself.  So the dialog click handler can add an object to a
		list of items clicked before the body, and then check that against the list
		when it receives the BodyClick event to decide whether to close or not.
		*/

		this.ClickedObjects = new List();

		Dom.AddEventHandler(window, "keydown", function(e) {
			if(e.ctrlKey && String.fromCharCode(e.keyCode) === "Z") {
				if(this.FocusedObject !== null) {
					//event is fired at the focussed object, so handlers don't have to check whether they are focussed

					self.UndoAction.Fire(null, this.FocussedObject);
				}
			}
		});

		Dom.AddEventHandler(window, "hashchange", function() {
			self.HashChange.Fire();
		});

		if(Base.Request["user"]["signedin"]) {
			this.User.SignIn(Base.Request["user"]["username"]);
		}

		this.User.Prefs.LoadRow(Base.Request["user_prefs"]);

		Base.Ready.AddHandler(this, function() {
			Dom.AddEventHandler($(" > html")[0], "click", function() {
				self.BodyClick.Fire();
				self.ClickedObjects.Clear();
			});

			this.UserButton = new UserButton(6, 7);
			this.UserButton.Hide();
		});
	},

	JoinTable: function(id, game_id, colour) {
		this.UserJoinTable.Fire({
			Id: id,
			GameId: game_id,
			Colour: colour
		});
	},

	OpenTable: function(id) {
		this.UserOpenTable.Fire({
			Id: id
		});
	},

	/*
	quick challenge list updates instantly if the user creates/cancels a challenge

	more of a cosmetic thing than anything else.
	*/

	UpdateQuickChallenge: function() {
		this.UserQuickChallengeUpdate.Fire();
	},

	ImgUrl: function(path) {
		return this.img_url + path;
	},

	CssImg: function(path) {
		return "url('" + this.img_url + path + "')";
	}
}

App.init();

/*
NOTE keeping a reference in Base to avoid changing all Base.App to App

also popups might want Base.Root.App at some point so it could be a good
idea to keep it anyway.
*/

Base.App = App;