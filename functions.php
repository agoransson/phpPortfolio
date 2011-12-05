<?php

/********************************************************\
 * File: 	functions.php								*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-11-21									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Extra functions.						*
\********************************************************/

// Start a new, or continue the old, PHP session.
session_start();

function checkInstalled(){
	$filename = "installed.now";
	$installscript = "install.php";
	
	if( file_exists($filename) ){
		// Installed: Try to remove/rename the install script and return true.
		if( file_exists($installscript) )
			rename("install.php", "installphp.bak");
		
		return true;
	}else{		
		// Not installed yet: just return false
		return false;
	}
}

//creates a 3 character sequence
function createSalt(){
	$string = md5(uniqid(rand(), true));
	return substr($string, 0, 3);
}


function getImageList($directory){
	// create an array to hold directory list
	$results = array();

	// create a handler for the directory
	$handler = opendir($directory);
	
	// open directory and walk through the filenames
	while( $file = readdir($handler) ){
		// if file isn't this directory or its parent, add it to the results
		if( $file != "." && $file != ".." ){
			// Make sure only images (jpg, png, gif, etc.) are accepted
			if( preg_match("/\.(jpg|png|bmp)$/i", $file) )
				$results[] = $file;
		}
	}

	// tidy up: close the file handler
	closedir($handler);

	// done!
	return $results;
}

/* Returns a html-version of the project */
function htmlifyProject( $row ){
	$id = $row["id"];
	$name = $row["name"];
	$description = $row["description"];

	$dir = preg_replace( "{\?}i", "", "./media/" . $name );

	$image = $dir . "/icon.png";
	$image_gray = str_replace( ".png", "_gray.png", $image );
	$year = $row["year"];
	$tags = $row["tags"];

	$ret = '<div id="'.$id.'" name="'.$name.'" class="project" style="background-image: url(\''.$image_gray.'\')">';
	
	$ret .= '<section class="projecttitle">';	
	$ret .= '<p class="projectname">'.$name.'</p>';	
	$ret .= '</section>';

	$ret .= '<section class="projectdesc">';
	$ret .= '<p class="projectdate">'.$year.'</p>';
	$ret .= '<p class="projecttags">'.$tags.'</p>';
	$ret .= '</section>';

	$ret .= '</div>';
	
	return $ret;
}

?>

