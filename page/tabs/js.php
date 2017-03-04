<?php
//request info from the server

JsRequestInfo::output();

//generic libraries

loads("/lib/js/util");
loads("/lib/js/time.js");
loads("/lib/js/data");
loads("/lib/js/server");
loads("/lib/js/events");
loads("/lib/js/dom");
loads("/lib/js/base");
loads("/lib/js/Base.js");
loads("/lib/js/Property.js");

//database constants and enums

loads("/lib/js/dbenums/chess.js");
loads("/lib/js/dbcodes/chess.js");

//chess-specific libraries

loadw("/js/constants.js");
loadw("/js/chess/constants.js");
loadw("/js/chess");
loadw("/js/analysis");
loadw("/js/controls");
loadw("/js/livechess");
loadw("/js/tabs");
loadw("/js/User.js");
loadw("/js/UserPrefs.js");
loadw("/js/Messages.js");
loadw("/js/Comments.js");
loadw("/js/GenericUpdater.js");
loadw("/js/BoardStylePresets.js");
loadw("/js/App.js");

//tabs initialisation

loadw("/js/page/tabs.js");
?>