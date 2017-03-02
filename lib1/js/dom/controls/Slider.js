function Slider(parent, min, max, label_min, label_max) {
	InputControl.implement(this);

	this.InputNode=$("*input");
	parent.appendChild(this.InputNode);
	this.InputNode.type="range";

	this.InputNode.min=min||0;
	this.InputNode.max=max||100;
	this.label_min=label_min||"";
	this.label_max=label_max||"";

	this.Min=new Property(this, function() {
		return this.InputNode.min;
	}, function(value) {
		this.InputNode.min=value;
		this.UpdateHtml();
	});

	this.Max=new Property(this, function() {
		return this.InputNode.max;
	}, function(value) {
		this.InputNode.max=value;
		this.UpdateHtml();
	});

	this.LabelMin=new Property(this, function() {
		return this.label_min;
	}, function(value) {
		this.label_min=value;
		this.UpdateHtml();
	});

	this.LabelMax=new Property(this, function() {
		return this.label_max;
	}, function(value) {
		this.label_max=value;
		this.UpdateHtml();
	});

	this.old_value=null;

	this.ValueChanged=new Event(this);

	this.Value=new Property(this, function() {
		return this.InputNode.value;
	}, function(value) {
		this.InputNode.value=value;
		this.old_value=value;
	});

	this.SetupHtml();
}

Slider.prototype.SetupHtml=function() {
	var self=this;

	Dom.AddEventHandler(this.InputNode, "change", function() {
		self.changed();
	});

	this.UpdateHtml();
}

Slider.prototype.changed=function() {
	this.ValueChanged.Fire({
		OldValue: this.old_value,
		NewValue: this.InputNode.value
	});

	this.old_value=this.InputNode.value;
}

Slider.prototype.UpdateHtml=function() {
	this.Enabled.Set(this.Configurable.Get());

	//TODO labels
}