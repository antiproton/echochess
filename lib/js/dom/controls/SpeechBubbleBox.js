/*
Box with a speech-bubble arrow like this:

               /\
 ==============  ===============
|                               |
|                               |
|         Hello, world.         |
|                               |
|                               |
 ===============================

arrow_position can be TOP, LEFT, BOTTOM or RIGHT.  Other options are
given as an object - see constructor for available options and their
defaults.

The arrow shape is rendered by setting odd values on the css border
properties.

TODO only the TOP arrow position is currently supported
*/

function SpeechBubbleBox(arrow_position, options) {
	Control.implement(this, Dom.GetBody());

	this.ArrowPosition=arrow_position;

	this.Options={
		Width: 200,
		ArrowHeight: 8,
		BorderWidth: 1,
		BorderColour: "#808080",
		FillColour: "#ffffff"
	};

	if(is_object(options)) {
		for(var p in options) {
			this.Options[p]=options[p];
		}
	}

	this.SetupHtml();
}

SpeechBubbleBox.prototype.SetupHtml=function() {
	var self=this;

	Dom.Style(this.Node, {
		position: "absolute"
	});

	Dom.AddEventHandler(this.Node, "click", function() {
		App.ClickedObjects.Add(self);
	});

	switch(this.ArrowPosition) {
		case TOP: {
			this.arrow_container=div(this.Node);
			this.arrow_border=div(this.arrow_container);
			this.arrow_fill=div(this.arrow_border);
			this.main_container=div(this.Node);

			break;
		}
	}

	this.Inner=div(this.main_container);

	this.UpdateHtml();
}

SpeechBubbleBox.prototype.UpdateHtml=function() {
	var arrow_full_height=this.Options.ArrowHeight;
	var arrow_fill_height=this.Options.ArrowHeight-this.Options.BorderWidth;

	switch(this.ArrowPosition) {
		case TOP: {
			Dom.Style(this.arrow_container, {
				position: "absolute",
				width: "100%",
				height: this.Options.ArrowHeight
			});

			Dom.Style(this.arrow_border, {
				position: "absolute",
				top: 0,
				left: (Math.round((this.Options.Width/2)-(this.Options.ArrowHeight/2))),
				zIndex: 2,
				borderTopColor: this.Options.ArrowBorderColour,
				borderBottomColor: this.Options.ArrowBorderColour,
				borderLeftColor: "transparent",
				borderRightColor: "transparent",
				borderStyle: "solid",
				borderTopWidth: 0,
				borderLeftWidth: arrow_full_height,
				borderRightWidth: arrow_full_height,
				borderBottomWidth: arrow_full_height
			});

			Dom.Style(this.arrow_fill, {
				position: "absolute",
				top: this.Options.BorderWidth,
				left: -(this.Options.ArrowHeight-this.Options.BorderWidth),
				zIndex: 3,
				borderTopColor: this.Options.FillColour,
				borderBottomColor: this.Options.FillColour,
				borderLeftColor: "transparent",
				borderRightColor: "transparent",
				borderStyle: "solid",
				borderTopWidth: 0,
				borderLeftWidth: arrow_fill_height,
				borderRightWidth: arrow_fill_height,
				borderBottomWidth: arrow_fill_height

			});

			Dom.Style(this.main_container, {
				position: "absolute",
				top: this.Options.ArrowHeight-this.Options.BorderWidth,
				zIndex: 1,
				width: this.Options.Width,
				borderStyle: "solid",
				borderWidth: this.Options.BorderWidth,
				borderColor: this.Options.BorderColour,
				borderRadius: 3,
				boxShadow: "1px 2px 1px 0px rgba(0, 0, 0, .3)",
				backgroundColor: this.Options.FillColour
			});

			break;
		}
	}
}

SpeechBubbleBox.prototype.SetArrowLocation=function(x, y) {
	switch(this.ArrowPosition) {
		case TOP: {
			Dom.Style(this.Node, {
				top: y,
				left: x-Math.round(this.Options.Width/2)
			});

			break;
		}
	}
}