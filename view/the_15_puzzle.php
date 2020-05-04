<?php
	require_once "model/The15Puzzle.php";
	$board = $_SESSION['The15Puzzle']->getState();
	$nummoves = $_SESSION["The15Puzzle"]->getNumMoves();
	$display_nummoves = "</br>Number of slides made: " . $nummoves;

	$display_game = array();
	for($slot = 0; $slot < count($board); $slot++) {
		if ($slot % 4 == 0) {
			$display_game[]="<tr>";
		}
		$display_game[]="<td><button class=\"img\" type=\"submit\" name=\"move\" value=" . $board[$slot] . " ><img width=\"100\" height=\"100\" src=\"images16/" . $board[$slot] . ".gif\" /></button></td>";
		if ($slot % 4 == 3) {
			$display_game[]="</tr>";
		}
	}

	$isSolved=($_SESSION["The15Puzzle"]->isSolved()) ? True : False;
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
			<h1>The 15 Puzzle</h1>
			<p>Order the tiles starting from the top left corner of the board, to the bottom right corner of the board.
			</br> Do this by sliding (click) a tile to the empty spot.</p>
			<table border="border">
				<form method="post">
					<?php foreach($display_game as $tile)echo($tile); ?>
				</form>
			</table>

			<?php echo(view_errors($errors)); ?>
			<?php echo($display_nummoves) ?>

			<form method="post">
				<button type="submit" name="submit" value="reset">Reset</button>
				<button type="submit" name="submit" value="randomize">Randomize</button>
			</form>
			<p>Note: Resetting does not reset the number of slides made. It goes back to the starting problem.</p>
			<?php
				if($isSolved){
					echo($win_msg);
			?>
					<form method="post">
						<button class="RestartButton" type="submit" name="submit" value="Restart The 15 Puzzle Game">Restart</button>
					</form>
			<?php
				}
			?>
		</main>
		<footer>
		</footer>
	</body>
</html>
