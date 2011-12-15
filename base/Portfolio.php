<?php

/********************************************************\
 * File: 	Portfolio.php								*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-12-15									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Portfolio frontpage module.				*
\********************************************************/

include_once("CvModule.php");

class Portfolio extends CvModule {

	function Description(){
		// No description for base modules
	}
	
	// Creates an form for editing user details in the database. Anything but the username and password, for now...
	function Content(){
		global $link, $dbprefix;
				
		$query = "SELECT * FROM " . $dbprefix . "projects ORDER BY year DESC, name ASC";
		$result = mysql_query($query, $link) or die ( mysql_error() );			
				
		while( $row = mysql_fetch_assoc($result) ){
			$buffer .= htmlifyProject($row);
		}

		return $buffer;
	}
	
	function POST(){
		// No post methods available in this module.
	}
}

?>
