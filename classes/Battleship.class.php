<?php 
/**
* Project: Battleships
* Author: Joao Soares
* Version: 1.0
*/

class Battleship extends Vessel {

	const SPACES = 5;
	const NAME = "Battleship";
	
	public function __construct($spaces = 0, $name = '') {
		parent::__construct(self::SPACES,self::NAME);
	}
}
?>