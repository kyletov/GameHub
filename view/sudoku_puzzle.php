<?php
	require_once "model/Sudoku.php";
	$board = $_SESSION['Sudoku']->getState();
	$nummoves = $_SESSION["Sudoku"]->getNumMoves();
	$display_nummoves = "</br>Number of clicks made: " . $nummoves;

	$display_game = array();
	for($i = 0; $i < count($board); $i++){
		for($j = 0; $j < count($board); $j++){
			$pos = ($i*count($board)) + $j;
			if($_SESSION['Sudoku']->isMovePossible($pos)){
				if ($j == 0){
					$display_game[]="<tr>";
				}
				$display_game[]="<td><button class=\"img\" type=\"submit\" name=\"move\" value=" . $pos . " style=\"padding:0; width:auto; height:auto\"><img style=\"margin:0; padding:0\" width=\"50\" height=\"50\" src=\"imagesSudoku/" . $board[$i][$j] . ".gif\" /></button></td>";
				if ($j == count($board)-1) {
					$display_game[]="</tr>";
				}
			} else {
				if ($j == 0){
					$display_game[]="<tr>";
				}
				$display_game[]="<td><button class=\"img\" type=\"submit\" name=\"move\" value=" . $pos . " style=\"padding:0; width:auto; height:auto\"><img style=\"margin:0; padding:0\" width=\"50\" height=\"50\" src=\"imagesSudoku/" . $board[$i][$j] . "start.gif\" /></button></td>";
				if ($j == count($board)-1) {
					$display_game[]="</tr>";
				}
			}
		}
	}

	$isEraseMode=$_SESSION["Sudoku"]->isEraseMode();
	$cell_msg="<br/>Click on the box that you want to clear.";

	$isSolved=($_SESSION["Sudoku"]->isSolved()) ? True : False;
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
			<h1>Sudoku Puzzle</h1>
			<table border="border">
				<form method="post">
					<?php foreach($display_game as $tile)echo($tile); ?>
				</form>
			</table>

			<?php echo(view_errors($errors)); ?>
			<?php echo($display_nummoves) ?>
			<?php if($isEraseMode)echo($cell_msg); ?>
			
			<form method="post">
				<input type="submit" name="submit" value="clear all" />
				<input type="submit" name="submit" value="clear one" />
			</form>
			<?php
				if($isSolved){
					echo($win_msg);
			?>
					<form method="post">
						<button class="RestartButton" type="submit" name="submit" value="Restart Sudoku">Restart</button>
					</form>
			<?php
				}
			?>
		</main>
		<footer>
		</footer>
	</body>
</html>
