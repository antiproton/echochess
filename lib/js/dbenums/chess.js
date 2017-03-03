/*
Code/description pairs from database "chess".

Generated by /home/gus/bin/dbenums at 22:31, 09/06/2013.
*/

global.DbEnums = {};

DbEnums["BRD"] = {};

DbEnums["BRD"]["TNM"] = {Type: "BRD", Code: "TNM", Parent: null, Description: "Tournament"};

DbEnums["CHL"] = {};

DbEnums["CHL"]["QCK"] = {Type: "CHL", Code: "QCK", Parent: null, Description: "Quick challenge"};
DbEnums["CHL"]["CST"] = {Type: "CHL", Code: "CST", Parent: null, Description: "Custom table"};

DbEnums["CMT"] = {};

DbEnums["CMT"]["MSG"] = {Type: "CMT", Code: "MSG", Parent: null, Description: "Private message"};
DbEnums["CMT"]["GAM"] = {Type: "CMT", Code: "GAM", Parent: null, Description: "Archived game"};
DbEnums["CMT"]["TAB"] = {Type: "CMT", Code: "TAB", Parent: null, Description: "Table chat"};

DbEnums["EVT"] = {};

DbEnums["EVT"]["TNM"] = {Type: "EVT", Code: "TNM", Parent: null, Description: "Tournament game"};
DbEnums["EVT"]["CAS"] = {Type: "EVT", Code: "CAS", Parent: null, Description: "Casual game"};

DbEnums["FRM"] = {};

DbEnums["FRM"]["ONE"] = {Type: "FRM", Code: "ONE", Parent: null, Description: "Randomise once"};
DbEnums["FRM"]["EVT"] = {Type: "FRM", Code: "EVT", Parent: null, Description: "Randomise every game"};
DbEnums["FRM"]["EVO"] = {Type: "FRM", Code: "EVO", Parent: null, Description: "Randomise every other game"};

DbEnums["GFM"] = {};

DbEnums["GFM"]["BLZ"] = {Type: "GFM", Code: "BLZ", Parent: null, Description: "Blitz"};
DbEnums["GFM"]["ALL"] = {Type: "GFM", Code: "ALL", Parent: null, Description: "Overall"};
DbEnums["GFM"]["CSP"] = {Type: "GFM", Code: "CSP", Parent: null, Description: "Correspondence"};
DbEnums["GFM"]["STD"] = {Type: "GFM", Code: "STD", Parent: null, Description: "Standard"};
DbEnums["GFM"]["BUL"] = {Type: "GFM", Code: "BUL", Parent: null, Description: "Bullet"};
DbEnums["GFM"]["QCK"] = {Type: "GFM", Code: "QCK", Parent: null, Description: "Quick"};

DbEnums["GST"] = {};

DbEnums["GST"]["PRE"] = {Type: "GST", Code: "PRE", Parent: null, Description: "Pregame"};
DbEnums["GST"]["IPR"] = {Type: "GST", Code: "IPR", Parent: null, Description: "In progress"};
DbEnums["GST"]["FIN"] = {Type: "GST", Code: "FIN", Parent: null, Description: "Finished"};
DbEnums["GST"]["SVR"] = {Type: "GST", Code: "SVR", Parent: null, Description: "Cancelled by the server"};
DbEnums["GST"]["USR"] = {Type: "GST", Code: "USR", Parent: null, Description: "Cancelled by the user"};

DbEnums["GTP"] = {};

DbEnums["GTP"]["STD"] = {Type: "GTP", Code: "STD", Parent: null, Description: "Standard"};
DbEnums["GTP"]["BGH"] = {Type: "GTP", Code: "BGH", Parent: null, Description: "Bughouse"};

DbEnums["GUP"] = {};

DbEnums["GUP"]["LMW"] = {Type: "GUP", Code: "LMW", Parent: null, Description: "Last opened main tabs page for live games"};

DbEnums["MSG"] = {};

DbEnums["MSG"]["INV"] = {Type: "MSG", Code: "INV", Parent: null, Description: "Invite"};
DbEnums["MSG"]["REM"] = {Type: "MSG", Code: "REM", Parent: null, Description: "Rematch offer"};
DbEnums["MSG"]["RDE"] = {Type: "MSG", Code: "RDE", Parent: null, Description: "Rematch decline"};
DbEnums["MSG"]["IDE"] = {Type: "MSG", Code: "IDE", Parent: null, Description: "Invite decline"};
DbEnums["MSG"]["DRA"] = {Type: "MSG", Code: "DRA", Parent: null, Description: "Accept draw"};
DbEnums["MSG"]["DRD"] = {Type: "MSG", Code: "DRD", Parent: null, Description: "Decline draw"};
DbEnums["MSG"]["RCL"] = {Type: "MSG", Code: "RCL", Parent: null, Description: "Cancel rematch offer"};
DbEnums["MSG"]["ODC"] = {Type: "MSG", Code: "ODC", Parent: null, Description: "Opponent disconnected (closed main window)"};
DbEnums["MSG"]["OPC"] = {Type: "MSG", Code: "OPC", Parent: null, Description: "Opponent connected (table load)"};
DbEnums["MSG"]["BTC"] = {Type: "MSG", Code: "BTC", Parent: null, Description: "Bughouse team chat"};

DbEnums["PRL"] = {};

DbEnums["PRL"]["ANY"] = {Type: "PRL", Code: "ANY", Parent: null, Description: "Anyone"};
DbEnums["PRL"]["FRD"] = {Type: "PRL", Code: "FRD", Parent: null, Description: "Friends"};
DbEnums["PRL"]["INV"] = {Type: "PRL", Code: "INV", Parent: null, Description: "Invited"};

DbEnums["PRT"] = {};

DbEnums["PRT"]["WCH"] = {Type: "PRT", Code: "WCH", Parent: null, Description: "Watch"};
DbEnums["PRT"]["PLY"] = {Type: "PRT", Code: "PLY", Parent: null, Description: "Play"};

DbEnums["PST"] = {};

DbEnums["PST"]["ALP"] = {Type: "PST", Code: "ALP", Parent: null, Description: "Alpha"};
DbEnums["PST"]["MEP"] = {Type: "PST", Code: "MEP", Parent: null, Description: "Merida"};

DbEnums["RDT"] = {};

DbEnums["RDT"]["CHK"] = {Type: "RDT", Code: "CHK", Parent: null, Description: "Checkmate"};
DbEnums["RDT"]["RES"] = {Type: "RDT", Code: "RES", Parent: null, Description: "Resignation"};
DbEnums["RDT"]["50M"] = {Type: "RDT", Code: "50M", Parent: null, Description: "Stalemate (50-move rule)"};
DbEnums["RDT"]["3FR"] = {Type: "RDT", Code: "3FR", Parent: null, Description: "Stalemate (threefold repetition)"};
DbEnums["RDT"]["STL"] = {Type: "RDT", Code: "STL", Parent: null, Description: "Stalemate (no moves available)"};
DbEnums["RDT"]["TIM"] = {Type: "RDT", Code: "TIM", Parent: null, Description: "Timeout"};
DbEnums["RDT"]["INS"] = {Type: "RDT", Code: "INS", Parent: null, Description: "Stalemate (insufficient material to checkmate)"};
DbEnums["RDT"]["DRW"] = {Type: "RDT", Code: "DRW", Parent: null, Description: "Agreement"};
DbEnums["RDT"]["BGH"] = {Type: "RDT", Code: "BGH", Parent: null, Description: "The other Bughouse game ended"};

DbEnums["REL"] = {};

DbEnums["REL"]["FRD"] = {Type: "REL", Code: "FRD", Parent: null, Description: "Friends"};

DbEnums["RES"] = {};

DbEnums["RES"]["W"] = {Type: "RES", Code: "W", Parent: null, Description: "White won"};
DbEnums["RES"]["B"] = {Type: "RES", Code: "B", Parent: null, Description: "Black won"};
DbEnums["RES"]["D"] = {Type: "RES", Code: "D", Parent: null, Description: "Draw"};

DbEnums["SBV"] = {};

DbEnums["SBV"]["DBL"] = {Type: "SBV", Code: "DBL", Parent: "960", Description: "Double"};
DbEnums["SBV"]["NON"] = {Type: "SBV", Code: "NON", Parent: null, Description: "Standard"};

DbEnums["STT"] = {};

DbEnums["STT"]["PLR"] = {Type: "STT", Code: "PLR", Parent: null, Description: "Player"};
DbEnums["STT"]["SPC"] = {Type: "STT", Code: "SPC", Parent: null, Description: "Spectator"};

DbEnums["TIM"] = {};

DbEnums["TIM"]["FCH"] = {Type: "TIM", Code: "FCH", Parent: null, Description: "Fischer"};
DbEnums["TIM"]["FAF"] = {Type: "TIM", Code: "FAF", Parent: null, Description: "Fischer After"};
DbEnums["TIM"]["BRN"] = {Type: "TIM", Code: "BRN", Parent: null, Description: "Bronstein Delay"};
DbEnums["TIM"]["SDL"] = {Type: "TIM", Code: "SDL", Parent: null, Description: "Simple Delay"};
DbEnums["TIM"]["HRG"] = {Type: "TIM", Code: "HRG", Parent: null, Description: "Hourglass"};
DbEnums["TIM"]["PER"] = {Type: "TIM", Code: "PER", Parent: null, Description: "Per move"};
DbEnums["TIM"]["NON"] = {Type: "TIM", Code: "NON", Parent: null, Description: "Unlimited time"};
DbEnums["TIM"]["SDD"] = {Type: "TIM", Code: "SDD", Parent: null, Description: "Sudden death"};

DbEnums["UPT"] = {};

DbEnums["UPT"]["PCS"] = {Type: "UPT", Code: "PCS", Parent: null, Description: "Pieces taken"};
DbEnums["UPT"]["HST"] = {Type: "UPT", Code: "HST", Parent: null, Description: "History"};
DbEnums["UPT"]["TBK"] = {Type: "UPT", Code: "TBK", Parent: null, Description: "Undos"};
DbEnums["UPT"]["GAM"] = {Type: "UPT", Code: "GAM", Parent: null, Description: "Game"};
DbEnums["UPT"]["TAB"] = {Type: "UPT", Code: "TAB", Parent: null, Description: "Table"};
DbEnums["UPT"]["SAT"] = {Type: "UPT", Code: "SAT", Parent: null, Description: "Check whether a seat has been taken"};
DbEnums["UPT"]["TIM"] = {Type: "UPT", Code: "TIM", Parent: null, Description: "Time"};
DbEnums["UPT"]["MSG"] = {Type: "UPT", Code: "MSG", Parent: null, Description: "Messages"};
DbEnums["UPT"]["PRE"] = {Type: "UPT", Code: "PRE", Parent: null, Description: "Premoves"};
DbEnums["UPT"]["GEN"] = {Type: "UPT", Code: "GEN", Parent: null, Description: "Generic updates"};
DbEnums["UPT"]["CMT"] = {Type: "UPT", Code: "CMT", Parent: null, Description: "Comments"};
DbEnums["UPT"]["DRC"] = {Type: "UPT", Code: "DRC", Parent: null, Description: "Direct challenge"};

DbEnums["VNT"] = {};

DbEnums["VNT"]["STD"] = {Type: "VNT", Code: "STD", Parent: null, Description: "Standard"};
DbEnums["VNT"]["960"] = {Type: "VNT", Code: "960", Parent: null, Description: "Chess960"};