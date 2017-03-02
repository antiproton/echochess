var Data={
	Serialise: function(obj) {
		return json_encode(obj);
	},

	Unserialise: function(str) {
		return json_decode(str);
	}
};