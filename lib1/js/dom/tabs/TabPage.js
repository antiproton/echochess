function TabPage(parent) {
	Control.implement(this, parent);
	IEventHandlerLogging.implement(this);

	this.visible=true;
	this.selected=true;

	this.Visible=new Property(this, function() {
		return this.visible;
	}, function(value) {
		this.visible=value;
		this.UpdateHtml();
	});

	this.Selected=new Property(this, function() {
		return this.selected;
	}, function(value) {
		this.selected=value;
		this.UpdateHtml();
	});

	this.Tab=null;
	this.SetupHtml();
}

TabPage.ZINDEX_BELOW=1;
TabPage.ZINDEX_ABOVE=2;

TabPage.prototype.SetupHtml=function() {
	this.Inner=div(this.Node);
	this.UpdateHtml();
}

TabPage.prototype.UpdateHtml=function() {
	Dom.Style(this.Node, {
		display: (this.visible && this.selected)?"":"none"
	});
}

TabPage.prototype.Remove=function() {
	this.ClearEventHandlers();
	Dom.RemoveNode(this.Node);
}