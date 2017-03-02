/*
NOTE with the hash component stuff it is unworkable to create a new table
in the same tab - there is too much interaction between the tab and the hash
to untangle (updating the hash closes all tabs that aren't in the hash list)

so creating a new game from the tab ("New 10/15") always creates a new tab,
and closes the current one if there isn't a game in progress.
*/

function ILiveTableTab() {
	this.Table = null;
	this.Detachable = false;
	this.TabButton.ButtonDetach.Hide();

	this.TabButton.ButtonDetach.Click.AddHandler(this, function() {
		var self = this;

		this.UserDetach.Fire();

		var id = Base.Root.Popup(ap("/livetable_detached?id = "+this.Table.Id), 750, 650);

		Base.Root.RegisterPopupInitDetails(id, {
			Tab: this
		});
	});
}

ILiveTableTab.prototype.CreateTable = function() {
	if(this.Table !== null) {
		this.Table.Die();
	}

	Dom.ClearNode(this.TabPage.Inner);

	this.Table = new LiveTable(this.TabPage.Inner);

	this.update_tab_title();

	this.Table.SeatingChanged.AddHandler(this, function() {
		this.update_tab_title();
	});

	this.Table.Update.AddHandler(this, function(data, sender) {
		this.update_tab_title();

		if(this.Selected) {
			App.FocussedObject = this.Table.CurrentPlayerGame;
		}

		if(!this.Table.PlayerPresent) {
			this.Table.Die();
		}
	});

	this.Table.UiLoaded.AddHandler(this, function() {
		this.Table.UiByRel.ByGameAndPlayer.Player.Player.PlayerClock.Update.AddHandler(this, function(data, sender) {
			this.update_tab_title();
		});
	});

	this.Table.Loaded.AddHandler(this, function(data, sender) {
		this.HashAdd.Fire({
			Id: sender.Id
		});
	});

	this.Table.Dead.AddHandler(this, function(data, sender) {
		this.HashRemove.Fire({
			Id: sender.Id
		});
	});

	this.Table.UserClose.AddHandler(this, function(data, sender) {
		//NOTE this doesn't seem to be necessary, don't know why
		//this.HashRemove.Fire({
		//	Id: sender.Id
		//});

		this.Controller.RemoveTab(this);
	});

	//NOTE taken this out to avoid back button fuck up
	//should decide what hashadd/remove/close tab should/shouldn't affect
	//for now bad tables just display a message

	//this.Table.LoadFailed.AddHandler(this, function(data, sender) {
	//	this.HashRemove.Fire({
	//		Id: sender.Id
	//	});
	//
	//	this.Controller.RemoveTab(this);
	//});

	this.Table.UserNewQuickChallenge.AddHandler(this, function(data, sender) {
		var tab = this.Controller.Add(ILiveTableTab);

		Base.LongPoll.Pause(function() {
			tab.CreateTable();
			tab.Table.Load(data.Id);
			tab.Table.FromQuickChallenge = data.QuickChallenge;

			/*
			wait til the new tab has established itself before closing the old one,
			otherwise the hash update will see the new tab as one that needs closing
			because it isn't in the hash list.
			*/

			tab.HashAdd.AddHandler(this, function() {
				if(!sender.GameInProgress.Get()) {
					sender.Die();
				}

				return true;
			});
		}, this);
	});
}

ILiveTableTab.prototype.update_tab_title = function() {
	this.TabButton.Title.Set(this.Table.GetTitle());
}

ILiveTableTab.prototype.Select = function() {
	Tab.prototype.Select.call(this);

	if(this.Table !== null) {
		App.FocusedObject = this.Table.CurrentPlayerGame;
	}
}

ILiveTableTab.prototype.Remove = function() {
	if(this.Table !== null) {
		this.Table.Leave();
		this.Table.Die();
	}

	Tab.prototype.Remove.call(this);
}