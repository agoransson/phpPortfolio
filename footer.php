<?php

/********************************************************\
 * File: 	footer.php									*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-11-21									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Footer script.							*
\********************************************************/

global $messages;

// Errors
if( isset($modules) ){
	foreach( $modules as $mod ){
		if( $mod->Error() ) {
			print '<div class="errors">';
			print ( isset($_GET["mod"]) && get_class($mod) === $_GET["mod"] ? $mod->Error() : "" );
			print "</div>";
		}
	}
}

// mods
print "<div id=\"mods\">";
switch( $title ){
	case "portfolio":
		print '<a href="admin.php">login</a>';
		break;
	case "cv":
		print '<a href="cv.php?print=pdf">save as pdf</a>';
		break;
	case "admin":
		if( isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true )
			print '<a href="admin.php?mod=Login&logout=true">logout</a>';
		break;
	case "install":
		// Nothing
		break;
	default:
		// Nothing
		break;
}
print "</div>";

// Information
print '<div id=copyleft">';
print '<a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/"><img alt="Creative Commons-licens" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/3.0/80x15.png" /></a><br />Detta <span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" rel="dct:type"><a href="https://github.com/agoransson/phpPortfolio">verk</a></span> av <span xmlns:cc="http://creativecommons.org/ns#" property="cc:attributionName">Andreas Göransson</span> är licensierat under en <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Erkännande-DelaLika 3.0 Unported-licens</a>.';
print '<br />Tested, and working, in <a href="http://www.mozilla.org/firefox">FireFox 8.0</a>, <a href="http://www.google.com/chrome">Chrome 15</a>, and <a href="http://windows.microsoft.com/sv-SE/internet-explorer/products/ie/home">Internet Explorer 9</a>';
print '</div>';


?>
