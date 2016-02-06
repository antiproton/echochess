function Tabs() {
	this.window = window;
	this.init_tab_ctrl();
	this.init_alerts_container();
	this.init_seated_tables();
	this.init_new_tab_links();
	this.init_custom_table_form();
	this.init_quick_challenge_form();
	//this.init_other_login_alert();
	this.init_user_ratings();
	this.init_game_setup_handlers();
	this.init_messages();
	this.init_quit_cleanup();
	this.init_direct_challenge_listener();
	this.init_updates();

	Base.App.UserButton.Show();

	//DEBUG:

	//this.Ctrl.SelectTab(this.StartTab);
	//this.Ctrl.SelectedTab.Body.TableListTabCtrl.SelectTab(this.Ctrl.SelectedTab.Body.TableListTabCtrl.Tabs.FirstItem());
}

Tabs.prototype.init_alerts_container = function() {
	this.alerts_container = div(Dom.GetBody());

	Dom.Style(this.alerts_container, {
		position: "absolute",
		zIndex: 3,
		bottom: 0,
		right: 0
	});
}

Tabs.prototype.init_direct_challenge_listener = function() {
	this.direct_challenge_listener = new DirectChallengeListener();

	this.direct_challenge_listener.ChallengeReceived.AddHandler(this, function(data) {
		var challenge_alert = new DirectChallengeAlert(this.alerts_container, data);
	});
}

Tabs.prototype.init_quit_cleanup = function() {
	Dom.AddEventHandler(window, "beforeunload", function() {
		var ids = [];

		this.Ctrl.Tabs.Each(function(item) {
			if(
				item.Type === ILiveTableTab
				&& item.Table !== null
				&& item.Table.PlayerIsSeated.Get()
				&& item.Table.GameInProgress.Get()
			) {
				ids.push(item.Table.Id);
			}
		});

		if(ids.length > 0) {
			Xhr.RunQueryAsync(ap("/xhr/quit.php"), ids);
		}
	}, this);
}

Tabs.prototype.find_live_table = function(id) {
	var table = null;

	this.Ctrl.Tabs.Each(function(item) {
		if(item.Type === ILiveTableTab && item.Table !== null && item.Table.Id === id) {
			table = item.Table;

			return true;
		}
	});

	return table;
}

Tabs.prototype.init_messages = function() {
	this.Messages = new Messages();

	this.Messages.Update.AddHandler(this, function(data, sender) {
		if(is_array(data.Data)) {
			var msg;

			for(var i = 0; i < data.Data.length; i++) {
				msg = data.Data[i];

				switch(msg["type"]) {
					case MESSAGE_TYPE_REMATCH_DECLINE: {
						var table = this.find_live_table(msg["subject"]);

						if(table !== null) {
							table.MessageRematchDeclined(msg["sender"]);
						}

						break;
					}

					case MESSAGE_TYPE_REMATCH_CANCEL: {
						var table = this.find_live_table(msg["subject"]);

						if(table !== null) {
							table.MessageRematchCancelled(msg["sender"]);
						}

						break;
					}

					case MESSAGE_TYPE_OPPONENT_CONNECT: {
						var table = this.find_live_table(msg["subject"]);

						if(table !== null) {
							table.MessageOpponentConnected(msg["sender"]);
						}

						break;
					}

					case MESSAGE_TYPE_OPPONENT_DISCONNECT: {
						var table = this.find_live_table(msg["subject"]);

						if(table !== null) {
							table.MessageOpponentDisconnected(msg["sender"]);
						}

						break;
					}

					case MESSAGE_TYPE_TEAM_CHAT: {
						var table = this.find_live_table(msg["subject"]);

						if(table !== null) {
							table.MessageTeamChat(msg["sender"], msg["body"]);
						}

						break;
					}
				}
			}
		}
	});
}

/*
init_game_setup_handlers

signals that the user wants to open a table or accept a challenge can come
from anywhere, e.g. a Grid nested in 3 Tabs, which shouldn't know the implementation
of opening a table, but getting events out of it quickly becomes bollocks:

this.Body.AcceptChallenge.AddHandler(this, function(data) {
	this.AcceptChallenge.Fire(data);
});

so instead of that we just go straight to the App, which currently just fires an
event for each action (joining, opening, accepting).

NOTE accepting isn't done through the App anymore - challenge accepts end up going
through OpenTable via a callback passed to QuickChallenge.Accept.  This way the
callback can check whether the accept was successful before opening a tab (or
displaying a message if it was unsuccessful).
*/

Tabs.prototype.init_game_setup_handlers = function() {
	Base.App.UserOpenTable.AddHandler(this, function(data) {
		var tab = null;

		this.Ctrl.Tabs.Each(function(item) {
			if(item.Type === ILiveTableTab && item.Table !== null && item.Table.Id === data.Id) {
				tab = item;

				return true;
			}
		});

		if(tab === null) {
			tab = this.Ctrl.Add(ILiveTableTab);

			Base.LongPoll.Pause(function() {
				tab.CreateTable();
				tab.Table.Load(data.Id);
			});
		}

		else {
			tab.Controller.SelectTab(tab);
		}
	});

	Base.App.UserJoinTable.AddHandler(this, function(data) {
		var tab = null;

		this.Ctrl.Tabs.Each(function(item) {
			if(item.Type === ILiveTableTab && item.Table !== null && item.Table.Id === data.Id) {
				tab = item;

				return true;
			}
		});

		if(tab === null) {
			tab = this.Ctrl.Add(ILiveTableTab);

			Base.LongPoll.Pause(function() {
				tab.CreateTable();
				tab.Table.Load(data.Id);
			});

			tab.Table.Loaded.AddHandler(this, (function(game_id, colour) {
				return function(data, sender) {
					sender.Sit(game_id, colour);
				};
			})(data.GameId, data.Colour));
		}

		else {
			tab.Table.Sit(data.GameId, data.Colour);
			tab.Controller.SelectTab(tab);
		}
	});
}

Tabs.prototype.init_tab_ctrl = function() {
	var self = this;

	/*
	a couple of flags to stop event handlers going crazy while updating
	*/

	this.updating_hash = false;
	this.updating_from_hash = false;

	this.hash_list = [];

	/*
	NOTE hash types in quotes to avoid getting minned
	*/

	this.hash_tab_types = {
		"l": ILiveTableTab,
		"e": IAnalysisTab
	};

	/*
	create the main tab control
	*/

	this.Ctrl = new TabController($("#tab_bar"), $("#tab_body"), "tab_button", "tab_button_on");
	this.Ctrl.TabBar.Spacing.Set(1);

	/*
	event handlers for updating the hash
	*/

	this.Ctrl.TabAdded.AddHandler(this, function(data, sender) {
		for(var p in this.hash_tab_types) {
			if(data.Tab.Type === this.hash_tab_types[p]) {
				data.Tab.HashPrefix = p;

				break;
			}
		}

		if(data.Tab.HashPrefix !== null) {
			data.Tab.HashAdd.AddHandler(this, function(data, sender) {
				if(!this.updating_from_hash) {
					var str = sender.HashPrefix+data.Id;
					var already_added = false;

					for(var i = 0; i < this.hash_list.length; i++) {
						if(this.hash_list[i] === str) {
							already_added = true;

							break;
						}
					}

					if(!already_added) {
						this.hash_list.push(str);
						this.update_hash();
					}
				}
			});

			data.Tab.HashRemove.AddHandler(this, function(data, sender) {
				if(!this.updating_from_hash) {
					var str = sender.HashPrefix+data.Id;

					for(var i = 0; i < this.hash_list.length; i++) {
						if(this.hash_list[i] === str) {
							this.hash_list.splice(i, 1);
							i--;
						}
					}

					this.update_hash();
				}
			});
		}
	});

	Dom.AddEventHandler(window, "hashchange", function() {
		if(!self.updating_hash) { //there is no point doing anything if the event was caused by us updating the hash
			self.update_from_hash();
		}
	});

	/*
	start tab
	*/

	this.StartTab = this.Ctrl.Add(IStartTab);
	this.StartTab.Closeable = false;

	/*
	hash tabs
	*/

	this.update_from_hash();
}

/*
load tables the user is sat at
*/

Tabs.prototype.init_seated_tables = function() {
	var tables = Base.Request["page"]["tables"];
	var id;
	var already_added;

	for(var i = 0; i < tables.length; i++) {
		id = tables[i];
		already_added = false;

		this.Ctrl.Tabs.Each(function(item) {
			if(item.Type === ILiveTableTab && item.Table !== null && item.Table.Id === id) {
				already_added = true;

				return true;
			}
		});

		if(!already_added) {
			var tab = this.Ctrl.Add(ILiveTableTab);
			tab.CreateTable();
			tab.Table.Load(id);
		}
	}
}

Tabs.prototype.init_new_tab_links = function() {
	this.new_tab_links = {
		Quick: $("#new_live"),
		Custom: $("#new_custom"),
		Editor: $("#new_analysis")
	};

	Dom.AddEventHandler(this.new_tab_links.Quick, "click", function() {
		Base.App.ClickedObjects.Add(this.new_tab_links.Quick);

		if(this.sb_quick_challenge_form.Display.Get()) {
			this.sb_quick_challenge_form.Display.Set(false);
		}

		else {
			var os = Dom.GetOffsets(this.new_tab_links.Quick);
			var dim = [this.new_tab_links.Quick.offsetWidth, this.new_tab_links.Quick.offsetHeight];

			this.sb_quick_challenge_form.Display.Set(true);
			this.sb_quick_challenge_form.SetArrowLocation(os[X]+Math.round(dim[X]/2), os[Y]+dim[Y]+5);
			this.quick_challenge_form.Init();
		}
	}, this);

	Dom.AddEventHandler(this.new_tab_links.Custom, "click", function() {
		Base.App.ClickedObjects.Add(this.new_tab_links.Custom);

		if(this.sb_custom_table_dialog.Display.Get()) {
			this.sb_custom_table_dialog.Display.Set(false);
		}

		else {
			var os = Dom.GetOffsets(this.new_tab_links.Custom);
			var dim = [this.new_tab_links.Custom.offsetWidth, this.new_tab_links.Custom.offsetHeight];

			this.sb_custom_table_dialog.Display.Set(true);
			this.sb_custom_table_dialog.SetArrowLocation(os[X]+Math.round(dim[X]/2), os[Y]+dim[Y]+5);
		}
	}, this);

	Dom.AddEventHandler(this.new_tab_links.Editor, "click", function() {
		this.Ctrl.Add(IAnalysisTab).CreateTable();
	}, this);
}

Tabs.prototype.init_custom_table_form = function() {
	this.sb_custom_table_dialog = new SpeechBubbleBox(TOP, {
		ArrowHeight: 8,
		Width: 220,
		BorderColour: "#808080"
	});

	this.sb_custom_table_dialog.Display.Set(false);

	this.CustomTableForm = new CustomTableForm(this.sb_custom_table_dialog.Inner);

	this.CustomTableForm.CreateTable.AddHandler(this, function(data) {
		var tab = this.Ctrl.Add(ILiveTableTab);

		tab.CreateTable();
		tab.Table.Type.Set(data.Type);
		tab.Table.Save();

		this.sb_custom_table_dialog.Display.Set(false);
	});

	Base.App.BodyClick.AddHandler(this, function() {
		if(!Base.App.ClickedObjects.Contains(this.new_tab_links.Custom) && !Base.App.ClickedObjects.Contains(this.sb_custom_table_dialog)) {
			this.sb_custom_table_dialog.Display.Set(false);
		}
	});

	Base.App.HashChange.AddHandler(this, function() {
		this.sb_custom_table_dialog.Display.Set(false);
	});
}

Tabs.prototype.init_quick_challenge_form = function() {
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
			var tab = this.Ctrl.Add(ILiveTableTab);

			tab.CreateTable();
			tab.Table.Load(data.Table);
			tab.Table.FromQuickChallenge = sender.QuickChallenge;
			this.sb_quick_challenge_form.Display.Set(false);
		}
	});

	Base.App.BodyClick.AddHandler(this, function() {
		if(!Base.App.ClickedObjects.Contains(this.new_tab_links.Quick) && !Base.App.ClickedObjects.Contains(this.sb_quick_challenge_form)) {
			if(!this.quick_challenge_form.ChallengeWaiting.Get()) {
				this.sb_quick_challenge_form.Display.Set(false);
			}
		}
	});

	Base.App.HashChange.AddHandler(this, function() {
		this.sb_quick_challenge_form.Display.Set(false);
	});
}

/*
init_user_ratings - the server gets a list of the user's ratings at load
time for clientside use.  they won't be updated if the ratings change.
*/

Tabs.prototype.init_user_ratings = function() {
	//TODO
}

Tabs.prototype.init_other_login_alert = function() {
	var other_login_alert = new GenericUpdater(GENERIC_UPDATES_LIVE_MAIN_WINDOW, Base.Request["page"]["main_page_update"]);

	other_login_alert.Updated.AddHandler(this, function() {
		Base.LongPoll.Stop();

		//alert("Another login has been detected");

		window.location.href = ap("/");
	});
}

Tabs.prototype.update_from_hash = function() {
	this.updating_from_hash = true;

	var hash = window.location.hash;

	if(hash.indexOf("!") === 1 && hash.length > 2) {
		this.hash_list = hash.substr(2).split(",");

		/*
		add any that aren't in the tab control already
		*/

		var str, type, id, tab;
		var already_added;

		for(var i = 0; i < this.hash_list.length; i++) {
			str = this.hash_list[i];
			type = str.substr(0, 1);
			id = parseInt(str.substr(1));

			if((type in this.hash_tab_types) && is_number(id)) {
				already_added = false;

				this.Ctrl.Tabs.Each(function(item) {
					if(item.HashPrefix === type && item.Table !== null && item.Table.Id === id) {
						already_added = true;

						return true;
					}
				}, this);

				if(!already_added) {
					tab = this.Ctrl.Add(this.hash_tab_types[type]);
					tab.CreateTable();
					tab.Table.Load(id);
				}
			}
		}

		/*
		remove any from the tab control that aren't in the hash
		*/

		this.Ctrl.Tabs.Each(function(item) {
			if((item.HashPrefix in this.hash_tab_types) && item.Table !== null) {
				var in_hash = false;

				str = item.HashPrefix+item.Table.Id;

				for(var i = 0; i < this.hash_list.length; i++) {
					if(this.hash_list[i] === str) {
						in_hash = true;

						break;
					}
				}

				if(!in_hash) {
					this.Ctrl.RemoveTab(item);
				}
			}
		}, this);
	}

	else {
		this.hash_list = [];
	}

	this.updating_from_hash = false;
}

Tabs.prototype.update_hash = function() {
	this.updating_hash = true;
	window.location.hash = "#!"+this.hash_list.join(",");
	this.updating_hash = false;
}

Tabs.prototype.init_updates = function() {
	Base.LongPoll.Url.Set(ap("/xhr/updates.php"));
	Base.LongPoll.Start();
}

var main;

window.addEventListener("load", function() {
	main = new Tabs();
});