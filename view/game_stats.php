<?php
	if(!$dbconn){
		$errors[]="Can't connect to db";
	} else {
		$stats=$_SESSION['gamestats']->get_stats($dbconn, $_SESSION['user']->getUserid());
	}
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
		<?php include_once("lib/navigation.php"); ?>
		<main>
			<h1>Game Stats</h1>
			<table border="solid">
				<tr><th>userid</th><th>game</th><th>number of moves taken to solve the game</th></tr>
				<?php
					if(isset($stats)){
						foreach($stats as $value) {
							echo($value);
						}
					}
				?>
			</table>
		</main>
		<footer>
		</footer>
	</body>
</html>
