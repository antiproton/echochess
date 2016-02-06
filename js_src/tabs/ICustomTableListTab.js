function ICustomTableListTab() {
	this.TabButton.Title.Set("Custom tables");
	this.Body = new CustomTableTabBody(this.TabPage.Inner);
}

ICustomTableListTab.prototype.Select = function() {
	Tab.prototype.Select.call(this);
	this.Body.Updating.Set(true);
}

ICustomTableListTab.prototype.Deselect = function() {
	Tab.prototype.Deselect.call(this);
	this.Body.Updating.Set(false);
}