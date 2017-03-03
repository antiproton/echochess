function TabButton(parent) {
	IEventHandlerLogging.implement(this);

	this.Node=div();
	this.Tab=null;
	this.selected=false;

	this.left_margin=0;
	this.right_margin=0;
	this.show_detach=true;
	this.show_close=true;
	this.class_node="";
	this.class_node_on="";
	this.title="";
	this.visible=true;

	this.init_props();

	this.UserSelect=new Event(this);
}

/*
these can't be changed, so to define styles specific to certain sets of
tabs, change the main class names (.class_node/.class_node_on) and use
css selectors for the inner ones, eg

	div.tab_button_2 div.tab_button_title{
		...
	}
*/

TabButton.CLASS_TITLE="tab_button_title";
TabButton.CLASS_BUTTONS="tab_button_buttons";

TabButton.prototype.init_props=function() {
	this.ShowDetach=new Property(this, function() {
		return this.show_detach;
	}, function(value) {
		this.show_detach=value;
		this.UpdateHtml();
	});

	this.ShowClose=new Property(this, function() {
		return this.show_close;
	}, function(value) {
		this.show_close=value;
		this.UpdateHtml();
	});

	this.ClassNode=new Property(this, function() {
		return this.class_node;
	}, function(value) {
		Dom.RemoveClass(this.Node, this.class_node);
		this.class_node=value;
		this.UpdateHtml();
	});

	this.ClassNodeOn=new Property(this, function() {
		return this.class_node_on;
	}, function(value) {
		Dom.RemoveClass(this.Node, this.class_node_on);
		this.class_node_on=value;
		this.UpdateHtml();
	});

	this.Title=new Property(this, function() {
		return this.title;
	}, function(value) {
		this.title=value;
		this.UpdateHtml();
	});

	this.LeftMargin=new Property(this, function() {
		return this.left_margin;
	}, function(value) {
		this.left_margin=value;
		this.UpdateHtml();
	});

	this.RightMargin=new Property(this, function() {
		return this.right_margin;
	}, function(value) {
		this.right_margin=value;
		this.UpdateHtml();
	});

	this.Visible=new Property(this, function() {
		return this.visible;
	}, function(value) {
		this.visible=value;
		this.UpdateHtml();
	});
}

TabButton.prototype.SetupHtml=function() {
	var self=this;
	var cb;

	Dom.AddEventHandler(this.Node, "click", function() {
		if(!App.ClickedObjects.Contains(self.ButtonClose) && !App.ClickedObjects.Contains(self.ButtonDetach)) {
			self.UserSelect.Fire();
		}
	});

	this.title_section=div(this.Node);
	this.buttons_section=div(this.Node);
	cb=div(this.Node);

	Dom.Style(this.title_section, {
		cssFloat: "left"
	});

	Dom.Style(this.buttons_section, {
		cssFloat: "left"
	});

	Dom.Style(cb, {
		clear: "both"
	});

	this.title_container=div(this.title_section);
	this.title_container.className=TabButton.CLASS_TITLE;
	this.Inner=div(this.title_container);

	this.buttons_container=div(this.buttons_section);
	this.buttons_container.className=TabButton.CLASS_BUTTONS;

	this.button_detach_container=div(this.buttons_container);
	this.button_close_container=div(this.buttons_container);
	cb=div(this.buttons_container);

	Dom.Style(this.button_detach_container, {
		cssFloat: "left",
		marginRight: 3
	});

	Dom.Style(this.button_close_container, {
		cssFloat: "left"
	});

	Dom.Style(cb, {
		clear: "both"
	});

	this.ButtonDetach=new SpriteButton(this.button_detach_container, 12, 12, "/img/buttons/detach.png");
	this.ButtonClose=new SpriteButton(this.button_close_container, 12, 12, "/img/buttons/close.png");

	this.ButtonClose.States.Add(SpriteButton.HOVER);
	this.ButtonDetach.States.Add(SpriteButton.HOVER);

	this.UpdateHtml();
}

TabButton.prototype.UpdateHtml=function() {
	Dom.Style(this.Node, {
		display: this.visible?"":"none",
		marginLeft: this.left_margin,
		marginRight: this.right_margin
	});

	Dom.AddClass(this.Node, this.class_node);

	Dom.Style(this.button_detach_container, {
		display: this.show_detach?"block":"none"
	});

	Dom.Style(this.button_close_container, {
		display: this.show_close?"block":"none"
	});

	if(this.selected) {
		Dom.AddClass(this.Node, this.class_node_on);
	}

	else {
		Dom.RemoveClass(this.Node, this.class_node_on);
	}

	this.Inner.innerHTML=this.title;
}

TabButton.prototype.Select=function() {
	this.ButtonClose.Image.Set("/img/buttons/close-on.png");
	this.ButtonDetach.Image.Set("/img/buttons/detach-on.png");
	this.selected=true;
	this.UpdateHtml();
}

TabButton.prototype.Deselect=function() {
	this.ButtonClose.Image.Set("/img/buttons/close.png");
	this.ButtonDetach.Image.Set("/img/buttons/detach.png");
	this.selected=false;
	this.UpdateHtml();
}

TabButton.prototype.Remove=function() {
	this.ClearEventHandlers();
	Dom.RemoveNode(this.Node);
}