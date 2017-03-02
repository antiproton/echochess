var Xhr={
	Get: function(url, params) {
		var xhr=new XMLHttpRequest();
		var q="";

		if(params) {
			q="?"+obj_to_get(params);
		}

		xhr.open("GET", url+q, false);
		xhr.send(null);

		return xhr.responseText;
	},

	GetAsync: function(url, handler, params, obj) {
		var xhr=new XMLHttpRequest();
		var q="";

		if(!is_object(obj)) {
			obj=window;
		}

		if(params) {
			q="?"+obj_to_get(params);
		}

		xhr.open("GET", url+q, true);

		if(is_function(handler)) {
			Dom.AddEventHandler(xhr, "readystatechange", function() {
				if(this.readyState==4) {
					handler.call(obj, this.responseText);
				}
			});

			Dom.AddEventHandler(xhr, "error", function() {
				handler.call(obj, this.responseText);
			});
		}

		xhr.send(null);

		return xhr;
	},

	Query: function(url, q) {
		return this.Get(url, {"q": Data.Serialise(q)});
	},

	QueryAsync: function(url, handler, q, obj) {
		var xhr=new XMLHttpRequest();
		var mtime_send=mtime();

		if(!is_object(obj)) {
			obj=window;
		}

		var get=obj_to_get({
			"q": Data.Serialise(q)
		});

		var full_url=url+"?"+get;

		xhr.open("GET", full_url, true);

		if(is_function(handler)) {
			Dom.AddEventHandler(xhr, "readystatechange", function() {
				if(this.readyState==4) {
					handler.call(obj, Data.Unserialise(this.responseText), mtime()-mtime_send);
				}
			});

			Dom.AddEventHandler(xhr, "error", function() {
				handler.call(obj, Data.Unserialise(this.responseText), mtime()-mtime_send);
			});
		}

		xhr.send(null);

		return xhr;
	},

	RunQueryAsync: function(url, q) {
		var xhr=new XMLHttpRequest();

		var get=obj_to_get({
			"q": Data.Serialise(q)
		});

		xhr.open("GET", url+"?"+get, true);
		xhr.send(null);

		return xhr;
	},

	Run: function(url, params) {
		var xhr=new XMLHttpRequest();
		var q="";

		if(params) {
			q="?"+obj_to_get(params);
		}

		xhr.open("GET", url+q, false);
		xhr.send(null);
	},

	RunAsync: function(url, params) {
		var xhr=new XMLHttpRequest();
		var q="";

		if(params) {
			q="?"+obj_to_get(params);
		}

		xhr.open("GET", url+q, true);
		xhr.send(null);

		return xhr;
	}
};