function User() {
	this.Username=null;
	this.IsSignedin=false;
	this.Prefs=new UserPrefs(this);
}

User.prototype.SignIn=function(user) {
	this.Username=user;
	this.IsSignedin=true;
}