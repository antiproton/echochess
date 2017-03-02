/*
FIXME stopped working on this after copying some stuff from DropDown.js.

underscore before class name to indicate work-in-progress state.

NOTE don't carry on with table layout, switch to inline block divs
*/

function _TextBoxWithLabel(parent, label, label_width) {
	InputControl.implement(this);
	Control.implement(this, parent);

	this.label_width=label_width;
	this.label=label;
	this.old_value=null;

	this.TextChanged=new Event(this);
	this.KeyPress=new Event(this);

	this.Label=new Property(this, function() {
		return this.label;
	}, function(value) {
		this.label=value;
		this.UpdateHtml();
	});

	this.LabelWidth=new Property(this, function() {
		return this.label_width;
	}, function(value) {
		this.label_width=value;
		this.UpdateHtml();
	});

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

	this.SetupHtml();
}

_TextBoxWithLabel.prototype.SetupHtml=function() {
	var self=this;

	var table=$("*table");

	var tr=$("*tr");
	this.label_td=$("*td");
	var textbox_td=$("*td");

	this.Node.appendChild(table);

	table.appendChild(tr);

	table.cellSpacing=0;
	table.cellPadding=0;

	Dom.Style(table, {
		width: "100%"
	});

	tr.appendChild(this.label_td);
	tr.appendChild(textbox_td);

	this.label_inner=$("*div");
	this.label_td.appendChild(this.label_inner);

	this.label_ctrl=new Label(this.label_inner);

	Dom.Style(this.label_td, {
		//textAlign: "right",
		verticalAlign: "middle"
	});

	Dom.Style(select_td, {
		verticalAlign: "middle"
	});

	this.container_configurable=new Container(textbox_td);
	this.container_display_only=new Container(textbox_td);

	this.InputNode=$("*select");
	this.container_configurable.Node.appendChild(this.InputNode);

	Dom.Style(this.InputNode, {
		width: "100%"
	});

	Dom.AddEventHandler(this.InputNode, "change", function() {
		self.SelectionChanged.Fire({
			OldValue: self.old_value,
			NewValue: self.InputNode.value
		});

		self.old_value=self.InputNode.value;
	});

	this.display_only_inner=$("*div");
	this.container_display_only.Node.appendChild(this.display_only_inner);
	this.label_display_only=new Label(this.display_only_inner, "");

	Dom.AddClass(this.display_only_inner, "dropdown_display_only");

	this.UpdateHtml();
}

_TextBoxWithLabel.prototype.UpdateHtml=function() {
	Dom.Style(this.label_td, {
		width: this.label_width
	});

	if(this.label.length>0) {
		Dom.Style(this.label_inner, {
			paddingRight: 3,
			paddingLeft: 3
		});
	}

	this.label_ctrl.Text.Set(this.label);

	this.update_display_only_label();

	if(this.display_only) {
		this.container_configurable.Hide();
		this.container_display_only.Show();
	}

	else {
		this.container_configurable.Show();
		this.container_display_only.Hide();
	}
}

_TextBoxWithLabel.prototype.update_display_only_label=function() {
	var index=this.InputNode.selectedIndex;

	if(index>-1) {
		this.label_display_only.Text.Set(this.InputNode.options[index].innerHTML);
	}
}

_TextBoxWithLabel.prototype.Add=function(value, label) {
	var option=$("*option");
	this.InputNode.appendChild(option);
	option.value=value;
	option.appendChild($("%"+label));
	this.old_value=this.InputNode.value;
}

_TextBoxWithLabel.prototype.Remove=function(value) {
	//TODO (options should be in a List maybe - can't be arsed with walkin the dom)
}

/*
show/hide overridden to not just hide InputNode
*/

_TextBoxWithLabel.prototype.Hide=function() {
	Control.prototype.Hide.call(this);
}

_TextBoxWithLabel.prototype.Show=function() {
	Control.prototype.Show.call(this);
}