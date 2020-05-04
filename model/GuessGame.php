<?php

class GuessGame {
	public $secretNumber = 5;
	public $numMoves = 0;
	public $history = array();
	public $state = "";

	public function __construct() {
        	$this->secretNumber = rand(1,100);
    	}
	
	public function makeGuess($guess){
		$this->numMoves++;
		if($guess>$this->secretNumber){
			$this->state="too high";
		} else if($guess<$this->secretNumber){
			$this->state="too low";
		} else {
			$this->state="correct";
		}
		$this->history[] = "Guess #$this->numMoves was $guess and it was $this->state.";
	}

	public function getState(){
		return $this->state;
	}

	public function getNumMoves(){
		return $this->numMoves;
	}


}
?>
