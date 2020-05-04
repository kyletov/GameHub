<?php
	ini_set('display_errors', 'On');
	require_once "lib/lib.php";
	require_once "model/GateKeeper.php";
	require_once "model/GameStats.php";
	require_once "model/UserProfile.php";
	require_once "model/GuessGame.php";
	require_once "model/The15Puzzle.php";
	require_once "model/TheFrogPuzzle.php";
	require_once "model/Sudoku.php";


	if (!file_exists("sess")) {
		mkdir("sess", 0700);
	}
	session_save_path("sess");
	session_start();

	$dbconn = db_connect();

	$errors=array();
	$view="";

	$GateKeeper = new GateKeeper();

	/* controller code */

	/* local actions, these are state transforms */
	if(!isset($_SESSION['state'])){
		$_SESSION['state']='login';
	}

	switch($_SESSION['state']){
		case "unavailable":
			$oview="unavailable.php";
			$view=isNavbarPressed($oview);
			break;

		case "login":
			// the view we display by default
			$view="login.php";

			// check if submit or not
			if(empty($_REQUEST['submit']) || $_REQUEST['submit']!="login" && $_REQUEST['submit']!="register"){
				break;
			}

			if($_REQUEST['submit']=="register"){
				$_SESSION['state']="register";
				$view="register.php";
				break;
			}

			// validate and set errors
			if(empty($_REQUEST['user']))$errors[]='user is required';
			if(empty($_REQUEST['password']))$errors[]='password is required';
			if(!empty($errors))break;

			// perform operation, switching state and view if necessary
			if(!$dbconn){
				$errors[]="Can't connect to db";
				break;
			}

			$success = $GateKeeper->login($dbconn, $_REQUEST['user'], $_REQUEST['password']);
			if($success){
				$info=$GateKeeper->get_user_profile($dbconn, $_REQUEST['user']);
				$_SESSION['user']=new UserProfile($info['userid'],$_REQUEST['password'],$info['fname'],$info['lname'],$info['gender'],$info['age'],$info['bio']);
        		$_SESSION['gamestats']=new GameStats();
				$_SESSION['state']='game_stats';
				$_SESSION['highscores']=$_SESSION['user']->get_highscores($dbconn);
				$view='game_stats.php';
			} else {
				$errors[]="invalid login";
			}
			break;

		case "register":
			// the view we display by default
			$view="register.php";

			// check if submit or not
			if(empty($_REQUEST['submit']) || ($_REQUEST['submit']!="register" && $_REQUEST['submit']!="back")){
				break;
			}

			if($_REQUEST['submit']=="back"){
				$_SESSION['state']="login";
				$view="login.php";
				break;
			}

			// validate and set errors
			if(empty($_REQUEST['user']))$errors[]='user is required';
			if(empty($_REQUEST['password']))$errors[]='password is required';
			if(empty($_REQUEST['confirm_password']))$errors[]='confirm password is required';
			if(!empty($errors))break;
			if($_REQUEST['password'] != $_REQUEST['confirm_password'])$errors[]="passwords don't match";
			if(!empty($errors))break;

			if(!$dbconn){
				$errors[]="Can't connect to db";
				break;
			}

			$exists = $GateKeeper->check_user_exists($dbconn, $_REQUEST['user']);
			if($exists){
				$errors[]="User already exists";
				$_SESSION['state']='register';
				$view="register.php";
			} else {
				$isRegistered = $GateKeeper->register($dbconn, $_REQUEST['user'], $_REQUEST['password']);
				if($isRegistered){
					$_SESSION['user']=new UserProfile($_REQUEST['user'], $_REQUEST['password']);
	        		$_SESSION['gamestats']=new GameStats();
					$_SESSION['state']='game_stats';
					$_SESSION['highscores']=$_SESSION['user']->get_highscores($dbconn, $_SESSION['user']->getUserid());
					$view='game_stats.php';
				} else {
					$errors[]="Could not complete registration process. Please try again.";
					$_SESSION['state']='register';
					$view="register.php";
				}
			}
			break;

		case "game_stats":
			$oview="game_stats.php";
			$view=isNavbarPressed($oview);
			if($view != $oview){
				break;
			}
			break;

		case "guess_game":
			$oview="guess_game.php";
			$view=isNavbarPressed($oview);
			if($view != $oview){
				break;
			}

			// check if submit or not
			if(empty($_REQUEST['submit'])||$_REQUEST['submit']!="check my guess"){
				break;
			}

			// validate and set errors
			if(!is_numeric($_REQUEST["guess"]))$errors[]="Guess must be numeric.";
			if(!empty($errors))break;

			// perform operation, switching state and view if necessary
			$_SESSION["GuessGame"]->makeGuess($_REQUEST['guess']);
			if($_SESSION["GuessGame"]->getState()=="correct"){
				$_SESSION['guessGameIsStored'] = False;
				$_SESSION['state']="won_guess_game";
				$view="guess_game.php";
			}
			$_REQUEST['guess']="";

			break;

		case "won_guess_game":
			$oview="guess_game.php";
			if(!$_SESSION['guessGameIsStored']){
				if(!$dbconn){
					$errors[]="Can't connect to db";
					break;
				}
				if(!empty($errors))break;

				$game_name = get_class($_SESSION['GuessGame']);
				$num_moves = $_SESSION['GuessGame']->getNumMoves();
				$userid = $_SESSION['user']->getUserid();

				if(!empty($_SESSION['highscores']["GuessGame"])){
					if($num_moves < $_SESSION['highscores']["GuessGame"]){
						if(!$_SESSION['gamestats']->update_game_stats($dbconn, $userid, $game_name, $num_moves)){
							$errors[]="Storing gamestats was not successful.";
						}
					}
				} else {
					if(!$_SESSION['gamestats']->insert_game_stats($dbconn, $userid, $game_name, $num_moves)){
						$errors[]="Storing gamestats was not successful.";
					}
				}

				if(empty($errors)){
					$_SESSION['highscores'][$game_name]=$_SESSION['GuessGame']->numMoves;
					$_SESSION['guessGameIsStored'] = True;
				}
			}

			$view=isNavbarPressed($oview);
			if($view != $oview){
				break;
			}

			// check if submit or not
			if(empty($_REQUEST['submit']))break;

			if($_REQUEST['submit']=="Restart Guess Game"){
				$_SESSION["GuessGame"]=new GuessGame();
				$_SESSION['state']="guess_game";
				$view="guess_game.php";
			}

			break;

		case "the_15_puzzle":
			$oview="the_15_puzzle.php";
			$view=isNavbarPressed($oview);
			if($view != $oview){
				break;
			}

			if(isset($_REQUEST['submit'])){
				if($_REQUEST['submit'] =="reset"){
					$_SESSION['The15Puzzle']->reset();
					break;
				} else if($_REQUEST['submit'] =="randomize"){
					$_SESSION['The15Puzzle']->randomize();
					break;
				}
			}

			if(!isset($_REQUEST['move']))break;
			$board = $_SESSION['The15Puzzle']->getState();
			$empty_position = array_search(0, $board);
			$clicked_position = array_search($_REQUEST['move'], $board);
			if($_REQUEST['move'] == 0||!$_SESSION['The15Puzzle']->isMovePossible($empty_position, $clicked_position))$errors[]="Click an adjacent tile to move to the empty spot.";
			if(!empty($errors))break;

			$_SESSION["The15Puzzle"]->makeMove($_REQUEST['move'], $empty_position, $clicked_position);
			if($_SESSION["The15Puzzle"]->isSolved()){
				$_SESSION['the15PuzzleIsStored'] = False;
				$_SESSION['state']="won_the_15_puzzle";
				$view="the_15_puzzle.php";
			}
			$_REQUEST['move'] = "";
			break;

		case "won_the_15_puzzle":
			$oview="the_15_puzzle.php";
			if(!$_SESSION['the15PuzzleIsStored']){
				if(!$dbconn){
					$errors[]="Can't connect to db";
					break;
				}
				if(!empty($errors))break;

				$game_name = get_class($_SESSION['The15Puzzle']);
				$num_moves = $_SESSION['The15Puzzle']->getNumMoves();
				$userid = $_SESSION['user']->getUserid();

				if(!empty($_SESSION['highscores']["The15Puzzle"])){
					if($num_moves < $_SESSION['highscores']["The15Puzzle"]){
						if(!$_SESSION['gamestats']->update_game_stats($dbconn, $userid, $game_name, $num_moves)){
							$errors[]="Storing gamestats was not successful.";
						}
					}
				} else {
					if(!$_SESSION['gamestats']->insert_game_stats($dbconn, $userid, $game_name, $num_moves)){
						$errors[]="Storing gamestats was not successful.";
					}
				}

				if(empty($errors)){
					$_SESSION['highscores'][$game_name]=$_SESSION['The15Puzzle']->numMoves;
					$_SESSION['the15PuzzleIsStored'] = True;
				}
			}

			$view=isNavbarPressed($oview);
			if($view != $oview){
				break;
			}

			// check if submit or not
			if(empty($_REQUEST['submit']))break;

			if($_REQUEST['submit']=="Restart The 15 Puzzle Game"){
				$_SESSION["The15Puzzle"]=new The15Puzzle(range(0, 15));
				$_SESSION['state']='the_15_puzzle';
				$view="the_15_puzzle.php";
			}

			break;

		case "the_frog_puzzle":
			$oview="the_frog_puzzle.php";
			$view=isNavbarPressed($oview);
			if($view != $oview){
				break;
			}

			if(empty($_REQUEST['move']))break;
			$board = $_SESSION['TheFrogPuzzle']->getState();
			$clicked_position = array_search($_REQUEST['move'], $board);
			if($_REQUEST['move'] == 0 || !$_SESSION['TheFrogPuzzle']->isMovePossible($_REQUEST['move'], $clicked_position))$errors[]="Click a frog that can jump to the empty spot.";
			if(!empty($errors))break;

			$_SESSION["TheFrogPuzzle"]->makeMove($_REQUEST['move'], $clicked_position);
			if($_SESSION["TheFrogPuzzle"]->isSolved()){
				$_SESSION['theFrogPuzzleIsStored'] = False;
				$_SESSION['state']="won_the_frog_puzzle";
				$view="the_frog_puzzle.php";
			} else if ($_SESSION["TheFrogPuzzle"]->noMovesLeft()){
				$_SESSION['theFrogPuzzleIsStored'] = True;
				$_SESSION['state']="won_the_frog_puzzle";
				$view="the_frog_puzzle.php";
			}
			$_REQUEST['move'] = "";
			break;

		case "won_the_frog_puzzle":
			$oview="the_frog_puzzle.php";
			if(!$_SESSION['theFrogPuzzleIsStored']){
				if(!$dbconn){
					$errors[]="Can't connect to db";
					break;
				}
				if(!empty($errors))break;

				$game_name = get_class($_SESSION['TheFrogPuzzle']);
				$num_moves = $_SESSION['TheFrogPuzzle']->getNumMoves();
				$userid = $_SESSION['user']->getUserid();

				if(!empty($_SESSION['highscores']["TheFrogPuzzle"])){
					if($num_moves < $_SESSION['highscores']["TheFrogPuzzle"]){
						if(!$_SESSION['gamestats']->update_game_stats($dbconn, $userid, $game_name, $num_moves)){
							$errors[]="Storing gamestats was not successful.";
						}
					}
				} else {
					if(!$_SESSION['gamestats']->insert_game_stats($dbconn, $userid, $game_name, $num_moves)){
						$errors[]="Storing gamestats was not successful.";
					}
				}

				if(empty($errors)){
					$_SESSION['highscores'][$game_name]=$_SESSION['TheFrogPuzzle']->getNumMoves();
					$_SESSION['theFrogPuzzleIsStored'] = True;
				}
			}

			$view=isNavbarPressed($oview);
			if($view != $oview){
				break;
			}

			// check if submit or not
			if(empty($_REQUEST['submit']))break;

			if ($_REQUEST['submit']=="Restart The Frog Puzzle Game"){
				// perform operation, switching state and view if necessary
				$_SESSION["TheFrogPuzzle"]=new TheFrogPuzzle();
				$_SESSION['state']='the_frog_puzzle';
				$view="the_frog_puzzle.php";
			}

			break;

		case "sudoku_puzzle":
			$oview="sudoku_puzzle.php";
			$view=isNavbarPressed($oview);
			if($view != $oview){
				break;
			}

			if (isset($_REQUEST['submit'])){
				if($_REQUEST['submit'] =="clear all"){
					$_SESSION['Sudoku']->reset();
					$_REQUEST['move'] = "";
					break;
				}
				if($_REQUEST['submit']=="clear one"){
					$_SESSION['Sudoku']->changeMode();
					break;
				}
			}
			if (!isset($_REQUEST['move']))break;
			$board = $_SESSION['Sudoku']->getState();
			if (!$_SESSION['Sudoku']->isMovePossible($_REQUEST['move']))$errors[]="Click a number that is not originally there.";
			if(!empty($errors))break;
			if($_SESSION["Sudoku"]->isEraseMode()){
				$_SESSION["Sudoku"]->clearCell($_REQUEST['move']);
				$_SESSION['Sudoku']->changeMode();
			}else{
				$_SESSION["Sudoku"]->makeMove($_REQUEST['move']);
			}
			if($_SESSION["Sudoku"]->isSolved()){
				$_SESSION['sudokuIsStored'] = False;
				$_SESSION['state']="won_sudoku_puzzle";
				$view="sudoku_puzzle.php";
			}
			$_REQUEST['move'] = "";
			break;

		case "won_sudoku_puzzle":
			$oview="sudoku_puzzle.php";
			if(!$_SESSION['sudokuIsStored']){
				if(!$dbconn){
					$errors[]="Can't connect to db";
					break;
				}
				if(!empty($errors))break;

				$game_name = get_class($_SESSION['Sudoku']);
				$num_moves = $_SESSION['Sudoku']->getNumMoves();
				$userid = $_SESSION['user']->getUserid();

				if(!empty($_SESSION['highscores']["Sudoku"])){
					if($num_moves < $_SESSION['highscores']["Sudoku"]){
						if(!$_SESSION['gamestats']->update_game_stats($dbconn, $userid, $game_name, $num_moves)){
							$errors[]="Storing gamestats was not successful.";
						}
					}
				} else {
					if(!$_SESSION['gamestats']->insert_game_stats($dbconn, $userid, $game_name, $num_moves)){
						$errors[]="Storing gamestats was not successful.";
					}
				}

				if(empty($errors)){
					$_SESSION['highscores'][$game_name]=$_SESSION['Sudoku']->getNumMoves();
					$_SESSION['sudokuIsStored'] = True;
				}
			}

			$view=isNavbarPressed($oview);
			if($view != $oview){
				break;
			}

			// check if submit or not
			if(empty($_REQUEST['submit']))break;

			if ($_REQUEST['submit']=="Restart Sudoku"){
				// perform operation, switching state and view if necessary
				$_SESSION["Sudoku"]=new Sudoku();
				$_SESSION['state']='sudoku_puzzle';
				$view="sudoku_puzzle.php";
			}
			break;

		case "user_profile":
			$oview="user_profile.php";
			$view=isNavbarPressed($oview);
			if($view != $oview){
				break;
			}

			// check if submit or not
			if(empty($_REQUEST['submit'])||$_REQUEST['submit']!="Edit Profile"){
				break;
			}

			if($_REQUEST['submit']=="Edit Profile"){
				$_SESSION['state']="edit_profile";
				$view="user_profile.php";
				break;
			}
			break;

		case "edit_profile":
			$oview="user_profile.php";
			$view=isNavbarPressed($oview);
			if($view != $oview){
				break;
			}

			if(!$dbconn){
				$errors[]="Can't connect to db";
				break;
			}

			// check if submit or not
			if(empty($_REQUEST['submit'])||($_REQUEST['submit']!="Save changes" && $_REQUEST['submit']!="Cancel")){
				break;
			}

			if($_REQUEST['submit']=="Cancel"){
				$_SESSION['state']="user_profile";
				$view="user_profile.php";
				break;
			}

			// validate and set errors
			if(empty($_REQUEST['password'])){$errors[]="password is required to confirm these changes"; break;}
			if(!$_SESSION['user']->confirm_password($_REQUEST['password']))$errors[]="password is incorrect";
			if(!empty($errors))break;

			if(!isset($_REQUEST['gender']))$_REQUEST['gender']="";
			if($_REQUEST['age']==0)$_REQUEST['age']=null; // Age not set
			$info=array(
				"fname"=>$_REQUEST['fname'],
				"lname"=>$_REQUEST['lname'],
				"gender"=>$_REQUEST['gender'],
				"age"=>$_REQUEST['age'],
				"bio"=>$_REQUEST['bio']);

			if($_SESSION['user']->update_profile($dbconn, $info)){
				$_SESSION['user']->setFirstName($info['fname']);
		        $_SESSION['user']->setLastName($info['lname']);
		        $_SESSION['user']->setGender($info['gender']);
		        $_SESSION['user']->setAge($info['age']);
		        $_SESSION['user']->setBio($info['bio']);
		        $_SESSION['saved_msg']="Successfully saved changes";
		        $_SESSION['state']="user_profile";
				$view="user_profile.php";
			} else {
				$errors[]="Could not save changes made. Please try again.";
			}
			break;

	}

	require_once "view/$view";
?>
