<?php

class The15Puzzle {
	public $numMoves = 0;
	public $history = array();
	public $state = array();
	public $start = array();
	public $solution = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,0);

	public function __construct($tiles) {
    	$this->state = $tiles;
    	shuffle($this->state);
		$this->start = $this->state;
	}

	public function makeMove($move, $empty_position, $clicked_position){
		$this->numMoves++;
		$this->state[$empty_position] = $move;
		$this->state[$clicked_position] = 0;
		$this->history[] = "Move #$this->numMoves was moving tile $move.";
	}

	public function reset(){
		$this->state = $this->start;
	}

	public function isMovePossible($empty_position, $clicked_position){
		$possible_moves = array($empty_position+4, $empty_position-4);
		// Catch wraps around each case
		if(in_array($clicked_position, array(3,7,11)) || in_array($clicked_position, array(4,8,12))){
			$possible_moves[] = in_array($clicked_position, array(3,7,11)) ? $empty_position+1 : $empty_position-1;
		} else {
			$possible_moves[] = $empty_position-1;
			$possible_moves[] = $empty_position+1;
		}
		return in_array($clicked_position, $possible_moves);
	}

	public function randomize(){
		shuffle($this->state);
		$this->start = $this->state;
	}

	public function getNumMoves(){
		return $this->numMoves;
	}

	public function getState(){
		return $this->state;
	}

	public function isSolved(){
		return $this->state == $this->solution;
	}


}
?>
