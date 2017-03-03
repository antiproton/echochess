function IStartTab() {
	this.Detachable = false;
	this.Body = new StartTabBody(this.TabPage.Inner);
	this.TabButton.Title.Set("Games");
	this.TabButton.ShowDetach.Set(false);
	this.TabButton.ShowClose.Set(false);
	this.last_inner_tab_selected = this.Body.TableListTabCtrl.SelectedTab;
}

/*
deselecting this tab deselects the tab in the inner tabcontrol

that's how they know to stop updating the table list if the
start tab isn't selected (they each stop whenever they are deselected
themselves anyway)

selecting them updates the table lists immediately
*/

IStartTab.prototype.Select = function() {
	Tab.prototype.Select.call(this);

	if(this.last_inner_tab_selected !== null) {
		this.Body.TableListTabCtrl.SelectTab(this.last_inner_tab_selected);
	}
}

IStartTab.prototype.Deselect = function() {
	Tab.prototype.Deselect.call(this);

	this.last_inner_tab_selected = this.Body.TableListTabCtrl.SelectedTab;
	this.Body.TableListTabCtrl.SelectTab(null);
}