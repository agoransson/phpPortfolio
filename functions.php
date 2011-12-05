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

/* Use the global message array! */
global $messages;

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

function register( $userlogin, $userpw1, $userpw2, $fullname, $street, $city, $country, $phone, $email ){
	// Make sure the two passwords are the same, and that the username doesn't exeed the limit
	if( $userpw1 != $userpw2 )
		return false;
	if( strlen($username) > 30 )
		return false;

	// Get the hash
	$hash = hash( "sha256", $userpw1 );
	
	// Add the randomizer
	$salt = createSalt();
	$hash = hash( "sha256", $salt . $hash );

	$_POST["salt"] = $salt;
	$_POST["hash"] = $hash;
	
	// ...and make sure someone isn't trying to hack the db.
	$username = mysql_real_escape_string($username);
	$query = "INSERT INTO cv_main ( username, password, salt, name, street, city, country, phone, email )
		VALUES ( '$userlogin', '$hash', '$salt', '$fullname', '$street', '$city', '$country', '$phone', '$email' );";
		
	if( $link )
		mysql_query( $query, $link ) or die ( mysql_error() );
	else
		mysql_query( $query ) or die ( mysql_error() );
		
	return true;
}

function attemptLogIn($username, $password){
	$username = mysql_real_escape_string($username);

	$query = "SELECT password, salt FROM cv_main WHERE username = '$username';";
	$result = mysql_query( $query ) or die ( mysql_error() );

	if( mysql_num_rows($result) < 1 ){
		// No such user!
		if( isset($_SESSION["loggedIn"]) )
			unset( $_SESSION["loggedIn"] );
			
		return false;
	}

	$row = mysql_fetch_array( $result, MYSQL_ASSOC );
	$hash = hash( "sha256", $row["salt"] . hash("sha256", $password) );
	if( $hash != $row["password"] ){
		// Incorrect password!
		if( isset($_SESSION["loggedIn"]) )
			unset( $_SESSION["loggedIn"] );
		
		return false;
	}

	$_SESSION["loggedIn"] = true;
	$_SESSION["username"] = $username;
	
	// Logged in
	return true;
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
function htmlifyProject($row){//, $index) {
	$id = $row["id"];
	$name = $row["name"];
	$description = $row["description"];

	$dir = preg_replace( "{\?}i", "", "./media/" . $name );

	$image = $dir . "/icon.png";
	$image_gray = str_replace( ".png", "_gray.png", $image );
	$target = $row["target"];
	$when = $row["date"];
	$tags = $row["tags"];

	$ret = '<div id="'.$id.'" name="'.$name.'" class="project" style="background-image: url(\''.$image_gray.'\')">';
	
	$ret .= '<section class="projecttitle">';	
	$ret .= '<p class="projectname">'.$name.'</p>';	
	$ret .= '</section>';

	$ret .= '<section class="projectdesc">';
	$ret .= '<p class="projectdate">'.$when.'</p>';
	$ret .= '<p class="projecttags">'.$tags.'</p>';
	$ret .= '</section>';

	$ret .= '</div>';
	
	return $ret;
}

?>

