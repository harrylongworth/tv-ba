// Initialise Phaser


var w = $(window).width();
var h = $(window).height();
var game = new Phaser.Game(w, h, Phaser.AUTO, 'gameDiv');

window.addEventListener("resize", function() { location.reload(); }, false);
// The above listener reloads the page on screen rotate to get Phaser to reset everything properly for the new orientation.

// Initialise Variables
var audio_duration = [];    
var text;
var caption = [];
var page_no;
var counter = 0;
var pages = 0;
var image;
var audio_loc;
var rightbutton;
var leftbutton;
var back_colour = '#3498db';
var caption_colour = '#fff';
var current_audio = 0;
var too_soon = 0;
var main_audio;

function now_okay() {
    too_soon = 0;
}



    function layout_screen () {
    
    w = $(window).width();
    h = $(window).height();
    
    game.width=w;
    game.height=h;
    // game.stage.bounds.width = w;
    // game.stage.bounds.height = h;
    
    if (game.renderType === Phaser.WEBGL) {
	   game.renderer.resize(w, h);
    }
    
    var imageW;
    var imageH;
    
    if (h>w) {       
      imageW=w*0.8;
      imageH = (imageW*0.7);
    } else {
      imageH = h*0.7;
      imageW= imageH*1.333;
    }
    
    image.width=imageW;
    image.height=imageH;
    image.x=w/2;
    image.y=h*0.05;
    
    leftbutton.width= w/2;
    leftbutton.height=h;
    
    rightbutton.width= w/2;
    rightbutton.height=h;
    rightbutton.x = w/2;
    
    text.x = w/2;
    text.y=(h-h*0.3);
    
    page_no.x = (w*0.9);
    page_no.y = (h-h*0.2);   
    
} // END layout_screen
    
function listener_left () {

  if (too_soon==0) {
    game.time.events.add(Phaser.Timer.SECOND * 1.5, now_okay, this);
    too_soon=1;
    counter--;
    if (counter<0) { counter = 0 };
    fx.stop(current_audio);
    fx.play(counter);
    current_audio = counter;
    text.text = caption[counter];
    page_no.text = counter+1;
    image.loadTexture(counter+1);
    image.anchor.setTo(0.5, 0);
    
    var imageW;
    var imageH;
    
    if (h>w) {       
      imageW=w*0.8;
      imageH = (imageW*0.7);
    } else {
      imageH = h*0.7;
      imageW= imageH*1.333;
    }
    
    image.width=imageW;
    image.height=imageH;
    
    if (counter >= pages-2) { counter = 0; }
        
  } // END too_soon
        
} // END listener_left
    
function listener_right () {

  if (too_soon==0) {
    game.time.events.add(Phaser.Timer.SECOND * 1.5, now_okay, this);
    too_soon=1;
    counter++;
    fx.stop(current_audio);
    fx.play(counter);
    current_audio = counter;
    text.text = caption[counter];
    page_no.text = counter+1;
        
    image.loadTexture(counter+1);
    image.anchor.setTo(0.5, 0);
    
    var imageW;
    var imageH;
    
    if (h>w) {       
      imageW=w*0.8;
      imageH = (imageW*0.7);
    } else {
      imageH = h*0.7;
      imageW= imageH*1.333;
    }
    
    image.width=imageW;
    image.height=imageH;
    
    if (counter >= pages-1) { counter = -1; }
  } // END Too Soon

} // END listener_right
    
function goto_start () {

  if (too_soon==0) {
    game.time.events.add(Phaser.Timer.SECOND * 1.5, now_okay, this);
    too_soon=1;
    counter=0;
    fx.stop(current_audio);
    fx.play(counter);
    current_audio = counter;
    text.text = caption[counter];
    page_no.text = counter+1;
    image.loadTexture(counter+1);
    image.anchor.setTo(0.5, 0);
    
    var imageW;
    var imageH;
    
    if (h>w) {       
      imageW=w*0.8;
      imageH = (imageW*0.7);
    } else {
      imageH = h*0.7;
      imageW= imageH*1.333;
    }
    
    image.width=imageW;
    image.height=imageH;
    
        
  } // END too_soon
    
} // END goto_start

// Add all the states
game.state.add('boot', bootState);
game.state.add('load', loadState);
game.state.add('play', playState);
// Start the 'boot' state
// game.state.start('boot');
