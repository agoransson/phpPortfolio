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

function printError( $err ){
	$lines = explode( '. ', $err );
	print '<div class="errors">';
	foreach( $lines as $line ){
		print '<p>'.$line.'</p>';
	}
	print '</div>';
}

function connect_to_db(){
	global $link, $dbhost, $dbuser, $dbpass, $dbname;

	$link = mysql_connect( $dbhost, $dbuser, $dbpass );
	if( !$link )
		return false;

	$result = mysql_select_db( $dbname, $link );
	if( !$result )
		return false;

	return $link;
}

function checkInstallFile(){
	return file_exists( "install.php" );
}

function checkInstalled(){
	global $link, $dbname, $dbprefix;

	// Count number of $dbprefix tables in the database (should be 7)
	$query = "SHOW TABLES IN $dbname LIKE '$dbprefix%'";
	$link = connect_to_db();
	if( !$link )
		return false;

	$result = mysql_query($query,$link) or die ( mysql_error() );
	
	return (mysql_num_rows($result) == 7 ? true : false);
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
			if( preg_match("/[a-zA-Z0-9_]+\.(jpg|png|bmp)$/i", $file) )
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
