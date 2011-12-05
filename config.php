<?php

/********************************************************\
 * File: 	config.php									*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-11-21									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Configuration script.					*
\********************************************************/

include_once("functions.php");

// Include all modules
foreach( glob("modules/*.php") as $module ){
    include $module;
}

global $link, $dbprefix;

if( checkInstalled() ){
	/* If the site is installed properlly, connect to the database... */

	// Database details
	$dbhost="host";
	$dbuser="username";
	$dbpass="password";
	$dbname="schema";
	$dbprefix="prefix";

	// Connect to the database - using persistant connection 
	$link = mysql_connect( $dbhost, $dbuser, $dbpass ) or die ( mysql_error() );
	mysql_select_db( $dbname, $link ) or die ( mysql_error() );
}

?>
