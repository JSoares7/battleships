<?php 
/**
* Project: Battleships
* Author: Joao Soares
* Version: 1.0
*/

class GameController {
	private $model;
	
	public function __construct(Game $model) {
		$this->model = $model;
	}
	
	public function convertToPosition($coordinate) {
		if(preg_match("/[A-Za-z]+[0-9]{1,2}/",$coordinate)) {
			$height = $this->model->getBoardHeight();
			$width = $this->model->getBoardWidth();
			$letter = substr($coordinate, 0, 1);
			$numbers = substr($coordinate, 1);
			
			$range = range('A', 'Z');
			$letter_found = -1;
			for($i = 0; $i < $height; $i++) {
				if ($range[$i] == $letter)
					$letter_found = $i;
			}
			if ($letter_found == -1)
				return null;
			$letter_found = $letter_found > 0 ? $letter_found * 10 : 0;
			$numbers_found = -1;
			for($i = 1; $i <= $width; $i++) {
				if ($numbers == $i)
					$numbers_found = $i;
			}
			if ($numbers_found == -1)
				return null;
				
			$position = ($letter_found + $numbers_found) - 1;
			return $position;
		} else {
			return null;
		}
	}
	
	public function nextMove($position) {
		$height = $this->model->getBoardHeight();
		$width = $this->model->getBoardWidth();
		if ($position >= 0 && $position <= ($height * $width)) {
			$this->authoriseMove($position);	
		} else {
			return false;
		}	
	}
	
	public function returnGame() {
		return $this->model;
	}
	
	public function setMessage($message) {
		$this->model->setMessage($message);
	}
	
	private function authoriseMove($position) {
		return $this->model->playMove($position);
	}
}