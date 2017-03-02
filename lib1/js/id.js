(function() {
	var n = 0;

	window.id = function() {
		return "_" + (++n);
	};
})();