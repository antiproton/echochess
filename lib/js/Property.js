function Property(obj, get, set) {
	this.Get = get.bind(obj);
	
	if(set) {
		this.Set = set.bind(obj);
	}
}