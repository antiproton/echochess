function TournamentsTabBody(parent) {
	Control.implement(this, parent);

	var self=this;

	this.updating=false;
	this.last_xhr=null; //only update if the xhr coming back was the last one to be sent

	this.Updating=new Property(this, function() {
		return this.updating;
	}, function(value) {
		this.updating=value;

		if(this.updating) {
			this.update();
		}
	});

	this.cols=[
		{
			Title: "Type",
			Width: 45,
			Value: function(row) {
				return "<img src=\""+ap("/img/icon/code/"+GAME_TYPE+"/"+row["type"]+"-20.png")+"\" title=\""+DbEnums[GAME_TYPE][row["type"]].Description+"\">";
			}
		},
		{
			Title: "Variant",
			Width: 60,
			Value: function(row) {
				return "<img src=\""+ap("/img/icon/code/"+VARIANT+"/"+row["variant"]+"-20.png")+"\" title=\""+DbEnums[VARIANT][row["variant"]].Description+"\">";
			}
		},
		{
			Title: "Time",
			Width: 200,
			Value: function(row) {
				return ClockTimeDisplay.EncodeFull(
					row["timing_style"],
					row["timing_initial"],
					row["timing_increment"],
					row["timing_overtime"],
					row["timing_overtime_increment"],
					row["timing_overtime_cutoff"]
				);
			}
		},
		{
			Title: "Rating",
			Width: 60,
			Value: "owner_rating"
		},
		{
			Title: "Rated",
			Width: 60,
			Value: function(row) {
				if(row["rated"]) {
					return "Rated";
				}

				else {
					return "Unrated";
				}
			}
		}
	];

	var colour_fields={};

	colour_fields["White"]={
		Field: "white",
		Colour: WHITE
	};

	colour_fields["Black"]={
		Field: "black",
		Colour: BLACK
	};

	for(var game_id=0; game_id<2; game_id++) {
		for(var field in colour_fields) {
			this.cols.push({
				Title: field,
				Width: 120,
				Value: (function(game_id, field) {
					return function(row) {
						var val=row["game_"+game_id+"_"+colour_fields[field].Field];

						if(val===null && game_id<=LiveGame.MaxGameId[row["type"]]) {
							var button=$("*button");

							button.type="button";
							button.innerHTML="Join";

							button.onclick=function(e) {
								Base.App.JoinTable(row["id"], game_id, colour_fields[field].Colour);

								if(e.stopPropagation) {
									e.stopPropagation();
								}

								return false;
							};

							return button;
						}

						else if(val!==null) {
							return val;
						}

						else {
							return "";
						}
					};
				})(game_id, field)
			});
		}
	}

	this.SetupHtml();
	this.init_updates();
}

TournamentsTabBody.prototype.SetupHtml=function() {
	Dom.Style(this.Node, {
		//padding: 2
	});

	Dom.AddClass(this.Node, "noselect");

	var tmp, con;

	this.filters_container=div(this.Node);

	Dom.Style(this.filters_container, {
		fontSize: 11,
		padding: 3
	});

	/*
	tournaments can be filtered using most of the same stuff as custom tables
	and quick challenges.  the xhr filters out ones where the user isn't in the
	rating range, and all tournaments are rated.

	NOTE some additional filters may be needed .. tournament format etc

	use a selector button perhaps .. [ warzone | round robin | etc ]

	NOTE current idea of different rounds having different timing complicates
	filters
	*/

	this.TournamentFilter=new TournamentFilter(this.filters_container);

	this.TournamentFilter.Update.AddHandler(this, function(data) {
		this.update();
	});

	this.Grid=new Grid(this.Node, this.cols);

	this.Grid.RowClick.AddHandler(this, function(data) {
		Base.App.OpenTable(data.Row["id"]);
	});

	this.Grid.BeforeRowDraw.AddHandler(this, function(data) {
		Dom.AddClass(data.RowDiv, "table_list_grid_row");

		if(data.Row["owner"]===Base.App.User.Username) {
			Dom.AddClass(data.RowDiv, "grid_row_custom_table_owner");
		}
	});
}

TournamentsTabBody.prototype.update=function() {
	this.last_xhr=Xhr.QueryAsync(ap("/xhr/tournaments_list.php"), function(data, rtt, xhr) {
		if(xhr===this.last_xhr && is_array(data)) {
			this.Grid.Update(data);
		}
	}, this.TournamentFilter.Filters.Get(), this);
}

TournamentsTabBody.prototype.init_updates=function() {
	Base.Tick.AddHandler(this, function() {
		if(this.updating) {
			this.update();
		}
	});
}