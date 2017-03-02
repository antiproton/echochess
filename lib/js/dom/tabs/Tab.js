function Tab(controller, tab_button, tab_page) {
	IEventHandlerLogging.implement(this);
	UiElement.implement(this);

	this.Selected=false;
	this.visible=true;
	this.Controller=controller;
	this.TabButton=tab_button;
	this.TabPage=tab_page;
	this.Type=Tab;

	tab_button.Tab=this;
	tab_page.Tab=this;

	this.UserClose=new Event(this);
	this.UserSelect=new Event(this);
	this.UserDetach=new Event(this);
	this.UserAttach=new Event(this);

	/*
	HashAdd/Remove - the tab controller keeps a list of the tabs currently
	open in the document fragment identifier, listening for these events
	to add and remove data from its list.

	tabs fire the events with an Id in the data, e.g. ILiveTable tab would
	do this.HashAdd.Fire({Id: this.Table.Id});

	the tab controller will only do anything with the events if it has a
	hash prefix for the tab's Type (the class that is passed to the controller's
	Add method)

	the hash prefix is a static property on the tab type class, and the controller
	adds the prefix to its list if it isn't already there when a tab of that
	type is added.

	the point of all this is so that if the url is

	chess/tabs#l641,e98

	and ILiveTableTab.HashPrefix="l" and IAnalysisTab.HashPrefix="e"

	the controller will call

	var tab=this.Add(ILiveTableTab);
	tab.CreateTable();
	tab.Table.Load(641);

	and then

	var tab=this.Add(IAnalysisTab);
	tab.CreateTable();
	tab.Table.Load(98);

	standard Tabs don't have a hash prefix and should never fire HashAdd, as without
	some id that ultimately points to something in the database, it doesn't mean
	anything.
	*/

	this.HashAdd=new Event(this);
	this.HashRemove=new Event(this);
	this.HashPrefix=null;

	this.Type=Tab;

	this.TabButton.UserSelect.AddHandler(this, function() {
		this.UserSelect.Fire();
	});

	this.TabButton.ButtonClose.Click.AddHandler(this, function() {
		this.UserClose.Fire();
	});

	this.Visible=new Property(this, function() {
		return this.visible;
	}, function(value) {
		this.visible=value;
		this.TabButton.Visible.Set(value);
		this.TabPage.Visible.Set(value);
	});
}

Tab.prototype.Select=function() {
	this.Selected=true;
}

Tab.prototype.Deselect=function() {
	this.Selected=false;
}

Tab.prototype.Remove=function() {
	this.ClearEventHandlers();
}