/*
Control - object representing an HTML node with some useful
methods.

NOTE objects that implement Control can use the Display
property and the Show/Hide methods interchangeably to
do the same thing, but depending on whether implement or override
is used and the order of implementation, objects that implement
both InputControl and Control could get confused when using both
(Control.Display affects .Node; InputControl.Show/Hide affect
.InputNode)
*/

function Control(parent, clear, nodetype) {
	nodetype=nodetype||"div";

	UiElement.implement(this);

	this.Node=$("*"+nodetype);

	if(clear) {
		Dom.ClearNode(parent);
	}

	parent.appendChild(this.Node);

	this.Display=new Property(this, function() {
		return (this.Node.style.display!=="none");
	}, function(value) {
		this.Node.style.display=(value?"":"none");
	});

	this.Visible=new Property(this, function() {
		return (this.Node.style.visibility!=="hidden");
	}, function(value) {
		this.Node.style.visibility=(value?"":"hidden");
	});
}

Control.prototype.Show=function() {
	this.Node.style.display="";
}

Control.prototype.Hide=function() {
	this.Node.style.display="none";
}