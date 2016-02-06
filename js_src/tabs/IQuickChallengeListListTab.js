function IQuickChallengeListListTab() {
	this.Detachable = false;
	this.Closeable = false;
	this.Body = new QuickChallengeListListTabBody(this.TabPage.Inner);
}