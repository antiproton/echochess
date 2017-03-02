/*
wrapper for a plain div that has Control methods (Show, Hide etc)
*/

function Container(parent) {
	Control.implement(this, parent);
}