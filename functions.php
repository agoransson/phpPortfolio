<?php

/********************************************************\
 * File: 	functions.php				*
 * Author: 	Andreas Göransson			*
 * Date: 	2011-11-21				*
 * Organization: Andreas Göransson			*
 *							*
 * Project: 	Portfolio.				*
 *							*
 * Description:	Functions.				*
\********************************************************/

require('fpdf.php');

// Start a new, or continue the old, PHP session.
session_start();

//creates a 3 character sequence
function createSalt(){
	$string = md5(uniqid(rand(), true));
	return substr($string, 0, 3);
}

function register($username, $pass1, $pass2){
	// Make sure the two passwords are the same, and that the username doesn't exeed the limit
	if( $pass1 != $pass2 )
		return false;
	if( strlen($username) > 30 )
		return false;

	// Get the hash
	$hash = hash('sha256', $pass1);
	
	// Add the randomizer
	$salt = createSalt();
	$hash = hash('sha256', $salt . $hash);

	$_POST["salt"] = $salt;
	$_POST["hash"] = $hash;
	
	// ...and make sure someone isn't trying to hack the db.
	$username = mysql_real_escape_string($username);
	$query = "INSERT INTO users ( username, password, salt )
		VALUES ( '$username' , '$hash' , '$salt' );";

	mysql_query( $query, $link ) or die ( mysql_error() );

	// Move to the start-page
	header('Location: index.php');
}

function attemptLogIn($username, $password){
	//connect to the database here
	$username = mysql_real_escape_string($username);

	$query = "SELECT password, salt FROM users WHERE username = '$username';";
	$result = mysql_query($query);

	if(mysql_num_rows($result) < 1){
		// No such user!
		if( isset($_SESSION["loggedIn"]) )
			unset( $_SESSION["loggedIn"] );
		return false;
	}

	$userData = mysql_fetch_array($result, MYSQL_ASSOC);
	$hash = hash('sha256', $userData['salt'] . hash('sha256', $password) );
	if($hash != $userData['password']){
		// Incorrect password!
		if( isset($_SESSION["loggedIn"]) )
			unset( $_SESSION["loggedIn"] );
		return false;
	}

	$_SESSION["loggedIn"] = true;

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

	// tidy up: close the handler
	closedir($handler);

	// done!
	return $results;
}

function getTableRow($row){
	$id = $row["idprojects"];
	$name = $row["name"];
	$description = $row["description"];
	//$image = $row["image"];
	//$image_gray = str_replace( ".png", "_gray.png", $image );
	$for = $row["for"];
	$when = $row["date"];
	$tags = $row["tags"];

	
	return "<tr>" . "<td>" . $id . "</td>" . "<td>" . $name . "</td>" . "<td>" . $when . "</td>" . "<td>" . $description . "</td>" . "<td><a href=\"delete.php?id=" . $id . "\">del</a></td>" . "</tr>";
}

/* Returns a html-version of the project */
function htmlifyProject($row){//, $index) {
	$id = $row["idprojects"];
	$name = $row["name"];
	$description = $row["description"];

	$dir = preg_replace( "{\?}i", "", "./media/" . $name );

	$image = $dir . "/icon.png"; //$row["image"];
	$image_gray = str_replace( ".png", "_gray.png", $image );
	$target = $row["target"];
	$when = $row["date"];
	$tags = $row["tags"];

	// Used to add a specific class (for proper margins)
	$classpos = ($index%4?1:0);
	if( $classpos != 0 ){
		$classpos = ($index%3?1:2);
	}

	//$ret = "<div class=\"project\" style=\"background: url('" . $image . "') bottom;\">";
	$ret = "<div id=\"" . $id . "\" name=\"" . $name . "\" class=\"project\" style=\"background-image: url('" . $image_gray . "')\">";
	// " . ($classpos==0?"left":($classpos == 2?"right":"middle")) . "

	$ret .= "<section class=\"projecttitle\">";	
	$ret .= "<p class=\"projectname\">" . $name . "</p>";	
	$ret .= "</section>";

	$ret .= "<section class=\"projectdesc\">";	
	//$ret .= "target: " . $target . "</br>";	
	$ret .= "<p class=\"projectdate\">" . $when . "</p>";
	$ret .= "<p class=\"projecttags\">" . $tags . "</p>";
	$ret .= "</section>";

	$ret .= "</div>";
	
	return $ret;
}

?>

