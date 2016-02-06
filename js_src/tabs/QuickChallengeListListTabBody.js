function QuickChallengeListListTabBody(parent) {
	Control.implement(this, parent);

	this.SetupHtml();
}

QuickChallengeListListTabBody.prototype.SetupHtml = function() {
	Dom.AddClass(this.Node, "noselect");

	this.Grid = new Grid(this.Node, [
		{
			Title: "Variant",
			Width: 60,
			Value: function(row) {
				return " < img src = \""+ap("/img/icon/code/"+VARIANT+"/"+row["variant"]+"-20.png")+"\" title = \""+DbEnums[VARIANT][row["variant"]].Description+"\" > ";
			}
		},
		{
			Title: "Time",
			Width: 120,
			Value: function(row) {
				return ClockTimeDisplay.Encode(
					TIMING_FISCHER_AFTER,
					row["timing_initial"],
					row["timing_increment"]
				);
			}
		},
		{
			Title: "Player",
			Width: 120,
			Value: "owner"
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
		},
		{
			Title: "Colour",
			Width: 45,
			Value: function(row) {
				var dot = "grey";

				if(row["choose_colour"]) {
					dot = Util.colour_name(Util.opp_colour(row["challenge_colour"]));
				}

				return " < img src = \""+ap("/img/icon/colour_dot/"+dot+".png")+"\" > ";
			}
		}
	]);

	this.Grid.RowClick.AddHandler(this, function(data) {
		if(data.Row["owner"] !== Base.App.User.Username) {
			QuickChallenge.Accept(data.Row["id"], function(response) {
				if(response !== false) {
					Base.App.OpenTable(data.Row["id"]);
				}
			});
		}
	});

	this.Grid.BeforeRowDraw.AddHandler(this, function(data) {
		Dom.AddClass(data.RowDiv, "table_list_grid_row");

		if(data.Row["owner"] === Base.App.User.Username) {
			Dom.AddClass(data.RowDiv, "grid_row_quick_challenge_owner");
		}
	});

	this.container_no_challenges = new Container(this.Node);

	var nc = this.container_no_challenges.Node;
	var tmp = div(nc);

	Dom.Style(tmp, {
		fontSize: "1.5em",
		textAlign: "center",
		color: "#3f3f3f",
		paddingTop: "1em"
	});

	tmp.innerHTML = "Create a challenge";

	tmp = div(nc);

	Dom.Style(tmp, {
		fontSize: "1.1em",
		fontWeight: "bold",
		textAlign: "center",
		color: "#3f3f3f",
		padding: "1em"
	});

	var msg = "";
	var users_online = Base.Request["page"]["users_online"]-1;
	var singular = (users_online === 1);

	if(users_online < 0) {
		users_online = 0;
	}

	if(users_online === 1) {
		users_online = 2; //lying for grammatical reasons there
	}

	msg += "There are no quick challenges open at the moment.";
	msg += "  There "+(singular?"is":"are")+" ";

	if(users_online > 0) {
		msg += " < a href = \"javascript:void(0)\" > "+users_online+" other user"+s(users_online)+" < /a > ";
	}

	else {
		msg += users_online+" other user"+s(users_online);
	}

	msg += " online.";

	tmp.innerHTML = msg;

	this.container_no_challenges.Display.Set(false);
}

QuickChallengeListListTabBody.prototype.Update = function(data) {
	this.Grid.Update(data);

	//if(this.Grid.Rows.length === 0) {
	//	this.container_no_challenges.Display.Set(true);
	//}
}