/*
Base keeps some basic functionality together in a convenient place.

It also keeps some useful references, e.g. Root points to the instance of
Base that was created in the main window (not in a popup).

Popups:

to avoid having to put loads of stuff in the query string of the popup
url, if the data is available on the client already it can be made
available to the popup using the RegisterPopupInitDetails function.

e.g. in the main page:

var popup=Base.Popup(url etc); //popup now contains the id of the popup
Base.RegisterPopupInitDetails(popup, { Test: 123 });

and in the popup:

var info=Base.GetInitDetails();
info.Test; //123
*/

var Base={
	init: function() { //constructor
		var self=this;

		this.last_id=0; //for generating unique ids
		this.App=null;
		this.Window=window;
		this.Root=this;
		this.Popups={};
		this.popup_init_details={};
		this.MtimePageLoad=mtime();
		this.Ready=new Event(this);
		this.WindowClosing=new Event(this);
		this.LongPoll=new LongPoll();
		this.Tick=new Event(this);
		this.HalfSecTick=new Event(this);
		this.TenthSecTick=new Event(this);
		this.tenth_sec_ticks=0;

		if(!window.self.name) {
			window.self.name=this.get_window_name();
		}

		if(window.opener!==undefined && window.opener!==null) {
			try {
				if(window.opener.Base) {
					this.Root=window.opener.Base;
				}
			}

			catch(e) {
				//opened from somewhere other than main site
			}
		}

		this.ticker=setInterval(function() {
			self.tenth_sec_ticks++;
			self.TenthSecTick.Fire();

			if(self.tenth_sec_ticks%5==0) {
				self.HalfSecTick.Fire();
			}

			if(self.tenth_sec_ticks%10==0) {
				self.Tick.Fire();
			}
		}, MSEC_PER_SEC/10);

		Dom.AddEventHandler(window, "load", function() {
			self.Ready.Fire();
		});

		Dom.AddEventHandler(window, "beforeunload", function() {
			self.WindowClosing.Fire();
		});
	},

	IsRoot: function() {
		return (this.Root===this);
	},

	Popup: function(url, width, height) {
		width=width||640;
		height=height||480;

		/*
		NOTE no window name is given - this is so that the Base in the popup generates
		its own for use with its longpoll
		*/

		var id=this.GetId();
		var popup=window.open(url, "", "width="+width+",height="+height);
		this.Popups[id]=popup;

		return id;
	},

	RegisterPopupInitDetails: function(id, obj) {
		this.popup_init_details[id]=obj;
	},

	GetPopupInitDetails: function(popup) {
		for(var id in this.popup_init_details) {
			if(this.Popups[id]===popup) {
				return this.popup_init_details[id];
			}
		}

		return null;
	},

	GetInitDetails: function() { //call from the popup
		return this.Root.GetPopupInitDetails(this.Window);
	},

	GetId: function() { //get a general-purpose unique id string
		return "_"+(++this.last_id);
	},

	/*
	each window needs a name (to be retained across refreshes) so
	that only one longpoll is kept open per tab per user
	*/

	get_window_name: function() {
		return "_"+mtime();
	}
};

Base.init();