function LiveTableDetached() {
	this.Table=null;

	this.main_window_open=!Base.IsRoot();
	this.attaching=false; //no quit if just reattaching to main window
	this.init_details=Base.GetInitDetails();
	this.init_table();
	this.init_attach_button();
	this.update_window_title();
	this.init_quit_cleanup();
	this.init_updates();

	Base.App.UserButton.Y.Set(2);
}

LiveTableDetached.prototype.init_attach_button=function() {
	var self=this;

	this.link_attach=$("#attach");

	if(this.main_window_open) {
		Base.Root.WindowClosing.AddHandler(this, function() {
			this.main_window_open=false;

			Dom.Style(this.link_attach, {
				display: "none"
			});
		});

		Dom.AddEventHandler(this.link_attach, "click", function() {
			self.attaching=true;

			/*
			NOTE too tangled up here; relies on knowing that the TabController handles
			UserDetach by just hiding the tab
			*/

			self.init_details.Tab.Controller.ShowTab(self.init_details.Tab);
			self.init_details.Tab.Controller.SelectTab(self.init_details.Tab);
			
			window.close();
		});
	}

	else {
		Dom.Style(this.link_attach, {
			display: "none"
		});
	}
}

LiveTableDetached.prototype.CreateTable=function() {
	if(this.Table!==null) {
		this.Table.Die();
	}

	this.Table=new LiveTable($("#table"));

	this.Table.SeatingChanged.AddHandler(this, function() {
		this.update_window_title();
	});

	this.Table.Update.AddHandler(this, function() {
		this.update_window_title();
	});

	this.Table.UiLoaded.AddHandler(this, function() {
		this.Table.UiByRel.ByGameAndPlayer.Player.Player.PlayerClock.Update.AddHandler(this, function(data, sender) {
			this.update_window_title();
		});
	});

	this.Table.UserNewQuickChallenge.AddHandler(this, function(data, sender) {
		if(sender.GameInProgress.Get()) {
			Base.Root.Popup(ap("/livetable_detached?id="+data.Id));
		}

		else {
			Base.LongPoll.Pause(function() {
				this.CreateTable();
				this.Table.Load(data.Id);
				this.Table.FromQuickChallenge=data.QuickChallenge;
			}, this);
		}
	});
}

LiveTableDetached.prototype.update_window_title=function() {
	document.title=this.Table.GetTitle();
}

LiveTableDetached.prototype.init_table=function() {
	this.CreateTable();
	this.Table.Load(Base.Request["page"]["id"]);
}

LiveTableDetached.prototype.init_quit_cleanup=function() {
	Dom.AddEventHandler(window, "beforeunload", function() {
		if(!this.attaching) {
			if(this.main_window_open) {
				var tab=this.init_details.Tab;

				tab.Table.Leave();
			}
		}
	}, this);
}

LiveTableDetached.prototype.init_updates=function() {
	Base.LongPoll.Url.Set(ap("/xhr/updates.php"));
	Base.LongPoll.Start();
}

var main;

Base.Ready.AddHandler(this, function() {
	main=new LiveTableDetached();
});