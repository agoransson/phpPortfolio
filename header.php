<?php

/********************************************************\
 * File: 	header.php									*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-11-21									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Header script.							*
\********************************************************/

include_once("config.php");

global $link;

$name = "";
$email = "";

// Get the name and email
if( checkInstalled() == true ){
	$query = "SELECT name, email FROM cv_main ORDER BY id LIMIT 1";
	$result =  mysql_query( $query, $link ) or die ( mysql_error() );
	
	while( $row = mysql_fetch_assoc($result) ){
		$name = $row["name"];
		$email = $row["email"];
	}
}

// Get the browser context, this doesn't really work though I think... bad results depending on browser.
$webkit = strpos( $_SERVER['HTTP_USER_AGENT'], "AppleWebKit" );	

// Left side of header (message)
print '<div id="headerleft">';
switch( $title ){
	case "portfolio":
		print '<a id="trigger" href="mailto:' . $email . '">' . $name . '</a>';
		break;
	case "cv":
		print '<a id="trigger" href="mailto:' . $email . '">' . $name . '</a>';
		break;
	case "admin":
		print '<a href="index.php" class="titleleft">back</a>';
		break;
	case "install":
		print '<p class="titleleft">welcome</p>';
		break;
	default:
		print '<a href="javascript:history.back()" class="titleleft">back</a>';
		break;
}
print "</div>";


// Right side of header (title)
print '<div id="headerright">';
if( $webkit === true ){
	// No page-title if webkit?
}else{
	switch( $title ){
		case "portfolio":
			print '<p class="titleright"><a href="index.php">portfolio</a>|<a class="notselected" href="cv.php">CV</a>';
			break;
		case "cv":
			print '<p class="titleright"><a class="notselected" href="index.php">portfolio</a>|<a href="cv.php">CV</a>';
			break;
		default:
			print '<p class="titleright">'.$title.'</p>';
			break;
	}
}
print "</div>";

?>
