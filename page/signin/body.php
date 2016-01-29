<?php
require_once "php/User.php";

$user=User::getinst();
?>
<?php
include "html/basic_top.php";
?>
<div class="signin">
	<?php
	if(isset($_POST["signin"]) && !$user->signedin) {
		echo "<span class=\"signin_error\">Incorrect username or password</span><br><br>";
	}
	?>
	<form method="POST" id="signin_form" action="/">
		<div>
			<div class="fl">
				<div class="ib">
					<label for="username">
						Username
					</label>
					<select class="novis nospace"></select>
				</div>
			</div>
			<div class="fr tar">
				<div class="ib">
					<input type="text" name="username" id="username" autofocus>
				</div>
			</div>
			<div class="cb i">

			</div>
		</div>
		<div>
			<div class="fl">
				<div class="ib">
					<label for="password">
						Password
					</label>
					<select class="novis nospace"></select>
				</div>
			</div>
			<div class="fr tar">
				<div class="ib">
					<input type="password" name="password" id="password">
				</div>
			</div>
			<div class="cb i">

			</div>
		</div>
		<div>
		<div>
			<div class="ib">
				<input type="submit" name="signin" value="Sign in">
				<a href="<?php echo ap("/register"); ?>">Register</a><!-- |
				<a href="<?php echo ap("/resetpwd"); ?>">Reset password</a>-->
			</div>
		</div>
		</div>
	</form>
</div>