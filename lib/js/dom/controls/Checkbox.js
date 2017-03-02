/*
wrapper class for a checkbox with a label next to it
*/

function Checkbox(parent, label) {
	InputControl.implement(this);
	Control.implement(this, parent);

	this.CheckedChanged=new Event(this);

	this.label=label||"";

	this.Id=Base.GetId();

	this.Label=new Property(this, function() {
		return this.label;
	}, function(value) {
		this.label=value;
		this.UpdateHtml();
	});

	this.Checked=new Property(this, function() {
		return this.InputNode.checked;
	}, function(value) {
		this.InputNode.checked=value;
	});

	this.SetupHtml();
}

Checkbox.prototype.SetupHtml=function() {
	var self=this;

	Dom.Style(this.Node, {
		display: "inline-block",
		verticalAlign: "middle"
	});

	this.cb_container=idiv(this.Node);
	this.label_container=idiv(this.Node);

	Dom.Style(this.cb_container, {
		paddingTop: 2
	});

	this.InputNode=$("*input");
	this.InputNode.type="checkbox";
	this.cb_container.appendChild(this.InputNode);
	this.InputNode.id=this.Id;

	this.label_node=$("*label");
	this.label_container.appendChild(this.label_node);
	this.label_node.htmlFor=this.Id;

	Dom.AddEventHandler(this.InputNode, "change", function() {
		self.CheckedChanged.Fire();
	});

	this.UpdateHtml();
}

Checkbox.prototype.UpdateHtml=function() {
	this.Disabled.Set(this.display_only);
	this.label_node.innerHTML=this.label;
}

/*
show/hide overridden to not just hide InputNode
*/

Checkbox.prototype.Hide=function() {
	Control.prototype.Hide.call(this);
}

Checkbox.prototype.Show=function() {
	Control.prototype.Show.call(this);
}