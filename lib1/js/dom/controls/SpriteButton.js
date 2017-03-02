function SpriteButton(parent, width, height, image) {
	Control.implement(this, parent);

	this.Click=new Event(this);

	this.width=width;
	this.height=height;
	this.image=image;

	/*
	alternate states - each item of the array is another state the button can
	be in (mouse over etc)

	the index of the array specifies how far along in the image the state is,
	e.g.

	[SpriteButton.HOVER, SpriteButton.ON]

	image:

	[default state][hover state][on state]
	<-- width ----><-- width --><- width->
	*/

	this.States=new List();
	this.States.Add(SpriteButton.DEFAULT); //the default is always the 0th element

	this.Image=new Property(this, function() {
		return this.image;
	}, function(value) {
		this.image=value;
		this.UpdateHtml();
	});

	this.SetupHtml();
}

SpriteButton.DEFAULT=0;
SpriteButton.HOVER=1;
SpriteButton.ON=2;
SpriteButton.DOWN=3;

SpriteButton.prototype.SetupHtml=function() {
	var self=this;

	Dom.Style(this.Node, {
		display: "inline-block",
		cursor: "pointer"
	});

	Dom.AddEventHandler(this.Node, "click", function(e) {
		App.ClickedObjects.Add(self);
		self.Click.Fire();
	}, true);

	Dom.AddEventHandler(this.Node, "mouseover", function() {
		self.States.Each(function(item, i) {
			if(item===SpriteButton.HOVER) {
				this.Select(i);
			}
		}, self);
	});

	Dom.AddEventHandler(this.Node, "mouseout", function() {
		self.Select(0);
	});

	this.UpdateHtml();
}

SpriteButton.prototype.UpdateHtml=function() {
	Dom.Style(this.Node, {
		width: this.width,
		height: this.height,
		backgroundImage: Dom.CssUrl(ap(this.image)),
		backgroundRepeat: "no-repeat",
		backgroundPosition: "0 0"
	});
}

/*
change the image by changing the background image offset in multiples of the width
*/

SpriteButton.prototype.Select=function(index) {
	var pos=""+(-index*this.width)+"px 0px";

	Dom.Style(this.Node, {
		backgroundPosition: pos
	});
}