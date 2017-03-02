function TabBar(parent) {
	Control.implement(this, parent, true);

	this.TabButtons=new List();

	this.scroll=0;
	this.padding=0;
	this.spacing=3;
	this.left_margin=0;

	this.LeftMargin=new Property(this, function() {
		return this.left_margin;
	}, function(value) {
		this.left_margin=value;
		this.UpdateHtml();
	});

	this.Padding=new Property(this, function() {
		return this.padding;
	}, function(value) {
		this.padding=value;
		this.UpdateHtml();
	});

	this.Spacing=new Property(this, function() {
		return this.spacing;
	}, function(value) {
		this.spacing=value;
		this.UpdateHtml();
	});

	this.Scroll=new Property(this, function() {
		return this.scroll;
	}, function(value) {
		this.scroll=value;
		this.UpdateHtml();
	});

	this.SetupHtml();
}

TabBar.prototype.SetupHtml=function() {
	this.ButtonContainer=$("*div");

	this.Node.appendChild(this.ButtonContainer);
	this.clear_both=$("*div");
	this.ButtonContainer.appendChild(this.clear_both);

	Dom.Style(this.clear_both, {
		clear: "both"
	});

	Dom.Style(this.ButtonContainer, {
		position: "relative"
	});

	this.UpdateHtml();
}

TabBar.prototype.UpdateHtml=function(dont_invalidate) {
	this.TabButtons.Each(function(button) {
		if(button==this.TabButtons.FirstItem()) {
			button.LeftMargin.Set(this.left_margin);
		}

		else {
			button.LeftMargin.Set(0);
		}

		if(button==this.TabButtons.LastItem()) {
			button.RightMargin.Set(0);
		}

		else {
			button.RightMargin.Set(this.spacing);
		}
	}, this);

	Dom.Style(this.ButtonContainer, {
		top: 0,
		left: this.scroll,
		padding: this.padding
	});
}

TabBar.prototype.Add=function() {
	var tab_button=new TabButton();

	this.ButtonContainer.insertBefore(tab_button.Node, this.clear_both);

	tab_button.SetupHtml();

	this.TabButtons.Add(tab_button);
	this.UpdateHtml();

	return tab_button;
}

TabBar.prototype.Remove=function(tab_button) {
	tab_button.Remove();
	this.TabButtons.Remove(tab_button);
}

TabBar.prototype.Select=function(button) {
	this.TabButtons.Each(function(button) {
		button.Deselect();
	});

	button.Select();
}