/*
wrapper class for a text input
*/

function TextBox(parent) {
	Control.implement(this, parent);
	InputControl.implement(this);

	this.old_value=null;
	this.width=100;
	this.title="";

	this.TextChanged=new Event(this);
	this.KeyPress=new Event(this);

	this.Value=new Property(this, function() {
		return this.InputNode.value;
	}, function(value) {
		this.InputNode.value=value;
		this.old_value=value;
	});

	this.Width=new Property(this, function() {
		return this.width;
	}, function(value) {
		this.width=value;
		this.UpdateHtml();
	});

	this.Title=new Property(this, function() {
		return this.title;
	}, function(value) {
		this.title=value;
		this.InputNode.title=value;
	});

	this.SetupHtml();
}

TextBox.prototype.SetupHtml=function() {
	var self=this;

	this.input_container=idiv(this.Node);

	this.InputNode=$("*input");
	this.input_container.appendChild(this.InputNode);
	this.InputNode.type="text";
	this.InputNode.spellcheck=false;

	InputControl.prototype.SetupHtml.call(this);

	Dom.AddEventHandler(this.InputNode, "change", function() {
		self.changed();
		self.old_value=self.InputNode.value;
	});

	Dom.AddEventHandler(this.InputNode, "keyup", function(e) {
		if(self.InputNode.value!==self.old_value) {
			self.changed();
		}

		self.old_value=self.InputNode.value;
	});

	Dom.AddEventHandler(this.InputNode, "keypress", function(e) {
		/*
		NOTE chrome seems to behave strangely if the textbox is disabled
		during a key event.  stuff was failing if the KeyPress event was fired
		from the DOM keydown event and a handler tried to disable the input.

		keyup events occur if the keydown of something else causes it to be
		focussed, so that's no good if you only want keyup events that mean
		the whole keypress happened on the input in question.

		keypress works fine.
		*/

		self.KeyPress.Fire(e);
	});

	this.UpdateHtml();
}

TextBox.prototype.changed=function() {
	this.TextChanged.Fire({
		OldValue: this.old_value,
		NewValue: this.InputNode.value
	});
}

TextBox.prototype.UpdateHtml=function() {
	Dom.Style(this.InputNode, {
		width: this.width
	});

	this.Enabled.Set(this.Configurable.Get());

	if(this.display_only) {
		Dom.AddClass(this.InputNode, "textbox_display_only");
	}

	else {
		Dom.RemoveClass(this.InputNode, "textbox_display_only");
	}
}