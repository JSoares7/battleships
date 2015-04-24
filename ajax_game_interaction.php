<?php 
require_once realpath( dirname(__FILE__) ) . '/loader.php';

if ( isset($_POST) && !empty($_POST) && !empty($_POST['nextCommand']) && !empty($_POST['game']) ) {
	$game  = unserialize(base64_decode($_POST['game']));
	$coord = strtoupper($_POST['nextCommand']);
	
	$controller = new GameController($game);
	$controller->setMessage('');
	$position = $controller->convertToPosition($coord);
	if ($position !== NULL) {
		$controller->nextMove($position);
	} else {
		if (strtoupper($coord) == 'SHOW') {} else {
			$controller->setMessage('*** INVALID PLAY ***');
		}
	}
	$view = new GameHandler($game);
	
	if (strtoupper($coord) == 'SHOW') {
		$result = array(
			'game'=>$view->displaySolution().$view->output(),
			'serialiazed'=>base64_encode(serialize($controller->returnGame()))
		);
	} else {
		$result = array(
			'game'=>$view->output(),
			'serialiazed'=>base64_encode(serialize($controller->returnGame()))
		);
	}
	
	echo json_encode($result);
	die;
}
?>