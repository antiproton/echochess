/*
FIXME uses private properties on LiveGame

the hilite methods should probably be available on IGameWithUiBoard or something
*/

function Register() {
	this.init_captcha();
}

Register.prototype.init_captcha=function() {
	this.cap_fen=Base.Request["page"]["captcha"];
	this.cap_answer=$("#cap_answer");

	this.board=new UiBoard($("#captcha_board"));
	this.board.SquareSize.Set(30);
	this.board.SquareColour.Set([BoardStylePresets[0].Light, BoardStylePresets[0].Dark]);
	this.board.PieceStyle.Set(PIECE_STYLE_ALPHA);
	this.board.ShowCoords.Set(true);

	this.cap_game=new AnalysisGame(this.board);
	this.cap_game.SetFen(this.cap_fen);

	this.board.UserMove.AddHandler(this, function(data) {
		LiveGame.prototype.clear_all_hilites.call(this.cap_game);
		LiveGame.prototype.HiliteLastMove.call(this.cap_game);

		if(this.cap_game.History.MainLine.FirstMove!==null) {
			this.cap_answer.value=this.cap_game.History.MainLine.FirstMove.GetLabel();
		}
	});

	Dom.AddEventHandler($("#captcha_reset"), "click", function() {
		this.reset_captcha();
	}, this);
}

Register.prototype.reset_captcha=function() {
	this.cap_game.SetFen(this.cap_fen);
	LiveGame.prototype.clear_all_hilites.call(this.cap_game);
	this.cap_answer.value="";
}

var main;

Base.Ready.AddHandler(this, function() {
	main=new Register();
});