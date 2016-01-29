function StartTabBody(parent) {
	Control.implement(this, parent);

	this.SetupHtml();
}

StartTabBody.prototype.SetupHtml=function() {
	var con; //temp div variables for building up layout.

	con=div(this.Node);

	Dom.Style(con, {
		padding: "1em .8em"
	});

	this.con_tablelist=div(con);

	con=div(this.con_tablelist);

	var con_bar=div(con);
	var con_body=div(con);

	Dom.AddClass(con_bar, "tab_bar_table_list");
	Dom.AddClass(con_body, "tab_body_table_list");

	this.TableListTabCtrl=new TabController(con_bar, con_body, "tab_button_table_list", "tab_button_table_list_on");
	this.TableListTabCtrl.TabBar.Spacing.Set(5);

	var tabs={
		users: this.TableListTabCtrl.Add(IUsersTab),
		quick: this.TableListTabCtrl.Add(IQuickChallengeListTab),
		custom: this.TableListTabCtrl.Add(ICustomTableListTab),
		tournaments: this.TableListTabCtrl.Add(ITournamentsTab)
	};

	this.TableListTabCtrl.SelectTab(tabs.users);

	this.TableListTabCtrl.HideTab(tabs.tournaments);

	for(var p in tabs) {
		tabs[p].Closeable=false;
		tabs[p].Detachable=false;
		tabs[p].TabButton.ShowClose.Set(false);
		tabs[p].TabButton.ShowDetach.Set(false);
	}

	this.UpdateHtml();
}

StartTabBody.prototype.UpdateHtml=function() {
	//Dom.Style(this.con_tablelist, {
	//	width: 960
	//});
}