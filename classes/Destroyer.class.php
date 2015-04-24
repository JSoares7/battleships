<?php 
/**
* Project: Battleships
* Author: Joao Soares
* Version: 1.0
*/

class Destroyer extends Vessel {

	const SPACES = 4;
	const NAME = "Destroyer";
	
	public function __construct($spaces = 0, $name = '') {
		parent::__construct(self::SPACES,self::NAME);
	}
}
?>