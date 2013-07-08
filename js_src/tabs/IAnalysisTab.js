function IAnalysisTab() {
	this.Closeable=true;
	this.Detachable=false;
	this.Table=null;
	this.TabButton.Title.Set("Editor "+(++IAnalysisTab.last_no));

	//this.TabButton.ButtonDetach.Click.AddHandler(this, function() {
	//	var self=this;
	//
	//	this.UserDetach.Fire();
	//
	//	var id=Base.Root.Popup(ap("/editor_detached"), 900, 600);
	//
	//	Base.Root.RegisterPopupInitDetails(id, {
	//		Tab: this
	//	});
	//});

	this.TabButton.ButtonDetach.Hide();
}

IAnalysisTab.last_no=0;

IAnalysisTab.prototype.CreateTable=function() {
	if(this.Table!==null) {
		this.Table.Die();
	}

	Dom.ClearNode(this.TabPage.Inner);

	this.Table=new AnalysisTable(this.TabPage.Inner);

	/*
	NOTE these only get fired if the table is stored on the
	server
	*/

	this.Table.Loaded.AddHandler(this, function(data, sender) {
		this.HashAdd.Fire(sender.Id);
	});

	this.Table.Dead.AddHandler(this, function(data, sender) { //happens if tab is closed or new quick challenge replaces current one
		this.HashRemove.Fire(sender.Id);
	});
}