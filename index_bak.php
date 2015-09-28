<!DOCTYPE html>
<html>
<?php

// TO DO:
// multiple audio files, 1 per page, rather than use a duration array
// if not using default language then use lang as folder name inside audio folder
// Text To Speach rather than audio files (and lang to set language of TTS)
// Highlight each word as read

// Navigation interface so can use as TV payload
// = get folder to read from url param - i.e. content folder from root URL
 
// DONE:
// prevent double clicking with a timer [DONE]
// stop last sound when new sound starts [DONE]
// detect screen rotation and reload [DONE!]

 $location=explode("/", __FILE__);
 // print_r($location);
 $folderName= $location[count($location)-2];
 // $folderName= substr($filename ,0,-4);
 $folder="./";
 
?> 
<head>
<meta charset="utf-8" />
<title> Book Aloud - <?php echo $folderName  ?></title>
<style type="text/css">

* {
margin: 0;
padding: 0;
}
.hiddenText {
font-family: Geo;
visibility: hidden;
}
</style>
<meta name="viewport" content="width=device-width initial-scale=1 maximum-scale=1
user-scalable=0 minimal-ui" />
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="HandheldFriendly" content="true" />

<script type="text/javascript" src="js/phaser.min.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>

<?php
 // Build images from images folder
 
     /*
 $temp ="";
 $idtext=0;
 
 $dir = new RecursiveDirectoryIterator( $folder );
 foreach(new RecursiveIteratorIterator($dir) as $file) {
 		 $filename= $file->getFilename();  
    if ($file->Isfile() && (substr( $filename,-4) != ".php") && (substr( $filename ,0,1) != ".")&& (substr($filename,0,-4)!="icon")) {
      $temp = $temp. "<img width='220px' src='" . $filename ."' alt='". substr( $filename ,0,strlen($filename)-4). "' id='". $idtext."' >" ;
      $idtext++;
      }
    }
 echo $temp;
 */
?>
    
<script>
        
        // from http://www.html5gamedevs.com/topic/1380-how-to-scale-entire-game-up/
        // get dimensions of the window considering retina displays
       // var w = window.innerWidth * window.devicePixelRatio;
       // var h = window.innerHeight * window.devicePixelRatio;
    
    var w = $(window).width();
    var h = $(window).height();
    var audio_duration = [];
    

       var game = new Phaser.Game(w, h, Phaser.AUTO, 'gameDiv', { preload: preload, create: create,update: update });

    
    window.addEventListener("resize", function() {
        location.reload();
        // game.time.events.add(Phaser.Timer.SECOND * 1.5, layout_screen, this);      
        
}, false);
    
    
    /*
    window.addEventListener("orientationchange", function() {
    // Announce the new orientation number
        
 layout_screen ();
        
}, false);
    */
        
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

function now_okay() {
    too_soon = 0;
}
    
function preload () {

    // go to full screen
	// screenfull.request();
    
    //  You can fill the preloader with as many assets as your game requires

    //  Here we are loading an image. The first parameter is the unique
    //  string by which we'll identify the image later in our code.

    //  The second parameter is the URL of the image (relative)
    
    <?php
     
        $lang = 'default';
        $currentfolder = 'books/book-jpg/';
        $images_folder = $currentfolder.'images/';
        $audio_folder = $currentfolder.'audio/';
        $story_folder = $currentfolder.'story/';
        
        $audio_ext = ".mp3";

        $story_loc = $story_folder.$lang.'.php';
        include $story_loc;
        // defines images array
    
        $numImages=count($story);

        $temp="var numImages=".$numImages.";";
        $temp=$temp."pages=".$numImages.";";
        $temp=$temp."audio_loc='".$audio_folder.$lang.$audio_ext."';";
        $temp=$temp."back_colour='".$background_colour."';";
        $temp=$temp."caption_colour='".$caption_colour."';";
        $temp2="";
        $temp3="";

        for ($i = 1; $i < $numImages+1; $i++) {
            $temp= $temp."game.load.image('".$i."', '".$images_folder.$i.$image_ext."');";
            $i_minus = $i-1;
            $temp2=$temp2."audio_duration[".$i_minus."]=".$duration[$i_minus].";";
            $temp3=$temp3."caption[".$i_minus."]='".$story[$i_minus]."';";
            
        
        }

        echo $temp;
        echo $temp2;
        echo $temp3;

        
    ?>
      
    game.load.image('button','assets/button.svg');
    game.load.audio('audio', audio_loc);

}

function create() {
    
    game.stage.backgroundColor = back_colour;
    // this.cursor = game.input.keyboard.createCursorKeys();
    
    leftbutton = game.add.sprite(0,0, 'button');
    rightbutton = game.add.sprite(w/2,0, 'button');
    image = game.add.sprite(w/2, h*0.05, '1');
    
    leftbutton.alpha = 0;
    rightbutton.alpha = 0;
    
    //  Moves the image anchor to the middle, so it centers inside the game properly
    // image.anchor.set(0,0.5);
    // image.scale.width(100%);
    image.anchor.setTo(0.5, 0);
    
    text = game.add.text(w/2, (h-h*0.25), '', { fill: caption_colour });
    page_no = game.add.text((w*0.9), (h-h*0.15), '', { fill: caption_colour });
    
    text.text = caption[0];
    text.anchor.setTo(0.5, 0);    
     
    page_no.anchor.setTo(0.5, 0);
    page_no.text = '1';
    
    layout_screen ()
    
    
    //  Enables all kind of input actions on this image (click, etc)
    // image.inputEnabled = true;
    leftbutton.inputEnabled = true;
    rightbutton.inputEnabled = true;



    // text.width=w*0.8;
    
    
    
    leftbutton.events.onInputDown.add(listener_left, this);
    rightbutton.events.onInputDown.add(listener_right, this);
    
    // image.events.onInputDown.add(listener, this);
    
    // AUDIO
    
    //	Here we set-up our audio sprite
	fx = game.add.audio('audio');
    fx.allowMultiple = true;

	//	And this defines the markers.

	//	They consist of a key (for replaying), the time the sound starts and the duration, both given in seconds.
	//	You can also set the volume and loop state, although we don't use them in this example (see the docs)

    // var duration=2;
    var pointer=0;
    
    for(var i=0;i<pages;i++) { 
        fx.addMarker(i,pointer,audio_duration[i]);
        pointer=pointer+audio_duration[i];
    }
    
    fx.play(0);
    
    // Enable Keyboard input
    this.leftKey = game.input.keyboard.addKey(Phaser.Keyboard.LEFT);
	this.rightKey = game.input.keyboard.addKey(Phaser.Keyboard.RIGHT);
    this.spaceKey = game.input.keyboard.addKey(Phaser.Keyboard.SPACEBAR);

    //  Stop the following keys from propagating up to the browser
    game.input.keyboard.addKeyCapture([ Phaser.Keyboard.LEFT, Phaser.Keyboard.RIGHT, Phaser.Keyboard.SPACEBAR ]);

}
    
    
function update() {
    
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
	
    
}  // END Update

function layout_screen () {
    
    w = $(window).width();
    h = $(window).height();
    
    game.width=w;
    game.height=h;
    // game.stage.bounds.width = w;
    // game.stage.bounds.height = h;
    
    if (game.renderType === Phaser.WEBGL)
{
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
    text.y=(h-h*0.25);
    
    page_no.x = (w*0.9);
    page_no.y = (h-h*0.15);
    

    
    
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
    
    
   
    // text.text = pages + " You clicked this " + counter + " times!" + audio_duration[counter-1];
    
    
    // go to full screen
	// screenfull.request();
    
    if (counter >= pages-2) { counter = 0; }
        
  } // END too_soon
    
        
    

}
    
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
    
    
   
    // text.text = pages + " You clicked this " + counter + " times!" + audio_duration[counter-1];
    
    
    // go to full screen
	// screenfull.request();
    
    if (counter >= pages-1) { counter = -1; }
  } // END Too Soon

}
    
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
    
    
    </script>
      	
</head>


<body>
<div id="gameDiv"> </div>
<p class="hiddenText"> . </p>
</body>
</html>