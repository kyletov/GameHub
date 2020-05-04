<?php $active = $_SESSION['state']; ?>
<form action="" method="post">
	<nav>
		<ul>
			<li> <button type="submit" name="view" value="game_stats" <?php if($active == "game_stats"){?> class="active" <?php } ?> >Game Stats</button>
			<li> <button type="submit" name="view" value="guess_game" <?php if($active == "guess_game"){?> class="active" <?php } ?> >Guess Game</button>
			<li> <button type="submit" name="view" value="the_15_puzzle" <?php if($active == "the_15_puzzle"){?> class="active" <?php } ?> >The 15 Puzzle</button>
			<li> <button type="submit" name="view" value="the_frog_puzzle" <?php if($active == "the_frog_puzzle"){?> class="active" <?php } ?> >The Frog Puzzle</button>
			<li> <button type="submit" name="view" value="sudoku_puzzle" <?php if($active == "sudoku_puzzle"){?> class="active" <?php } ?> >Sudoku</button>
			<li> <button type="submit" name="view" value="user_profile" <?php if($active == "user_profile"){?> class="active" <?php } ?> >User Profile</button>
			<li> <button type="submit" name="view" value="login">Logout</button>
	    </ul>
	</nav>
</form>