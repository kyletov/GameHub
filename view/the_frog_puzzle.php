<?php
	require_once "model/TheFrogPuzzle.php";
	$board = $_SESSION['TheFrogPuzzle']->getState();
	$nummoves = $_SESSION["TheFrogPuzzle"]->getNumMoves();
	$display_nummoves = "</br>Number of jumps made: " . $nummoves;

	$display_game = array();
	for($slot = 0; $slot < count($board); $slot++) {
		if ($board[$slot] > 0) {
			$display_game[]="<td><button class=\"img\" type=\"submit\" name=\"move\" value=" . $board[$slot] . " ><img width=\"50\" height=\"50\" src=\"imagesFrogs/yellowFrog.gif\" /></button></td>";
		} else if ($board[$slot] < 0) {
			$display_game[]="<td><button class=\"img\" type=\"submit\" name=\"move\" value=" . $board[$slot] . " ><img width=\"50\" height=\"50\" src=\"imagesFrogs/greenFrog.gif\" /></button></td>";
		} else {
			$display_game[]="<td><button class=\"img\" type=\"submit\" name=\"move\" value=" . $board[$slot] . " ><img width=\"50\" height=\"50\" src=\"imagesFrogs/empty.gif\" /></button></td>";
		}
	}

	$win_msg = "<br/>You won the game! Please restart the game to play again.";
	$lose_msg = "<br/>No frogs can jump anymore. Please try again.";

	$game_ended = ($_SESSION["TheFrogPuzzle"]->isSolved() || $_SESSION["TheFrogPuzzle"]->noMovesLeft()) ? True : False;
	$isSolved = ($_SESSION["TheFrogPuzzle"]->isSolved()) ? True : False;

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
			<h1>The Frog Puzzle</h1>
			<p>Try to get all the yellow frogs to the right, and all the green frogs to the left. They can only jump in the direction that they are facing and can either hop to the space beside them, or over another frog. Click on the frog to make them jump.</p></br>
			<table>
				<tr>
					<form method="post">
						<?php foreach($display_game as $tile)echo($tile); ?>
					</form>
				</tr>
			</table>

			<?php echo(view_errors($errors)); ?>
			<?php echo($display_nummoves) ?>

			<?php
				foreach($_SESSION['TheFrogPuzzle']->history as $key=>$value){
					echo("<br/> $value");
				}
				if($game_ended){
					if($isSolved){
						echo($win_msg);
					} else {
						echo($lose_msg);
					}
			?>
					<form method="post">
						<button class="RestartButton" type="submit" name="submit" value="Restart The Frog Puzzle Game">Restart</button>
					</form>
			<?php
				}
			?>
		</main>
		<footer>
		</footer>
	</body>
</html>
