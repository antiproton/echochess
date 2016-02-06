Base.Ready.AddHandler(this, function() {
	/*
	make it so the signin head can redirect with a header to the original
	request including the hash (which isn't normally sent to the server)
	*/
	
	var action = location.origin+location.pathname;

	if(location.search) {
		action += location.search;
		action += "&hash = "+location.hash;
	}

	else {
		action += "?hash = "+location.hash;
	}

	$("#signin_form").action = action;
});