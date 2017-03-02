function Button(parent, text) {
	Control.implement(this, parent);
	InputControl.implement(this);

	this.text=text;

	this.Click=new Event(this);

	this.Text=new Property(this, function() {
		return this.text;
	}, function(value) {
		this.text=value;
		this.UpdateHtml();
	});

	this.SetupHtml();
}

Button.prototype.SetupHtml=function() {
	var self=this;

	this.InputNode=$("*button");
	this.Node.appendChild(this.InputNode);
	this.InputNode.type="button"; //otherwise chrome treats it as a submit button

	InputControl.prototype.SetupHtml.call(this);

	Dom.AddEventHandler(this.InputNode, "click", function(e) {
		self.Click.Fire({
			Event: e
		});
	});

	this.UpdateHtml();
}

Button.prototype.UpdateHtml=function() {
	this.InputNode.innerHTML=this.text;
}