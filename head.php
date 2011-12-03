<?php

/********************************************************\
 * File: 	head.php									*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-11-21									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Head script.							*
\********************************************************/

/* 
 * Add scripts, styles, etc here... this site is so small that we'll have all 
 * pages share this head statement!
 */
 
print "<title>".$title."</title>";
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">";

/* Print the webkit meta tag if applicable */
$webkit = strpos($_SERVER['HTTP_USER_AGENT'],"AppleWebKit");
$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");		
if($webkit === true || $iphone === true){
	print "<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;\" />";
}else{
	// Nothing?
}

?>
