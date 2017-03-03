function EditorDetached() {
	//TODO
}


var table
var init_details = null;

Base.Ready.AddHandler(this, function() {
	init_details = Base.GetInitDetails();
	table = new AnalysisTable($("#table"));

	for(var i = 0; i < init_details.Tab.Table.Game.History.MainLine.Line.Length; i++) {
		table.Game.History.Move(init_details.Tab.Table.Game.History.MainLine.Line.Item(i));
	}

	var attach = $("#attach");

	if(Base.IsRoot()) {
		Dom.Style(attach, {
			display: "none"
		});
	}

	else {
		Base.Root.WindowClosing.AddHandler(this, function() {
			Dom.Style(attach, {
				display: "none"
			});
		});

		Dom.AddEventHandler(attach, "click", function() {
			init_details.Tab.Controller.ShowTab(init_details.Tab);
			init_details.Tab.Controller.SelectTab(init_details.Tab);
			window.close();
		});
	}
});