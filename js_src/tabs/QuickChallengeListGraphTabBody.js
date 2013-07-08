function QuickChallengeListGraphTabBody(parent) {
	Control.implement(this, parent);

	this.SetupHtml();
}

QuickChallengeListGraphTabBody.prototype.SetupHtml=function() {
	Dom.AddClass(this.Node, "noselect");

	this.Graph=new Graph(this.Node);
}

QuickChallengeListGraphTabBody.prototype.Update=function(data) {
	this.Graph.Update(data);
}