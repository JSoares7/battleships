<?php 
/**
* Project: Battleships
* Author: Joao Soares
* Version: 1.0
*/

class Vessel {
	
	private $spaces;
	private $name;
	
	public function __construct($spaces = 0, $name = '') {
		$this->setSpaces($spaces);
		$this->setName($name);
	}
	
	public function setSpaces($spaces) {
		$this->spaces = $spaces;
	}
	
	public function getSpaces() {
		return $this->spaces;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
}
?>