<?php
// So I don't have to deal with unset $_REQUEST['user'] when refilling the form
// You can also take a look at the new ?? operator in PHP7

$_REQUEST['user']=!empty($_REQUEST['user']) ? $_REQUEST['user'] : '';
$_REQUEST['password']=!empty($_REQUEST['password']) ? $_REQUEST['password'] : '';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>Games</title>
	</head>
	<body>
		<header><img style="margin:0; padding:0" src="Title.jpg" /></header>
		<main>
			<h1>Login</h1>
			<form method="post">
				<table>
					<!-- Trick below to re-fill the user form field -->
					<tr><th><label id="user">User</label></th><td><input type="text" name="user" value="<?php echo($_REQUEST['user']); ?>" /></td></tr>
					<tr><th><label id="password">Password</label></th><td> <input type="password" name="password" /></td></tr>
					<tr><th>&nbsp;</th><td><input type="submit" name="submit" value="login" /></td></tr>
					<tr><th>&nbsp;</th><td><?php echo(view_errors($errors)); ?></td></tr>
				</table>
				<button class="link" type="submit" name="submit" value="register">register</button>
			</form>
		</main>
		<footer>
		</footer>
	</body>
</html>
