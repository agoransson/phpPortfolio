<?php

/********************************************************\
 * File: 	config.php				*
 * Author: 	Andreas Göransson			*
 * Date: 	2011-11-21				*
 * Organization: Andreas Göransson			*
 *							*
 * Project: 	Portfolio.				*
 *							*
 * Description:	Header script.				*
\********************************************************/

 
include_once("config.php");

$webkit = strpos($_SERVER['HTTP_USER_AGENT'],"AppleWebKit");	

// Logo
print "<div id=\"headerleft\">";

print ($title === "portfolio" || $title === "cv" ? "<a id=\"trigger\" href=\"mailto:ag@santiclaws.se\">Andreas Göransson</a>" : ($title === "admin" ? "<a href=\"index.php\" class=\"title\">back</a>" : "<a href=\"javascript:history.back()\" class=\"title\">back</a>"));
print "</div>";


// Title (upper right)
print "<div id=\"headerright\">";

if( $webkit === true ){
	// No page-title if webkit?
}else{	
	print ( $title === "portfolio" ? "<p class=\"title\"><a href=\"index.php\">portfolio</a>|<a class=\"notselected\" href=\"cv.php\">CV</a>" : ( $title === "cv" ? "<p class=\"title\"><a class=\"notselected\" href=\"index.php\">portfolio</a>|<a href=\"cv.php\">CV</a>" : "<p class=\"title\">".$title."</p>" ) );
}

print "</div>";


?>
