function Label(parent, text) {
	text=text||"";

	Control.implement(this, parent, false, "label");

	this.text=text;
	this.html_for=null;

	this.For=new Property(this, function() {
		return this.html_for;
	}, function(value) {
		this.html_for=value;
		this.UpdateHtml();
	});

	this.Text=new Property(this, function() {
		return this.text;
	}, function(value) {
		this.text=value;
		this.UpdateHtml();
	});

	this.SetupHtml();
}

Label.prototype.SetupHtml=function() {
	this.UpdateHtml();
}

Label.prototype.UpdateHtml=function() {
	this.Node.innerHTML=this.text;
	this.Node.htmlFor=this.html_for;
}