function IUsersTab() {
	this.TabButton.Title.Set("Players");
	this.Body = new UsersTabBody(this.TabPage.Inner);
}

IUsersTab.prototype.Select = function() {
	Tab.prototype.Select.call(this);
	this.Body.Update();
}