function IQuickChallengeListTab() {
	this.TabButton.Title.Set("Quick challenges");
	this.Body=new QuickChallengeTabBody(this.TabPage.Inner);
}

IQuickChallengeListTab.prototype.Select=function() {
	Tab.prototype.Select.call(this);
	this.Body.Updating.Set(true);
}

IQuickChallengeListTab.prototype.Deselect=function() {
	Tab.prototype.Deselect.call(this);
	this.Body.Updating.Set(false);
}