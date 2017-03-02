function LabelAndInputContainer(parent, options) {
	Control.implement(this, parent);

	this.Options={
		OverallWidth: null,
		LabelWidth: null,
		InputWidth: null,
		LabelAlign: LEFT,
		InputAlign: LEFT
	};

	if(is_object(options)) {
		for(var p in options) {
			this.Options[p]=options[p];
		}
	}

	this.SetupHtml();
}

LabelAndInputContainer.prototype.SetupHtml=function() {
	Dom.Style(this.Node, {
		display: "inline-block",
		verticalAlign: "middle"
	});

	this.label_container=div(this.Node);
	this.input_container=div(this.Node);

	/*
	height adjustment - to get the labels to line up with the input
	controls, there has to be an input control "near" them.  not sure
	why the input controls in the input control containers aren't near
	enough, but putting one here seems to make it work.
	*/

	this.height_adjustment=idiv(this.label_container);

	Dom.Style((new DropDown(this.height_adjustment)).Node, {
		visibility: "hidden",
		width: 0
	});

	this.LabelInner=idiv(this.label_container);
	this.InputInner=idiv(this.input_container);
	this.Label=new Label(this.LabelInner);

	cb(this.Node);

	this.UpdateHtml();
}

LabelAndInputContainer.prototype.UpdateHtml=function() {
	var css_dir=[];

	css_dir[LEFT]="left";
	css_dir[RIGHT]="right";

	var label_style={
		textAlign: css_dir[this.Options.LabelAlign],
		cssFloat: "left"
	};

	if(this.Options.LabelWidth!==null) {
		label_style.width=this.Options.LabelWidth;
	}

	var input_style={
		textAlign: css_dir[this.Options.InputAlign],
		cssFloat: css_dir[this.Options.InputAlign]
	};

	if(this.Options.InputWidth!==null) {
		input_style.width=this.Options.InputWidth;
	}

	Dom.Style(this.label_container, label_style);
	Dom.Style(this.input_container, input_style);

	var node_width="auto";

	if(this.Options.OverallWidth!==null) {
		node_width=this.Options.OverallWidth;
	}

	Dom.Style(this.Node, {
		width: node_width
	});
}