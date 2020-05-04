<?php
class Sudoku {
	public $numMoves = 0;
	public $state = array();
	public $size = 9;
	public $eraseMode=False;
	public $start = array(array(),array());
	public $duplicateSpots = array(array(),array(),array(),array(),array(),array(),array(),array(),array());
	public function __construct() {
		$this->initializeState();
		for($i=0;$i<$this->size;$i++){
			$this->insertNum($i);
		}
		for($i=0;$i<$this->size;$i++){
			$this->insertNum($i);
		}
		for($i=0;$i<rand(0,5);$i++){
			$this->insertNum(-1);
		}
    }
	public function insertNum($num){
		if($num==-1){
			$numb=rand(0, $this->size-1);
		}else{
			$numb = $num;
		}
		$cell=rand(0,($this->size*$this->size)-1);
		while(in_array($cell,$this->duplicateSpots[$numb])||in_array($cell,$this->start[0])){
			$cell=rand(0,($this->size*$this->size)-1);
		}
		$this->duplicateSpots[$numb]=array_merge($this->duplicateSpots[$numb],$this->generateNoMoves($cell));
		$coordinates=$this->getCoordinates($cell);
		$this->start[0][]=$cell;
		$this->start[1][]=$numb+1;
		$this->state[$coordinates[0]][$coordinates[1]]= $numb+1;

	}
	public function getState(){
		return $this->state;
	}

	public function getNumMoves(){
		return $this->numMoves;
	}
	public function isEraseMode(){
		return $this->eraseMode;
	}
	public function changeMode(){
		$this->eraseMode=!$this->eraseMode;
	}
	public function makeMove($clicked_position){
		$this->numMoves++;
		$coordinates=$this->getCoordinates($clicked_position);
		$temp = $this->state[$coordinates[0]][$coordinates[1]];
		$temp = ($temp+1)%($this->size+1);
		if ($temp==0){
			$temp=1;
		}
		$this->state[$coordinates[0]][$coordinates[1]]=$temp;
	}
	public function isMovePossible($clicked_position){
		for($i=0;$i<count($this->start[0]);$i++){
			if( in_array($clicked_position,$this->start[0])){
				return false;
			}
		}
		return true;
	}
	public function getCoordinates($position){
		$temp = array();
		$temp[]=intdiv($position ,$this->size);
		$temp[]=$position %$this->size;
		return $temp;
	}
	public function initializeState(){
		for($i=0;$i<$this->size;$i++){
			$this->state[$i]=array();
			for($j=0;$j<$this->size;$j++){
				$this->state[$i][$j]=0;
			}
		}
	}
	public function reset(){
	    $this->initializeState();
		for($i=0;$i<count($this->start[0]);$i++){
			$coordinates=$this->getCoordinates($this->start[0][$i]);
			$this->state[$coordinates[0]][$coordinates[1]]= $this->start[1][$i];
		}
	}
	public function isSolved(){
		for($i=0;$i<$this->size;$i++){
				if(in_array(0,$this->state[$i]))return false;
		}
		for($i=0;$i<$this->size;$i++){
				if(!$this->noDupes($this->state[$i]))return false;
		}
		for($i=0;$i<$this->size;$i++){
			$temp=array();
			for($j=0;$j<$this->size;$j++){
				$temp[]=$this->state[$j][$i];
			}
			if(!$this->noDupes($temp))return false;
		}
		for($i=0;$i<$this->size;$i+=sqrt($this->size)){
			for($j=0;$j<$this->size;$j+=sqrt($this->size)){
				$temp=array();
				for($l=$i;$l<$i+sqrt($this->size);$l++){
					for($k=$j;$k<$j+sqrt($this->size);$k++){
						$temp[]=$this->state[$l][$k];
					}
				}
				if(!$this->noDupes($temp))return false;
			}
		}
		return true;
	}
	public function noDupes($check){
		for($i=0;$i<count($check);$i++){
			for($j=$i+1;$j<count($check);$j++){
				if($check[$i]==$check[$j])return false;
			}
		}
		return true;

	}
	public function clearCell($position){
		$coordinates= $this->getCoordinates($position);
		$this->state[$coordinates[0]][$coordinates[1]]=0;

	}
	public function generateNoMoves($position)
	{
		$coordinates= $this->getCoordinates($position);
		$temp=array();
		//Generates all the possible places that a duplicate will happen
		for($i=$coordinates[0]*$this->size;$i<($coordinates[0]*$this->size)+$this->size;$i++){
			$temp[]=$i;
		}
		for($i=$coordinates[1];$i<($this->size*$this->size)-1;$i+=$this->size){
			$temp[]=$i;
		}
		$start=array();
		for($i=0;$i<2;$i++){
			if($coordinates[$i]>=6){
				$start[$i]=6;
			}else if($coordinates[$i]>=3){
				$start[$i]=3;
			}else{
				$start[$i]=0;
			}
		}
		for($i=$start[0];$i<$start[0]+3;$i++){
			for($j=$start[1];$j<$start[1]+3;$j++){
				$temp[]= ($i*$this->size)+$j;
			}
		}
		return $temp;
	}
}
?>
