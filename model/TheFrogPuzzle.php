<?php

class TheFrogPuzzle {
	public $numMoves = 0;
	public $history = array();
	public $state = array(1,2,3,0,-3,-2,-1);
	public $solution = array(-3,-2,-1,0,1,2,3);

	public function __construct() {
    	$this->empty_position = 3;
	}
	
	public function makeMove($move, $clicked_position){
		$this->numMoves++;
		$this->state[$this->empty_position] = $move;
		$this->state[$clicked_position] = 0;
		$this->empty_position = $clicked_position;
		if($move > 0){
			$this->history[] = "Move #$this->numMoves: A yellow frog jumped to the right.";
		} else {
			$this->history[] = "Move #$this->numMoves: A green frog jumped to the left.";
		}
	}

	public function isMovePossible($move, $clicked_position){
		if ($move > 0){
			$possible_moves = array($this->empty_position-1, $this->empty_position-2);
		} else {
			$possible_moves = array($this->empty_position+1, $this->empty_position+2);
		}
		return in_array($clicked_position, $possible_moves);
	}

	public function noMovesLeft(){
		$possible_moves = array(-1, -2, 1, 2);
		$moves_left = 4;
		foreach($possible_moves as $adjustment){
			$position = $this->empty_position + $adjustment;
			if (0 <= $position && $position < count($this->state)){
				// Yellow frog but is on the right side of empty spot
				if ($this->state[$position] > 0 && $this->empty_position < $position){
					$moves_left--;
				}
				// Green frog but is on the left side of empty spot
				else if ($this->state[$position] < 0 && $this->empty_position > $position){
					$moves_left--;
				}
			} else { // Invalid position index
				$moves_left--;
			}
		}
		return ($moves_left == 0);
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
