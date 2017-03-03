var Dom={
	GetBody: function() {
		return $(">body")[0];
	},

	GetOffset: function(element, axis) {
		var os=0;

		do {
			os+=element[["offsetLeft", "offsetTop"][axis]];
		} while(element=element.offsetParent);

		return os;
	},

	GetParentOffset: function(node, axis) { //TODO these will fail if given the root element (no parent)
		return Dom.GetOffset(node, axis)-Dom.GetOffset(node.parentNode, axis);
	},

	GetParentOffsets: function(node) {
		return [Dom.GetParentOffset(node, X), Dom.GetParentOffset(node, Y)];
	},

	GetOffsets: function(element) {
		return [Dom.GetOffset(element, X), Dom.GetOffset(element, Y)];
	},

	Style: function(element, obj) {
		var value;

		for(var prop in obj) {
			value=obj[prop];

			if(is_number(value) && prop!=="zIndex" && prop!=="opacity") {
				value+="px";
			}

			element.style[prop]=value;
		}
	},

	ElsByClass: function(cls) {
		var element=$(">*");
		var matches=[], matching;

		for(var i=0; i<element.length; i++) {
			if(element[i].className) {
				matching=false;

				var list=element[i].className.split(" ");

				for(var j=0; j<list.length; j++) {
					if(list[j]==cls) {
						matching=true;
						break;
					}
				}

				if(matching) {
					matches.push(element[i]);
				}
			}
		}

		return matches;
	},

	InsertAfter: function(parent, node, after) {
		if(!after.nextSibling) {
			parent.appendChild(node);
		}

		else {
			parent.insertBefore(node, after.nextSibling);
		}

		return node;
	},

	GetNodeIndex: function(node) {
		for(var i=0; i<node.parentNode.childNodes.length; i++) {
			if(node.parentNode.childNodes[i]==node) {
				return i;
			}
		}

		return -1;
	},

	ClearNode: function(node) {
		node.innerHTML="";
	},

	CssUrl: function(path) {
		return "url('"+path+"')";
	},

	FillWidth: function(node) {
		Dom.Style(node, {
			width: node.parentNode.offsetWidth
		});
	},

	FillHeight: function(node) {
		Dom.Style(node, {
			height: node.parentNode.offsetHeight
		});
	},

	FillAvailableSpace: function(node, axis, use_screen) {
		var size_prop=["offsetWidth", "offsetHeight"][axis];
		var screen_prop=["innerWidth", "innerHeight"][axis];
		var css_prop=["width", "height"][axis];
		var os=Dom.GetOffsets(node);
		var space_used=0;
		var child;
		var parent=node.parentNode;

		for(var i=0; i<parent.childNodes.length; i++) {
			child_node=parent.childNodes[i];

			if(child_node[size_prop] && child_node!=node) {
				space_used+=child_node[size_prop];
			}
		}

		var css={};
		var available_in_parent=parent[size_prop]-space_used;
		var available_on_screen=window[screen_prop]-os[axis];
		var size=available_in_parent;

		if(use_screen) {
			size=available_on_screen;
		}

		css[css_prop]=size;

		Dom.Style(node, css);
	},

	FillAvailableWidth: function(node) {
		Dom.FillAvailableSpace(node, X);
	},

	FillAvailableHeight: function(node) {
		Dom.FillAvailableSpace(node, Y);
	},

	FillAvailableScreenWidth: function(node) {
		Dom.FillAvailableSpace(node, X, true);
	},

	FillAvailableScreenHeight: function(node) {
		Dom.FillAvailableSpace(node, Y, true);
	},

	AddEventHandler: function(node, event, handler, bind) {
		if(node.addEventListener) { //proceed as normal
			node.addEventListener(event, handler.bind(bind || node));
		}
	},

	RemoveNode: function(node) {
		node.parentNode.removeChild(node);
	},

	AddClass: function(node, cls) {
		if(cls!=="") {
			var cls_list=node.className.split(" ");

			for(var i=0; i<cls_list.length; i++) {
				if(cls_list[i]===cls) {
					return;
				}
			}

			cls_list.push(cls);

			node.className=cls_list.join(" ");
		}
	},

	RemoveClass: function(node, cls) {
		if(cls!=="") {
			var cls_list=node.className.split(" ");

			for(var i=0; i<cls_list.length; i++) {
				if(cls_list[i]===cls) {
					cls_list.splice(i, 1);

					break;
				}
			}

			node.className=cls_list.join(" ");
		}
	},

	DisableDrag: function(node) {
		/*
		stop text selection (for draggable things etc)
		*/

		Dom.Style(node, {
			WebkitTouchCallout: "none",
			WebkitUserSelect: "none",
			KhtmlUserSelect: "none",
			MozUserSelect: "none",
			MsUserSelect: "none",
			UserSelect: "none"
		});
	}
};