/*
basic helper for creating/finding elements

FIXME this has been put here to make it work for now - should change everything
to use the new functions in js_src/dom/util.js
*/

function $(selector) {
	var prefix = selector.charAt(0);
	var param = selector.substr(1);

	switch(prefix) {
		case "#": return document.getElementById(param);
		case ".": return Dom.ElsByClass(param);
		case "@": return document.getElementsByName(param);
		case " > ": return document.getElementsByTagName(param);
		case "*": return document.createElement(param);
		case "%": return document.createTextNode(param);
	}
}

/*
helper function for getting a new div
*/

function div(parent) {
	var tmpdiv = $("*div");

	if(parent) {
		parent.appendChild(tmpdiv);
	}

	return tmpdiv;
}

/*
helper function for getting a new div with display: inline-block
*/

function idiv(parent) {
	var tmpdiv = div(parent);

	Dom.Style(tmpdiv, {
		display: "inline-block",
		verticalAlign: "middle"
	});

	return tmpdiv;
}

/*
shortcut for adding a clear:both div
*/

function cb(parent) {
	var tmp = div(parent);

	Dom.Style(tmp, {
		clear: "both"
	});
}