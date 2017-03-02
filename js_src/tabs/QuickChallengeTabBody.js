function QuickChallengeTabBody(parent) {
	Control.implement(this, parent);

	this.updating = false;
	this.last_xhr = null; //only update if the xhr coming back was the last one to be sent

	this.Updating = new Property(this, function() {
		return this.updating;
	}, function(value) {
		this.updating = value;

		if(this.updating) {
			this.update();
		}
	});

	this.SetupHtml();
	this.init_updates();
}

QuickChallengeTabBody.prototype.SetupHtml = function() {
	Dom.Style(this.Node, {
		//padding: 2
	});

	Dom.AddClass(this.Node, "noselect");

	var con;

	this.filters_container = div(this.Node);

	Dom.Style(this.filters_container, {
		fontSize: 11,
		padding: 3
	});

	this.TableFilter = new TableFilter(this.filters_container);
	this.TableFilter.ContainerType.Hide();

	this.TableFilter.Update.AddHandler(this, function(data) {
		this.update();
	});

	//graph/list selector (acting as tabs for tab control)
	//NOTE leaving graph for now

	//con = div(this.Node);
	//
	//Dom.Style(con, {
	//	padding: "5px 0 0 5px"
	//});
	//
	//this.ContainerSelector = new LabelAndInputContainer(con, {
	//	LabelPadding: 7
	//});
	//
	//this.ContainerSelector.Label.Text.Set("View as");

	//tab control (with tabs hidden)

	con = div(this.Node);

	var bar_con = div(con);
	var body_con = div(con);

	this.TabControlGraphList = new TabController(bar_con, body_con);

	var tab_graph = this.TabControlGraphList.Add(IQuickChallengeListGraphTab);
	var tab_list = this.TabControlGraphList.Add(IQuickChallengeListListTab);

	//this.SelectorView = new SelectorButton(this.ContainerSelector.InputInner, [
	//	{
	//		Value: tab_graph,
	//		Label: "Graph"
	//	},
	//	{
	//		Value: tab_list,
	//		Label: "List"
	//	}
	//]);

	this.TabControlGraphList.TabBar.Hide();
	this.TabControlGraphList.SelectTab(tab_list);
	
	//this.TabControlGraphList.SelectTab(this.SelectorView.Value.Get());
	//
	//this.SelectorView.SelectionChanged.AddHandler(this, function() {
	//	this.TabControlGraphList.SelectTab(this.SelectorView.Value.Get());
	//});

	this.UpdateHtml();
}

QuickChallengeTabBody.prototype.UpdateHtml = function() {

}

QuickChallengeTabBody.prototype.update = function() {
	this.last_xhr = Xhr.QueryAsync(ap("/xhr/tables_quick.php"), function(data, rtt, xhr) {
		if(xhr === this.last_xhr && is_array(data) && this.TabControlGraphList.SelectedTab !== null) {
			this.TabControlGraphList.SelectedTab.Body.Update(data);
		}
	}, this.TableFilter.Filters.Get(), this);
}

QuickChallengeTabBody.prototype.init_updates = function() {
	Base.Tick.AddHandler(this, function() {
		if(this.updating) {
			this.update();
		}
	});

	App.UserQuickChallengeUpdate.AddHandler(this, function() {
		if(this.updating) {
			this.update();
		}
	});
}