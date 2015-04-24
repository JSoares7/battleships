<?php 
/**
* Project: Battleships
* Author: Joao Soares
* Version: 1.0
*/

class Game {

	//Gaming pattern
	const MISS = "-";
	const HIT = "X";	
	const DEF = ".";
	
	//Fleet
	const BATTLESHIP = "Battleship";
	const DESTROYER = "Destroyer";

	//Game Dimensions
	const WIDTH = 10;
	const HEIGHT = 10;
	
	private $fleet = array();
	private $board = array();
	private $positions = array();
	private $message = '';
	private $nr_plays = 0;
	private $game_completed = 0;
	private $game_over = false;
	private $vessels = array();
	
	public function __construct() {
		$this->init();
	}
	
	private function getVesselStartingPosition() {
		$anchorpoint = rand(1,self::WIDTH * self::HEIGHT);
		return $anchorpoint;
	}
	
	private function getVesselOrientation() {
		$direction = (rand() % 2 == 0 ? "horizontal" : "vertical");
		return $direction;
	}
	
	private function init() {
		for($i = 0; $i < self::HEIGHT * self::WIDTH; $i++) {
			$this->board[$i] = self::DEF;
			$this->positions = '';
		}
	}
	
	public function renderBoard($default = true) {
		$result = '';
		if ($default == false) {
			$result .=  "<pre>  1 2 3 4 5 6 7 8 9 0<br/>";
			$range = range('A', 'Z');
			for($i = 0; $i < self::HEIGHT; $i++) {
				$result .=  $range[$i]." ";
				for($j = 1; $j <= self::WIDTH; $j++) {
					$result .=  ". ";
				}
				$result .=  "<br/>";
			}
			$result .=  "</pre>";
		} else {
			$game_over = $this->isGameOver();
			if ($game_over)
				$result .=  '<span id="report">*** SUNK ***</span>';
			else
				$result .=  '<span id="report">'.$this->message.'</span>';
			$result .= '<div class="clear"></div>';
			$result .= "<pre>  1 2 3 4 5 6 7 8 9 0";
			$range = range('A', 'Z');

			for($i = 0; $i < self::HEIGHT * self::WIDTH; $i++) {
				if ($i % self::WIDTH == 0) {$result .= '<br/>'.$range[$i/self::HEIGHT].' ';}
				$result .= $this->board[$i].' ';
			}
			$result .=  "</pre>";
			if ($game_over)
				$result .=  '<span id="success">Well done! You completed the game in '.$this->getNumberPlays().' shots</span><br/><br/>';
		}
		return $result;
	}
	
	public function deployFleet() {
	    $distribution = self::WIDTH * self::HEIGHT;
	    for($i = 0;$i < $distribution;$i++)
	        $this->fleet[$i] = " ";
	    
	    $vessels = array(self::BATTLESHIP,self::DESTROYER,self::DESTROYER);
	    $index = 0;
	    
	    foreach ($vessels as $vessel) {
	        $vessel_orientation = $this->getVesselOrientation();
	        $vessel_obj = new $vessel;
	        $vessel_length = $vessel_obj->getSpaces();
	        
	        $this->game_completed += $vessel_length;
	        $anchored = false;
	
	        while ($anchored == false) {
	            $vessel_starting_position = $this->getVesselStartingPosition();
	            $increment = ($vessel_orientation == "vertical" ? self::WIDTH : 1);
	            $collisions = 0;
	
	            for ($shipPoint = 0; $shipPoint < $vessel_length; $shipPoint++) {
	
	                if ($vessel_orientation == "horizontal") {
	                    if ((($vessel_starting_position % self::WIDTH) + $shipPoint) >= self::WIDTH) {
	                        $collisions++;
	                    }
	                } else if (($vessel_starting_position + ($increment * $shipPoint)) >= self::WIDTH*self::HEIGHT) {
	                 	$collisions++;
	                }
	
					if ($collisions == 0) {
		                if ($this->fleet[$vessel_starting_position + ($increment * $shipPoint)] != " ") {
		                    $collisions++;
		                }
					}
	            }
	            
	            if ($collisions == 0) {
	                for ($shipPoint = 0; $shipPoint < $vessel_length; $shipPoint++) {
	                    $this->fleet[$vessel_starting_position + ($increment * $shipPoint)] = "X";
	                    $this->vessels[$index][$vessel_starting_position + ($increment * $shipPoint)] = "";
	                }
	                $this->vessels[$index]['count'] = 0;
	                $anchored = true;
	            } else {
	                $anchored = false;
	            }
	        }
	        $index++;
        }
        /*echo "<pre>";
        print_r($this->vessels);
        echo "</pre>";*/
        return $this->fleet;
	}
	
	public function renderSolution() {
		$result = "<pre>";
		$distribution = self::WIDTH * self::HEIGHT;
	    for($i = 1;$i <= $distribution;$i++) {
			if ($this->fleet[$i-1] == " ") 
				$result .= self::DEF;
			else
				$result .= $this->fleet[$i-1];
			$result .= " ";
			if ($i % self::WIDTH == 0) $result .= "<br/>";
		}
		$result .= "</pre>";
		return $result;
	}
	
	public function setMessage($message) {
		$this->message = $message;
	}
	
	public function playMove($position) {
		$this->positions[] = $position;
		if ($this->board[$position] != self::DEF) {
			$this->setMessage('*** ALREADY PLAYED! ***');
		} else {
			if ($this->fleet[$position] == self::HIT) {
				$this->setMessage('*** HIT ***');
				$this->board[$position] = self::HIT;
				
				for ($i = 0; $i < count($this->vessels);$i++) {
					if (isset($this->vessels[$i][$position])) {
						$this->vessels[$i][$position] = 'X';
						$this->vessels[$i]['count'] += 1;
						if ((count($this->vessels[$i])-1) == $this->vessels[$i]['count']) {
							$this->setMessage('*** SUNK ***');
						}
					}
				}
				
			} else {
				$this->setMessage('*** MISS ***');
				$this->board[$position] = self::MISS;
			}
			$distribution = self::WIDTH * self::HEIGHT;
			$count = 0;
	    	for($i = 0;$i < $distribution;$i++) {
	    		if ($this->board[$i] == self::HIT && $this->fleet[$i] == self::HIT) {
	    			$count++;
	    		}
	    	}
	    	if ($count == $this->game_completed) {
	    		$this->gameOver();
	    	}
			$this->setNumberPlays(1);
		}
	}
	
	public function getNumberPlays() {
		return $this->nr_plays;
	}
	
	public function setNumberPlays($increment) {
		$this->nr_plays += $increment;
	}
	
	public function gameOver() {
		$this->game_over = true;
	}
	
	public function isGameOver() {
		return $this->game_over;
	}
	
	public function getBoardWidth() {
		return self::WIDTH;
	}
	
	public function getBoardHeight() {
		return self::HEIGHT;
	}
}
?>