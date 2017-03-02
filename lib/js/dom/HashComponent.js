/*
HashComponent - something that can store/reload its state from the
hash bit of the url

top level code explicitly adds handlers to one or more objects and
handles updating of the actual window.location - if one of the object's
states depends on other objects it has to handle that itself

e.g. the top level code adds handlers to a tab control, and the tab
control adds handlers for its tabs

the HashUpdate event should be fired with {Component: "string to be
stored in url"}
*/

function HashComponent() {
	this.HashUpdate=new Event(this);
}

HashComponent.prototype.LoadHashComponent=function(str) {
	/*
	objects should define this method with code to remember their state
	again off of the hash (will be the same thing that they passed with HashUpdate)
	*/
}