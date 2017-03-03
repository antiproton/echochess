Function.prototype.implement = function() {
	for(var i = 0; i < arguments.length; i++) {
		for(var method in arguments[i].prototype) {
			this.prototype[method] = arguments[i].prototype[method];
		}
	}
}