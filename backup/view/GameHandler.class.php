<?php 
/**
* Project: Battleships
* Author: Joao Soares
* Version: 1.0
*/

class GameHandler {
	private $model;
	
	public function __construct(Game $model) {
		$this->model = $model;
	}
	
	public function output($default = true) {
		return $this->model->renderBoard($default);
	}
	
	public function startGame() {
		return $this->model->deployFleet();
	}
	
	public function displaySolution() {
		return $this->model->renderSolution();
	}
}