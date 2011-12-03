<?php
/********************************************************\
 * File: 	index.php				*
 * Author: 	Andreas Göransson			*
 * Date: 	2011-11-21				*
 * Organization: Andreas Göransson			*
 *							*
 * Project: 	Portfolio.				*
 *							*
 * Description:	Footer script.				*
\********************************************************/

include_once("config.php");

// Print errors... 
if (!empty($messages)) {
	print "<div id=\"errors\">";
	displayErrors($messages);
	print "</div>";
};

// Links
print "<div id=\"links\">";
print ($title === "portfolio" ? "<a href=\"admin.php\">login</a>" : ( ($title === "admin" && isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) ? "<a href=\"logout.php\">logout</a>" : ($title === "cv" ? "<a href=\"cv.php?print=pdf\">save as pdf</a>" : "")) );

print "</div>";

// Information
print "<div id=\copyleft\">";
	print "<a rel=\"license\" href=\"http://creativecommons.org/licenses/by-sa/3.0/\"><img alt=\"Creative Commons-licens\" style=\"border-width:0\" src=\"http://i.creativecommons.org/l/by-sa/3.0/80x15.png\" /></a><br />Detta <span xmlns:dct=\"http://purl.org/dc/terms/\" href=\"http://purl.org/dc/dcmitype/InteractiveResource\" rel=\"dct:type\">verk</span> av <span xmlns:cc=\"http://creativecommons.org/ns#\" property=\"cc:attributionName\">Andreas Göransson</span> är licensierat under en <a rel=\"license\" href=\"http://creativecommons.org/licenses/by-sa/3.0/\">Creative Commons Erkännande-DelaLika 3.0 Unported-licens</a>.";
print "<br />Tested, and working, in <a href=\"www.mozilla.org/firefox/\">FireFox</a>, <a href=\"http://www.google.com/chrome\">Chrome</a>";
print "</div>";


?>
