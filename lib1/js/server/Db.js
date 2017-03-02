var Db={
	js_bool: function(value) {
		var values=[];
		values[DB_BOOL_TRUE]=true;
		values[DB_BOOL_FALSE]=false;

		return values[value];
	},

	db_bool: function(value) {
		return value?DB_BOOL_TRUE:DB_BOOL_FALSE;
	}
};