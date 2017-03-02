<?php
require_once "base.php";
require_once "include_utils.php";
require_once "php/init_page.php";
require_once "Page.php";
require_once "php/UserPrefs.php";
require_once "JsRequestInfo.php";

/*
some heads change the req - this loop makes sure the new head file is
loaded if that happens.
*/

$original_path = null;

$page = Page::getinst();

while($page->path !== $original_path) {
	$original_path = $page->path;

	if(file_exists($page->head)) {
		include $page->head;
	}

	JsRequestInfo::$data["user"] = [
		"signedin" => $user->signedin,
		"username" => $user->username
	];

	if($user->signedin) {
		JsRequestInfo::$data["user_prefs"] = UserPrefs::get($user->username);
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Chess</title>
		<meta charset="UTF-8">
		<!--<script src="/live.js"></script>-->
		<style type="text/css">
		<?php
		loads("/lib/css/reset.css");
		loads("/lib/css/common.css");

		loadw("/css/main.css");
		loadw("/css/fonts.css");

		if(file_exists($page->css)) {
			include $page->css;
		}
		?>
		</style>
		<script type="text/javascript">
		global = window;
		<?php
		if(file_exists($page->js)) {
			include $page->js;
		}
		?>
		</script>
	</head>
	<body class="<?php echo $page->name; ?>">
		<?php
		if(file_exists($page->body)) {
			include $page->body;
		}
		?>
		<!-- Start of StatCounter Code for Default Guide -->
		<script type="text/javascript">
		var sc_project=9060148;
		var sc_invisible=1;
		var sc_security="42ad842c";
		var scJsHost = (("https:" == document.location.protocol) ?
		"https://secure." : "http://www.");
		document.write("<sc"+"ript type='text/javascript' src='" +
		scJsHost+
		"statcounter.com/counter/counter.js'></"+"script>");
		</script>
		<noscript><div class="statcounter"><a title="web counter"
		href="http://statcounter.com/free-hit-counter/"
		target="_blank"><img class="statcounter"
		src="http://c.statcounter.com/9060148/0/42ad842c/1/"
		alt="web counter"></a></div></noscript>
		<!-- End of StatCounter Code for Default Guide -->
	</body>
</html>