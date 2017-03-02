function TabBody(parent) {
	Control.implement(this, parent, true);

	this.Pages=new List();
	this.SelectedPage=null;

	this.background_color="inherit";

	this.BackgroundColor=new Property(this, function() {
		return this.background_color;
	}, function(value) {
		this.background_color=value;
		this.UpdateHtml();
	});

	this.SetupHtml();
}

TabBody.prototype.SetupHtml=function() {
	this.page_container=div(this.Node);
}

TabBody.prototype.UpdateHtml=function() {

}

TabBody.prototype.Add=function() {
	var page=new TabPage(this.page_container);

	this.Pages.Add(page);

	return page;
}

TabBody.prototype.Remove=function(page) {
	page.Remove();
	this.Pages.Remove(page);
}

TabBody.prototype.Select=function(tab_page) {
	if(this.SelectedPage!==null) {
		this.SelectedPage.Selected.Set(false);
	}

	tab_page.Selected.Set(true);

	this.SelectedPage=tab_page;
}