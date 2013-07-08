<?php
include "html/basic_top.php";
?>
<div class="register">
	<?php
	if(isset($_POST["register"])) {
		if(count($errors)>0) {
			foreach($errors as $error) {
				echo "<span class=\"signin_error\">$error</span><br>";
			}

			echo "<br>";
		}
	}
	?>
	<div style="font-size: 1.3em; font-weight: bold; margin-bottom: 1em">Create account</div>
	<form method="POST">
		<input type="hidden" name="captcha" id="cap_answer">
		<div class="register_input_row">
			<div>
				<div class="ib">
					<label for="username">
						Username (3 or more characters.  Can contain letters, numbers, spaces and underscores.)
					</label>
					<select class="novis nospace"></select>
				</div>
			</div>
			<div>
				<div class="ib">
					<input type="text" name="username" id="username" value="<?php echo $_POST["username"]; ?>" autofocus>
				</div>
			</div>
		</div>
		<div class="register_input_row">
			<div>
				<div class="ib">
					<label for="email">
						E-mail (optional)
					</label>
					<select class="novis nospace"></select>
				</div>
			</div>
			<div>
				<div class="ib">
					<input type="email" name="email" id="email" value="<?php echo $_POST["email"]; ?>">
				</div>
			</div>
		</div>
		<div class="register_input_row">
			<div>
				<div class="ib">
					<label for="password">
						Password (6 or more characters.  Can contain anything except quotes and backslashes.)
					</label>
					<select class="novis nospace"></select>
				</div>
			</div>
			<div>
				<div class="ib">
					<input type="password" name="password" id="password" value="<?php echo $_POST["password"]; ?>" autocomplete="off">
				</div>
			</div>
		</div>
		<div class="register_input_row">
			<div>
				<div class="ib">
					<label for="password_confirm">
						Confirm password
					</label>
					<select class="novis nospace"></select>
				</div>
			</div>
			<div>
				<div class="ib">
					<input type="password" name="password_confirm" id="password_confirm" value="<?php echo $_POST["password_confirm"]; ?>" autocomplete="off">
				</div>
			</div>
		</div>
		<div class="register_input_row nodisp">
			<div>
				<div class="ib">
					<label for="password_confirm">
						<?php
						echo $_SESSION["captcha_message"];
						?>
						<br>
						<span class="smalltext">
							This verifies that you are human and that your browser supports
							the code necessary to make the program work.
						</span>
					</label>
					<select class="novis nospace"></select>
				</div>
			</div>
			<input type="button" id="captcha_reset" value="Reset board">
			<div id="captcha_board">

			</div>
		</div>
		<div>
			<div class="ib">
				<input type="submit" name="register" value="Register">
			</div>
		</div>
	</form>
</div>