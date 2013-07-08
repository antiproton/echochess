/*
this will be loaded up by PHP at load time, not with an XHR.

Saving is still done with an XHR to avoid page reload.

this has props so it can fire an event if the prefs are changed.

things to change after adding/removing a preference in the db:

Props
update_row
*/

function UserPrefs(user) {
	this.User=user;
	this.loaded=false;
	this.row=null;
	this.SaveOnSet=true;
	this.PrefsLoaded=new Event(this);
	this.PrefsChanged=new Event(this);
	this.PieceStyleChanged=new Event(this);
	this.BoardStyleChanged=new Event(this);
	this.BoardColourChanged=new Event(this);
	this.BoardSizeChanged=new Event(this);
	this.ShowCoordsChanged=new Event(this);
	this.HighlightLastMoveChanged=new Event(this);

	this.update_row=[
		//"board_style",
		"piece_style",
		"board_size",
		"show_coords",
		//"sound",
		"highlight_last_move",
		//"highlight_possible_moves",
		"animate_moves",
		"board_colour_light",
		"board_colour_dark",
		"premove",
		"auto_queen"
	];

	this.init_props();
}

UserPrefs.prototype.init_props=function() {
	//this.BoardStyle=new Property(this, function() {
	//	return this.board_style;
	//}, function(value) {
	//	this.board_style=value;
	//	this.BoardStyleChanged.Fire();
	//	this.PrefsChanged.Fire();
	//});

	this.PieceStyle=new Property(this, function() {
		return this.piece_style;
	}, function(value) {
		this.piece_style=value;
		this.PieceStyleChanged.Fire();
		this.PrefsChanged.Fire();
		this.SavePref("piece_style");
	});

	this.BoardSize=new Property(this, function() {
		return this.board_size;
	}, function(value) {
		this.board_size=value;
		this.BoardSizeChanged.Fire();
		this.PrefsChanged.Fire();
		this.SavePref("board_size");
	});

	this.ShowCoords=new Property(this, function() {
		return this.show_coords;
	}, function(value) {
		this.show_coords=value;
		this.ShowCoordsChanged.Fire();
		this.PrefsChanged.Fire();
		this.SavePref("show_coords");
	});

	//this.Sound=new Property(this, function() {
	//	return this.sound;
	//}, function(value) {
	//	this.sound=value;
	//	this.PrefsChanged.Fire();
	//});

	this.HighlightLastMove=new Property(this, function() {
		return this.highlight_last_move;
	}, function(value) {
		this.highlight_last_move=value;
		this.HighlightLastMoveChanged.Fire();
		this.PrefsChanged.Fire();
		this.SavePref("highlight_last_move");
	});

	//this.HighlightPossibleMoves=new Property(this, function() {
	//	return this.highlight_possible_moves;
	//}, function(value) {
	//	this.highlight_possible_moves=value;
	//	this.PrefsChanged.Fire();
	//});

	this.AnimateMoves=new Property(this, function() {
		return this.animate_moves;
	}, function(value) {
		this.animate_moves=value;
		this.PrefsChanged.Fire();
	});

	this.BoardColourLight=new Property(this, function() {
		return this.board_colour_light;
	}, function(value) {
		this.board_colour_light=value;
		this.BoardColourChanged.Fire();
		this.PrefsChanged.Fire();
		this.SavePref("board_colour_light");
	});

	this.BoardColourDark=new Property(this, function() {
		return this.board_colour_dark;
	}, function(value) {
		this.board_colour_dark=value;
		this.BoardColourChanged.Fire();
		this.PrefsChanged.Fire();
		this.SavePref("board_colour_dark");
	});

	this.BoardColour=new Property(this, function() {
		return [this.board_colour_light, this.board_colour_dark];
	}, function(light, dark) {
		this.board_colour_light=light;
		this.board_colour_dark=dark;
		this.BoardColourChanged.Fire();
		this.PrefsChanged.Fire();
		this.SavePref("board_colour_light", "board_colour_dark");
	});

	this.Premove=new Property(this, function() {
		return this.premove;
	}, function(value) {
		this.premove=value;
		this.PrefsChanged.Fire();
		this.SavePref("premove");
	});

	this.AutoQueen=new Property(this, function() {
		return this.auto_queen;
	}, function(value) {
		this.auto_queen=value;
		this.PrefsChanged.Fire();
		this.SavePref("auto_queen");
	});
}

UserPrefs.prototype.LoadRow=function(row) {
	var field;
	this.row=row;

	for(var field in row) {
		this[field]=row[field];
	}

	this.PrefsChanged.Fire();
	this.PrefsLoaded.Fire();

	this.loaded=true;
}

/*
Save - if no args, save all prefs, otherwise save fields passed
*/

UserPrefs.prototype.Save=function() {
	if(this.loaded && this.User.IsSignedin) {
		var field;
		var update={};

		for(var i=0; i<this.update_row.length; i++) {
			field=this.update_row[i];

			if(this[field]!==this.row[field]) {
				update[field]=this[field];
			}
		}

		if(!is_empty_object(update)) {
			Xhr.RunQueryAsync(ap("/xhr/save_prefs.php"), update);
		}
	}
}

/*
save an individual pref(s) if SaveOnSet is on
*/

UserPrefs.prototype.SavePref=function() {
	if(this.SaveOnSet) {
		var field;
		var update={};

		for(var i=0; i<arguments.length; i++) {
			field=arguments[i];
			update[field]=this[field];
		}

		if(!is_empty_object(update)) {
			Xhr.RunQueryAsync(ap("/xhr/save_prefs.php"), update);
		}
	}
}