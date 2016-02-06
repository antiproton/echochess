function IQuickChallengeListGraphTab() {
	this.Detachable = false;
	this.Closeable = false;
	this.Body = new QuickChallengeListGraphTabBody(this.TabPage.Inner);
}