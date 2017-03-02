/*
buttons like on tape recorders

same functionality as radio buttons
*/

function SelectorButton(parent, items) { //items must be passed now, no support for adding/removing later
	Control.implement(this, parent);
	InputControl.implement(this);

	this.Items=items;

	this.enabled=true;
	this.value=null;
	this.background_normal="-webkit-linear-gradient(bottom, rgb(230,230,230) 0%, rgb(255,255,255) 100%)";
	this.background_selected="-webkit-linear-gradient(bottom, rgb(255,255,255) 0%, rgb(230,230,230) 100%)";

	this.Enabled=new Property(this, function() {
		return this.enabled;
	}, function(value) {
		this.enabled=value;
		this.UpdateHtml();
	});

	this.Disabled=new Property(this, function() {
		return !this.enabled;
	}, function(value) {
		this.Enabled.Set(!value);
	});

	this.Value=new Property(this, function() {
		return this.value;
	}, function(value) {
		this.value=value;
		this.UpdateHtml();
	});

	if(this.Items.length>0) {
		this.value=this.Items[0].Value;
	}

	this.SelectionChanged=new Event(this);

	this.SetupHtml();
}

SelectorButton.prototype.SetupHtml=function() {
	this.InputNode=div(this.Node);
	InputControl.prototype.SetupHtml.call(this);

	this.inner=div(this.Node);

	Dom.Style(this.inner, {
		fontSize: 11,
		cssFloat: "left",
		border: "1px solid #bebebe",
		borderRadius: 2
	});

	var cb=div(this.Node);

	Dom.Style(cb, {
		clear: "both"
	});

	var label, value, con, tmp;
	var first_item=true;
	var self=this;

	for(var i=0; i<this.Items.length; i++) {
		label=this.Items[i].Label;
		value=this.Items[i].Value;

		con=div(this.inner);
		this.Items[i].div=con;

		Dom.Style(con, {
			cssFloat: "left",
			borderStyle: "solid",
			borderColor: "#bebebe"
		});

		tmp=div(con);

		Dom.Style(tmp, {
			padding: 5
		});

		tmp.appendChild($("%"+label));

		Dom.Style(tmp, {
			cursor: "pointer"
		});

		Dom.AddEventHandler(tmp, "click", (function(val) {
			return function() {
				if(self.enabled) {
					self.Value.Set(val);
					self.SelectionChanged.Fire();
				}
			};
		})(value));

		if(first_item) {
			Dom.Style(con, {
				borderWidth: 0
			});
		}

		else {
			Dom.Style(con, {
				borderWidth: "0 0 0 1px"
			});
		}

		first_item=false;
	}

	cb=div(this.inner);

	Dom.Style(cb, {
		clear: "both"
	});

	this.UpdateHtml();
}

SelectorButton.prototype.UpdateHtml=function() {
	for(var i=0; i<this.Items.length; i++) {
		if(this.Items[i].Value===this.value) {
			Dom.Style(this.Items[i].div, {
				backgroundColor: "#dfdfdf",
				backgroundImage: this.background_selected
			});
		}

		else {
			Dom.Style(this.Items[i].div, {
				backgroundColor: "#ffffff",
				backgroundImage: this.background_normal
			});
		}
	}
}