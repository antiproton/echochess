/*
some functionality for ui elements, but more abstract than Control -
doesn't involve html elements, so can be implemented by higher level
objects (e.g. Tab)
*/

function UiElement() {
	/*
	Fire this when a node's size or something changes and the parent
	(code responsible for creating this	object) needs to adjust
	the layout.

	e.g. if the height of the current TabPage in a TabController
	changes, it the TabPage should fire UiUpdate, and if necessary
	the TabController will have set up a handler when it created
	the tab.
	*/

	this.UiUpdate=new Event(this);
}