/*
wrapper class for a "select" element with a label to the left of it

TODO start using inline block for the label/select container divs, avoids
having to specify width.  NOTE should still be able to set label width
though
*/

function DropDown(parent) {
	Control.implement(this, parent);
	InputControl.implement(this);

	this.old_value=null;
	this.width=null;

	this.SelectionChanged=new Event(this);

	this.Index=new Property(this, function() {
		return this.InputNode.selectedIndex;
	}, function(value) {
		this.InputNode.selectedIndex=value;
	});

	this.Value=new Property(this, function() {
		return this.InputNode.value;
	}, function(value) {
		this.InputNode.value=value;
		this.update_display_only_label();
	});

	this.Width=new Property(this, function() {
		return this.width;
	}, function(value) {
		this.width=value;
		this.UpdateHtml();
	});

	this.SetupHtml();
}

DropDown.prototype.SetupHtml=function() {
	var self=this;

	this.inner=div(this.Node);

	this.container_configurable=new Container(this.inner);
	this.container_display_only=new Container(this.inner);

	this.InputNode=$("*select");
	this.container_configurable.Node.appendChild(this.InputNode);

	InputControl.prototype.SetupHtml.call(this);

	Dom.AddEventHandler(this.InputNode, "change", function() {
		self.SelectionChanged.Fire({
			OldValue: self.old_value,
			NewValue: self.InputNode.value
		});

		self.old_value=self.InputNode.value;
	});

	Dom.Style(this.container_display_only.Node, {
		border: "1px solid #cbcbcb",
		borderRadius: 3,
		backgroundColor: "#efefef"
	});

	this.display_only_inner=div(this.container_display_only.Node);
	this.label_display_only=new Label(this.display_only_inner, "");

	Dom.AddClass(this.display_only_inner, "dropdown_display_only");

	this.UpdateHtml();
}

DropDown.prototype.UpdateHtml=function() {
	this.update_display_only_label();

	if(this.display_only) {
		this.container_configurable.Hide();
		this.container_display_only.Show();
	}

	else {
		this.container_configurable.Show();
		this.container_display_only.Hide();
	}

	var width="auto";

	if(this.width!==null) {
		width=this.width;
	}

	Dom.Style(this.InputNode, {
		width: width
	});
}

DropDown.prototype.update_display_only_label=function() {
	var index=this.InputNode.selectedIndex;

	if(index>-1) {
		this.label_display_only.Text.Set(this.InputNode.options[index].innerHTML);
	}
}

DropDown.prototype.Add=function(value, label) {
	var option=$("*option");

	this.InputNode.appendChild(option);

	option.value=value;
	option.innerHTML=label;

	this.old_value=this.InputNode.value;
}

DropDown.prototype.Remove=function(value) {
	//TODO
}

DropDown.prototype.Clear=function() {
	Dom.ClearNode(this.InputNode);
}

/*
show/hide overridden to not just hide InputNode
*/

DropDown.prototype.Hide=function() {
	Control.prototype.Hide.call(this);
}

DropDown.prototype.Show=function() {
	Control.prototype.Show.call(this);
}