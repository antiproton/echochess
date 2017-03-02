function xhr(url, handler, q, obj) {
	obj = obj||window;
	q = q||{};

	var req = new XMLHttpRequest();
	var timeSent = time();

	req.open("GET", url+"?q = "+JSON.stringify(q), true);

	req.addEventListener("readystatechange", function() {
		if(this.readyState === 4) {
			handler.call(obj, JSON.parse(this.responseText), time()-timeSent, this);
		}
	});

	req.addEventListener("error", function() {
		handler.call(obj, JSON.parse(this.responseText), time()-timeSent, this);
	});

	req.send(null);

	return req;
}