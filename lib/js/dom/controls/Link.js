/*
Link with display: inline block - useful for getting vertical
alignment with form elements
*/

function Link(parent, text, href) {
	Control.implement(this, parent, false, "a");
	href=href||"javascript:void(0)";

	this.text=text;
	this.href=href;

	this.Text=new Property(this, function() {
		return this.text;
	}, function(value) {
		this.text=value;
		this.UpdateHtml();
	});

	this.Href=new Property(this, function() {
		return this.href;
	}, function(value) {
		this.href=value;
		this.UpdateHtml();
	});

	this.Click=new Event(this);

	this.SetupHtml();
}

Link.prototype.SetupHtml=function() {
	Dom.Style(this.Node, {
		display: "inline-block",
		verticalAlign: "middle"
	});
	
	Dom.AddEventHandler(this.Node, "click", (function() {
		this.Click.Fire();
	}).bind(this));

	this.UpdateHtml();
}

Link.prototype.UpdateHtml=function() {
	this.Node.innerHTML=this.text;
	this.Node.href=this.href;
}