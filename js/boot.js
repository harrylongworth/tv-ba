var bootState = {
	
	preload: function() {
		
		game.load.image('progressBar','assets/progressBar.png');
	},
	
	create: function() {
		
		game.stage.backgroundColor = '#3498db';
		document.body.style.backgroundColor = '#3498db';
				
		// start the load state
		game.state.start('load');
	}	
};