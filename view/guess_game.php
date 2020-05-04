<?php
	require_once "model/GuessGame.php";
	// So I don't have to deal with uninitialized $_REQUEST['guess']
	$_REQUEST['guess']=!empty($_REQUEST['guess']) ? $_REQUEST['guess'] : '';
	$nummoves = $_SESSION["GuessGame"]->getNumMoves();
	$display_nummoves = "</br>Number of guesses made: " . $nummoves;

	$isNotCorrect = $_SESSION["GuessGame"]->getState()!="correct" ? True : False;
	$isCorrect = !$isNotCorrect;

	$win_msg="<br/>You won the game! Restart to play again.";
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
			<h1>Guess Game</h1>
			<?php if($isNotCorrect){ ?>
					<p>A secret number has been chosen from 1 - 100, try to guess it.</p>
					<form method="post">
						Your Guess: <input type="text" name="guess" value="<?php echo($_REQUEST['guess']); ?>" /> <input type="submit" name="submit" value="check my guess" />
					</form>
			<?php } ?>

			<?php echo(view_errors($errors)); ?>
			<?php echo($display_nummoves); ?>

			<?php
				foreach($_SESSION['GuessGame']->history as $key=>$value){
					echo("<br/> $value");
				}
				if($isCorrect){
					echo($win_msg);
			?>
					<form method="post">
						<button class="RestartButton" type="submit" name="submit" value="Restart Guess Game">Restart</button>
					</form>
			<?php
				}
			?>
		</main>
		<footer>
		</footer>
	</body>
</html>
