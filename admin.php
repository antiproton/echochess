<?php
require_once "base.php";
require_once "php/init.php";

$emails=Db::col("select distinct email from users where email!='' and email is not null");

if($session->user->username!=="gus") {
	exit;
}

if(isset($_POST["submit"])) {
	$recipients=explode("\n", trim($_POST["emails"]));

	foreach($recipients as $addr) {
		mail($addr, $_POST["subject"], $_POST["message"], "From: EchoChess");
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Admin</title>
	</head>
	<body>
		<form method="POST">
			Emails:
			<br>
			<textarea name="emails" cols="80" rows="24"><?php
			foreach($emails as $email) {
				echo "$email\n";
			}

			echo "springheeljim@gmail.com";
			?></textarea>
			<br><br>
			Subject: <input name="subject">
			<br><br>
			Message:
			<br>
			<textarea name="message" cols="80" rows="20"></textarea>
			<br>
			<input type="submit" name="submit" value="Send">
		</form>
	</body>
</html>