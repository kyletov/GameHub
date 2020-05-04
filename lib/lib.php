<?php
	require_once "dbconnect_string.php";
    function db_connect(){
        global $g_dbconnect_string;
        $dbconn = pg_connect($g_dbconnect_string);
        if(!$dbconn){
    		$system_errors[] = "Can't connect to the database.";
    		return null;
    	} else return $dbconn;
    }

    // return the errors in a standard format
    function view_errors($e){
        $s="";
        foreach($e as $key=>$value){
            $s .= "<br/> $value";
        }
        return $s;
    }

    function isNavbarPressed($same_view){
        $view = $same_view;
        if(!empty($_REQUEST['view'])){
            if($_REQUEST['view'] == "game_stats"){
                $_SESSION['state']='game_stats';
                $view="game_stats.php";
            } else if($_REQUEST['view'] == "guess_game"){
                if(!isset($_SESSION['GuessGame'])){
                    $_SESSION['GuessGame']=new GuessGame();
                }
                $_SESSION['state']='guess_game';
                if($_SESSION['GuessGame']->getState()=="correct"){
                    $_SESSION['state']='won_guess_game';
                }
                $view="guess_game.php";
            } else if($_REQUEST['view'] == "the_15_puzzle"){
                if(!isset($_SESSION['The15Puzzle'])){
                    $_SESSION['The15Puzzle']=new The15Puzzle(range(0,15));
                }
                $_SESSION['state']='the_15_puzzle';
                if($_SESSION['The15Puzzle']->isSolved()){
                    $_SESSION['state']='won_the_15_puzzle';
                }
                $view="the_15_puzzle.php";
            } else if($_REQUEST['view'] == "the_frog_puzzle"){
                if(!isset($_SESSION['TheFrogPuzzle'])){
                    $_SESSION['TheFrogPuzzle']=new TheFrogPuzzle();
                }
                $_SESSION['state']='the_frog_puzzle';
                if($_SESSION['TheFrogPuzzle']->isSolved()){
                    $_SESSION['state']='won_the_frog_puzzle';
                }
                $view="the_frog_puzzle.php";
            } else if($_REQUEST['view'] == "sudoku_puzzle"){
                if(!isset($_SESSION['Sudoku'])){
                    $_SESSION['Sudoku']=new Sudoku();
                }
                $_SESSION['state']='sudoku_puzzle';
                if($_SESSION['Sudoku']->isSolved()){
                    $_SESSION['state']='won_sudoku_puzzle';
                }
                $view="sudoku_puzzle.php";
            } else if($_REQUEST['view'] == "user_profile"){
                $_SESSION['state']='user_profile';
                $view="user_profile.php";
            } else if($_REQUEST['view'] == "login"){
                session_destroy();
                $view="login.php";
            }
        }
        return $view;
    }


?>
