<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8">
		<title>Battleship Game - By Joao Soares</title>
		<style type="text/css">
			body {font:100 14px/16px monospace,Georgia,Helvetica,arial;}
			.wrapper {margin:40px auto;width:455px}
			h1 {font-size:24px;text-align:center;margin-bottom:30px}
			.footer {margin:20px 0;font-size:12px;text-align:center;}
			
			.clear {clear:both;}
			#error_message {color:#ff0000;}
			#success {color:#00aa00;}
		</style>
		<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
	</head>
	<body>
		<div class="wrapper">
			<h1>Battleship Game</h1>
			<?php
				$project_dir = realpath( dirname(__FILE__) );
				require_once $project_dir . '/loader.php';
				$game = new Game();
				$view = new GameHandler($game);
				
				$view->startGame();				
				$serialiazed_game = base64_encode(serialize($game));
				
				/* Uncomment this if you want to see where the vessels are. YOU CHEATER! */
				//echo $view->displaySolution();
			?>
			<div class="battleships-wrapper">
				<?php echo $view->output(false); ?>
			</div>
			<form name="input" action="" method="post" id="battleships_form">
				<input type="hidden" id="game_serialiazed" name="game_serialiazed" value="<?php echo $serialiazed_game; ?>" />				
				Enter coordinates (row, col), e.g. A5 <input type="input" size="3" style="width: 60px" name="coord" id="coord" autofocus>
				<input type="submit" />
			</form>
			<div class="footer">Joao Soares &copy; <?php echo date("Y"); ?></div>
		</div>
		
		<script type="text/javascript">
			$(function() {
				$("#battleships_form").submit(function( event ) {
				  	event.preventDefault();
				  	$("#invalid_play").html();
				  	if ($("#coord").val() == "") {
						$("#invalid_play").html("Invalid play!");	  	
				  	} else {
				  		$.post( "ajax_game_interaction.php", {game:$("#game_serialiazed").val(), nextCommand:$("#coord").val()}, function( result ) {
				  			data = $.parseJSON(result);
							$( ".battleships-wrapper" ).html( data.game );
							$("#game_serialiazed").val( data.serialiazed );
						});
				  	}
				  	return false;
				});
			});
		</script>
	</body>
</html>