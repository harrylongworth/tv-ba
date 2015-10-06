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

// $sDestination ='../../data/constants.php';
// require_once $sDestination;
// Start output buffering
// ob_start();
// run code in x.php file
// ...
// saving captured output to file


$defaultNav="defaultnav.jpg";
$defaultNavJPG="nav.jpg";
$defaultNavPNG="nav.png";
$folderNav="nav.jpg";
$loopCount=0;
$baseFolder="books";//(EXTERNAL_TEXT == 1) ? EXTERNAL_FOLDER.'/content/movies' : 'content/movies';
$folderName="books";
$navHTML="";
$thumbsHTML="";
$movieHTML="";
$mainNavFlag=1;
// $scriptDir = ROOT_DIR;
// $fullPathPrefix = ROOT_DIR;

if (isset($_GET["folder"])&& !empty($_GET["folder"])) {
    $folderName=$folderName.DIRECTORY_SEPARATOR.$_GET["folder"].DIRECTORY_SEPARATOR;
    $mainNavFlag=0;
}
// echo "FolderName: ".$folderName;
  // $folderName = str_replace($scriptDir."/", '', $folderName);

?> 
<head>
<meta charset="utf-8" />
<title> Book Aloud - <?php echo $folderName  ?></title>
<link href="css/ba.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
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
    
<?php

if ($mainNavFlag==1) {
      
?>
    <head></head><body class="main">

	  <div id="menubar">
		  <div id="lefticon" onTouchStart="history.go(-1)" onClick="history.go(-1)"><i class="mainNav fa fa-arrow-circle-left fa-3x"></i></div>
	      <div id="moviecat"><?php echo $folderName; ?></div>
		  <div id="righticon" onTouchStart="goto_start ();" onClick="goto_start ();"><i class="mainNav fa fa-cog fa-3x"></i></div>
	  </div>
<hr>
        <?php
    
$rootFolder = preg_replace( '~(\w)$~' , '$1' . DIRECTORY_SEPARATOR , realpath( getcwd() ) );
$navCount=0;

// $booksDir = str_replace("payloads".DIRECTORY_SEPARATOR."OATSEA-tv-player", 'content', $rootFolder);
$booksDir = "books";

   $rootdir=$booksDir."/*";
   foreach(glob($rootdir, GLOB_ONLYDIR) as $dir) 

{ 
    $dir = basename($dir); 
       $displayTitle = $dir;
    $imgText = $flashDir."/".$dir."/icon.jpg";
    echo '<div class="myfig"><a href="JavaScript:this.location=',"'index.php?folder=", $dir,"'",'"><img width=100 height=150 alt="',$dir,'" src="defaultnav.png"></a><p class="imgtitle">',$displayTitle,'</p></div>'; 
}  


/*
$depth = 0;
$navdir = new RecursiveDirectoryIterator( $folderName,FilesystemIterator::SKIP_DOTS );
$tempitr = new RecursiveIteratorIterator($navdir,RecursiveIteratorIterator::SELF_FIRST);
$tempitr->setMaxDepth($depth);
    
foreach($tempitr as $file) {

	$filename= $file->getFilename();
	// $itemUrl = str_replace(ROOT_DIR, '', $file);
	$checkApple = strpos($itemUrl,".Apple");
	$currentFolder = str_replace($filename, '', $itemUrl);
	$title = $filename;
	$displayTitle = str_replace("-", ' ', $title);
	
	if (($file->isDir())&&(substr( $filename ,0,1) != ".")&&($checkApple===false)) {
			// If it's a directory build navigation for it
			
			$thisDir=$currentFolder.$filename;
			// $thisFullPath=$fullPathPrefix.$thisDir;
			$thisFullPath=$thisDir;
        
			// If there's no thumbnail for this directory use default one
			if (file_exists($thisFullPath."/".$defaultNavJPG)) {
				$thisFolderNav=$thisDir."/".$defaultNavJPG;
			} else if (file_exists($thisFullPath."/".$defaultNavPNG)) {
				$thisFolderNav=$thisDir."/".$defaultNavPNG;
			} else {
				$thisFolderNav=$defaultNav;
			}
			
			$catID="'".$title."'";

			echo '<div class="myfig"><img class="videoicon" id="'.$title.'_nav" alt="'.$title.'" src="'.$thisFolderNav.'" onTouchStart="alert('.$catID.')" onclick="alert('.$catID.')" /><p class="imgtitle">'.$displayTitle.'</p></div>
			';	

			$navCount++;
	} // END if
} // END foreach		
		
if ($navCount!=0) {echo "<hr>";}

*/
echo "<hr></div>";
// } // end "Movies" foldername check 
}  else {
        ?>
    <script type="text/javascript" src="js/phaser.min.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>

<script type="text/javascript" src="js/boot.js"></script>
    
<script>
    // load.js
var loadState = {
	
	preload: function () {
		
		// add a loading label
		var loadingLabel = game.add.text(game.world.centerX,150,'loading...', {font: '30px Arial',fill:'black'});
		loadingLabel.anchor.setTo(0.5,0.5);
		
		// Display the progress bar
		var progressBar = game.add.sprite(game.world.centerX, 200, 'progressBar');
		progressBar.anchor.setTo(0.5, 0.5);
		game.load.setPreloadSprite(progressBar);
        
        
     <?php 
        $lang = 'default';
        // $currentfolder = 'books/book-jpg/';
        $currentfolder = $folderName;
        $images_folder = $currentfolder.'images/';
        $audio_folder = $currentfolder.'audio/';
        $story_folder = $currentfolder.'story/';
        
        $audio_ext = ".mp3";

        $story_loc = $story_folder.$lang.'.php';
        include $story_loc;
        // defines images array
    
        // Initialise parameters from story.php
        $numImages=count($story);
        $temp="var numImages=".$numImages.";";
        $temp=$temp."pages=".$numImages.";";
        $temp=$temp."audio_loc='".$audio_folder.$lang.$audio_ext."';";
        $temp=$temp."back_colour='".$background_colour."';";
        $temp=$temp."caption_colour='".$caption_colour."';";
        $temp2="";
        $temp3="";

        // Create load commands & set audio duration and caption data from story.php
        for ($i = 1; $i < $numImages+1; $i++) {
            $temp= $temp."game.load.image('".$i."', '".$images_folder.$i.$image_ext."');";
            $i_minus = $i-1;
            $temp2=$temp2."audio_duration[".$i_minus."]=".$duration[$i_minus].";";
            $temp3=$temp3."caption[".$i_minus."]='".$story[$i_minus]."';";
                  
        } // END For

        // Display new javascript:
        echo $temp;
        echo $temp2;
        echo $temp3;
      
    ?>
      
    game.load.image('button','assets/button.svg');
    game.load.audio('audio', audio_loc);
		
		
		},
		
		create: function() {
		// Go to the menu state
            game.state.start('play');
		}	
	
};    
    
</script> 
<script type="text/javascript" src="js/play.js"></script>
<script type="text/javascript" src="js/game.js"></script>
</head>
    
<body>
<script>game.state.start('boot');</script>
<div id="gameDiv"></div>

    
        
<?php //<p class="hiddenText"> . </p> 
  } ?>

    
</body>
</html>