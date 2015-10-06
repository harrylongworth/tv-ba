// New name for the state
var playState = {
  // Removed the preload function
  create: function() {
      
      // DRAW SCREEN:
    
    game.stage.backgroundColor = back_colour;
    // this.cursor = game.input.keyboard.createCursorKeys();
    
    // Transparent overlay buttons:
    leftbutton = game.add.sprite(0,0, 'button');
    rightbutton = game.add.sprite(w/2,0, 'button'); 
    leftbutton.alpha = 0;
    rightbutton.alpha = 0;
    
    // Main Image
    //image = game.add.sprite(w/2, h*0.05, '1');
    image = game.add.sprite(w, h, '1');
    image.anchor.setTo(0.5, 0);
    
    // Caption text and page number:
    text = game.add.text(w/2, (h-h*0.3), '', { fill: caption_colour });
    text.text = caption[0];
    text.anchor.setTo(0.5, 0);    

    page_no = game.add.text((w*0.9), (h-h*0.2), '', { fill: caption_colour });
    page_no.anchor.setTo(0.5, 0);
    page_no.text = '1';
    
    layout_screen ()
    
    
    //  Enables all kind of input actions on this image (click, etc)
    // image.inputEnabled = true;
    leftbutton.inputEnabled = true;
    rightbutton.inputEnabled = true;

    leftbutton.events.onInputDown.add(listener_left, this);
    rightbutton.events.onInputDown.add(listener_right, this);
    
    //	Here we set-up our audio sprite
	fx = game.add.audio('audio');
    fx.allowMultiple = true;

	//	And this defines the markers.

	//	They consist of a key (for replaying), the time the sound starts and the duration, both given in seconds.
	//	You can also set the volume and loop state, although we don't use them in this example (see the docs)

    var pointer=0;
    
    for(var i=0;i<pages;i++) { 
        fx.addMarker(i,pointer,audio_duration[i]);
        pointer=pointer+audio_duration[i];
    }
    
    
    
    // Enable Keyboard input
    this.leftKey = game.input.keyboard.addKey(Phaser.Keyboard.LEFT);
	this.rightKey = game.input.keyboard.addKey(Phaser.Keyboard.RIGHT);
    this.spaceKey = game.input.keyboard.addKey(Phaser.Keyboard.SPACEBAR);

    //  Stop the following keys from propagating up to the browser
    game.input.keyboard.addKeyCapture([ Phaser.Keyboard.LEFT, Phaser.Keyboard.RIGHT, Phaser.Keyboard.SPACEBAR ]);

      // detect sound finishes and go to next page
    // fx.onStop.add(listener_right, this);
      
      fx.play(0);
},// END Create

update: function() {
        // Catch keyboard input - debounce timer in each function prevents multiple key press.
    
    if (this.leftKey.isDown)
    {
        listener_left ();
    } 

    if (this.rightKey.isDown)
    {
        listener_right ();
    }
    
        if (this.spaceKey.isDown)
    {
        goto_start ();
    }

	
}, // END update

};
// Removed Phaser and states initialisation




