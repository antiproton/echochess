<div class="main">
	<div class="top">
		<div class="logo title">
			<a href="/">Echo Chess</a>
		</div>
		<div class="user">
			<?php
			if($session->user->signedin) {
				echo "Hello, {$session->user->username} | <a href=\"".ap("/signout")."\">Sign out</a>";
			}

			else {
				echo "<a href=\"".ap("/signin")."\">Sign in</a>";
			}
			?>
		</div>
		<div class="cb i">

		</div>
	</div>
	<div class="page">
		<div class="">
			<h2>Privacy</h2>
			<p>
				Data you send to echochess.com will be used only for the functioning of
				the website.  E-mail addresses are not required for any part of the website,
				and if supplied are used for security purposes only (e.g. verifying user
				identification in case of lost login details).
			</p>
			<p>
				All data is stored on a secure server and will not be shared with any 3rd party.
			</p>
			<p>
				This website uses a cookie to identify logged-in users.
			</p>
		</div>
	</div>
</div>