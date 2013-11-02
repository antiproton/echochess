<?php
require_once "php/User.php";
require_once "Page.php";

$page=Page::getinst();
$user=User::getinst();
?>
<div class="main">
	<div class="top">
		<div class="logo title">
			<a href="/">Echo Chess</a>
		</div>
		<!--<div class="nav">
			<a href="/tabs">Play</a>
		</div>-->
		<div class="user">
			<?php
			if($user->signedin) {
				echo "Hello, {$user->username} | <a href=\"".ap("/signout")."\">Sign out</a>";
			}

			else {
				echo "<a href=\"".ap("/signin")."\">Sign in</a>";
			}
			?>
		</div>
		<div class="cb i">

		</div>
	</div>
	<?php
	if($page->url_path==="/register" && $user->signedin) {
		echo "<div class=\"register_success\">Registration successful! <a href=\"/tabs\">Go to live chess</a></div>";
	}
	?>
	<div class="livechess">
		<div class="tac p1 header">
			Live Chess
		</div>
		<div class="container">
			<div class="fl half tac">
				<div class="subheader">
					Quick challenges
				</div>
			</div>
			<div class="fl half tac">
				<div class="subheader">
					Custom tables
				</div>
			</div>
			<div class="cb i">

			</div>
		</div>
		<div class="container">
			<div class="fl half tac explain">
				Find a matched opponent and start a game quickly.
			</div>
			<div class="fl half tac explain">
				Join or create a table - choose standard or Bughouse chess and any time control,
				including hourglass and overtime formats.
			</div>
			<div class="cb i">

			</div>
		</div>
		<div class="container">
			<div class="fl half tac explain">
				<!--<div class="img_border">
					<img src="http://img.echochess.com/scr_quick.png">
				</div>-->
				<img src="http://img.echochess.com/scr_quick.png">
			</div>
			<div class="fl half tac explain">
				<!--<div class="img_border">
					<img src="http://img.echochess.com/scr_custom.png">
				</div>-->
				<img src="http://img.echochess.com/scr_custom.png">
			</div>
			<div class="cb i">

			</div>
		</div>
	</div>
	<div class="m1 p1 tac">
		<a class="tabsbutton" href="<?php echo ap("/tabs"); ?>">Go to live chess</a>
		<br><br>
		<a class="register" href="<?php echo ap("/register"); ?>">Create an account</a>
	</div>
	<div class="page page_main">
		<div class="">
			<h2>Full-featured online chess</h2>
			<p>
				Echo Chess is a high-performance online chess application designed to offer
				a high level of control over how games are started and set up through a clean,
				simple interface.  It is the only web-based chess application that supports
				<a href="http://en.wikipedia.org/wiki/Bughouse_chess">Bughouse chess</a>.
			</p>
			<h2>
				Rules
			</h2>
			<p>
				Echo Chess tries to adhere to FIDE rules wherever possible - draws by threefold
				repetition and the 50-move rule must be claimed (a button will appear).
			</p>
			<p>
				<b>Timing</b> - in Bughouse games, white's clock starts after a 2 second delay from when the
				game begins (when everyone has clicked "Ready").  In standard games, white's clock
				starts immediately after black's first move.  This is a deviation from the FIDE rules,
				which seem unfair on black in an online setting because they can't tell when white is
				about to move.
			</p>
		</div>
	</div>
	<?php
	include "html/footer.php";
	?>
</div>