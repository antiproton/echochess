function ITournamentsTab() {
	this.TabButton.Title.Set("Tournaments");
	this.Body = new TournamentsTabBody(this.TabPage.Inner);
}

ITournamentsTab.prototype.Select = function() {
	Tab.prototype.Select.call(this);
	this.Body.Updating.Set(true);
}

ITournamentsTab.prototype.Deselect = function() {
	Tab.prototype.Deselect.call(this);
	this.Body.Updating.Set(false);
}