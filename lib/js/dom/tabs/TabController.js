/*
this keeps a list of information about the tabs it has open in the
document fragment identifier (http://chess/tabs#!...)

see HashComponent and top level code for how it gets its data into the hash

see code here and notes in Tab for how it gets, encodes and parses the actual
information about which tabs it has
*/

function TabController(bar_parent, body_parent, tab_button_class, tab_button_class_on) {
	UiElement.implement(this);
	HashComponent.implement(this);

	this.Tabs=new List();
	this.TabBar=new TabBar(bar_parent);
	this.TabBody=new TabBody(body_parent);
	this.tab_button_class=tab_button_class||"";
	this.tab_button_class_on=tab_button_class_on||"";
	this.SelectedTab=null;
	this.last_selected_tabs=new List();
	this.TabAdded = new Event(this);

	/*
	this is how it will know what kind of tab to add from the url

	e.g. http://chess/tabs#l65,e9
	*/

	this.hash_tabs={
		l: ILiveTableTab,
		e: IAnalysisTab
	};

	this.hash_list=[];
}

TabController.prototype.Add=function(cls_type) {
	var tab_button=this.TabBar.Add();
	var tab_page=this.TabBody.Add();
	var tab=new Tab(this, tab_button, tab_page);

	tab_button.ClassNode.Set(this.tab_button_class);
	tab_button.ClassNodeOn.Set(this.tab_button_class_on);

	if(cls_type instanceof Function) {
		cls_type.override(tab);
		tab.Type=cls_type;

		for(var p in this.hash_tabs) {
			if(cls_type===this.hash_tabs[p]) {
				tab.HashPrefix=p;

				break;
			}
		}

		if(tab.HashPrefix!==null) {
			tab.HashAdd.AddHandler(this, function(data, sender) {
				var str=sender.HashPrefix+data.Id;
				var already_added=false;

				for(var i=0; i<this.hash_list.length; i++) {
					if(this.hash_list[i]===str) {
						already_added=true;

						break;
					}
				}

				if(!already_added) {
					this.hash_list.push(str);
					this.update_hash();
				}
			});

			tab.HashRemove.AddHandler(this, function(data, sender) {
				var str=sender.HashPrefix+data.Id;

				for(var i=0; i<this.hash_list.length; i++) {
					if(this.hash_list[i]===str) {
						this.hash_list.splice(i, 1);
						i--;
					}
				}

				this.update_hash();
			});
		}
	}

	tab.UserSelect.AddHandler(this, function(data, sender) {
		this.SelectTab(sender);
	});

	tab.UserClose.AddHandler(this, function(data, sender) {
		this.RemoveTab(sender);
	});

	tab.UserDetach.AddHandler(this, function(data, sender) {
		this.HideTab(sender);
	});

	tab.UserAttach.AddHandler(this, function(data, sender) {
		this.ShowTab(sender);
	});

	this.SelectTab(tab);

	this.Tabs.Add(tab);

	this.TabAdded.Fire({
		Tab: tab
	});

	return tab;
}

TabController.prototype.RemoveTab=function(tab) {
	tab.Remove();
	this.TabBar.Remove(tab.TabButton);
	this.TabBody.Remove(tab.TabPage);
	this.Tabs.Remove(tab);
	this.last_selected_tabs.Remove(tab);

	if(tab===this.SelectedTab) {
		this.select_last_visible_tab();
	}
}

TabController.prototype.select_last_visible_tab=function() {
	this.last_selected_tabs.Each(function(tab) {
		if(tab.Visible.Get()) {
			this.SelectTab(tab);

			return true;
		}
	}, this);
}

TabController.prototype.SelectTab=function(tab) {
	if(this.SelectedTab!==null) {
		this.SelectedTab.Deselect();
	}

	this.SelectedTab=tab;

	if(tab!==null) {
		this.TabBody.Select(tab.TabPage);
		this.TabBar.Select(tab.TabButton);

		tab.Select();

		/*
		keep a list of up to how many tabs there are of what the last tabs
		selected were, to go back to them when the selected tab is closed.
		*/

		if(this.last_selected_tabs.Length>this.Tabs.Length) {
			this.last_selected_tabs.Pop();
		}

		this.last_selected_tabs.Unshift(tab);
	}
}

TabController.prototype.ShowTab=function(tab) {
	tab.Visible.Set(true);
}

TabController.prototype.HideTab=function(tab) {
	tab.Visible.Set(false);

	if(tab===this.SelectedTab) {
		this.select_last_visible_tab();
	}
}

TabController.prototype.update_hash=function() {
	this.HashUpdate.Fire({
		Component: this.hash_list.join(",")
	});
}

TabController.prototype.LoadHashComponent=function(str) {
	var str, type, id, tab;

	if(is_string(str) && str.length>0) {
		this.hash_list=str.split(",");

		for(var i=0; i<this.hash_list.length; i++) {
			str=this.hash_list[i];
			type=str.substr(0, 1);
			id=parseInt(str.substr(1));

			if((type in this.hash_tabs) && is_number(id)) {
				tab=this.Add(this.hash_tabs[type]);
				tab.CreateTable();
				tab.Table.Load(id);
			}
		}
	}

	else {
		this.hash_list=[];
	}
}