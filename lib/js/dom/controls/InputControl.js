/*
InputControl - useful methods for form controls.

NOTE objects that implement Control can use the Display
property and the Show/Hide methods interchangeably to
do the same thing, but depending on whether implement or override
is used and the order of implementation, objects that implement
both InputControl and Control could get confused when using both
(Control.Display affects .Node; InputControl.Show/Hide affect
.InputNode)
*/

function InputControl() {
	this.display_only=false;
	this.enabled=true;

	this.Display=new Property(this, function() {
		return (this.Node.style.display!=="none");
	}, function(value) {
		this.Node.style.display=(value?"inline-block":"none");
	});

	this.Enabled=new Property(this, function() {
		return !this.InputNode.disabled;
	}, function(value) {
		this.InputNode.disabled=!value;
	});

	this.Disabled=new Property(this, function() {
		return !!this.InputNode.disabled;
	}, function(value) {
		this.InputNode.disabled=value;
	});

	this.DisplayOnly=new Property(this, function() {
		return this.display_only;
	}, function(value) {
		this.display_only=value;
		this.UpdateHtml();
	});

	this.Configurable=new Property(this, function() { //the opposite of DisplayOnly
		return !this.display_only;
	}, function(value) {
		this.display_only=!value;
		this.UpdateHtml();
	});
}

InputControl.prototype.SetupHtml=function() {
	Dom.Style(this.Node, {
		display: "inline-block",
		verticalAlign: "middle",
		margin: "2px 3px 2px 0px"
	});

	Dom.Style(this.InputNode, {
		margin: 0
	});
}

InputControl.prototype.Disable=function() {
	this.InputNode.disabled=true;
}

InputControl.prototype.Enable=function() {
	this.InputNode.disabled=false;
}

InputControl.prototype.Show=function() {
	this.InputNode.style.display="";
}

InputControl.prototype.Hide=function() {
	this.InputNode.style.display="none";
}

InputControl.prototype.Focus=function() {
	this.InputNode.focus();
}

InputControl.prototype.Select=function() {
	this.InputNode.select();
}