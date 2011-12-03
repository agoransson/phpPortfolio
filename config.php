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

global $link, $dbhost, $dbuser, $dbpass, $dbname, $dbname_app;

if( checkInstalled() ){
/* If the site is installed properlly, connect to the database... */

// Database details
$dbhost="host";
$dbuser="username";
$dbpass="password";
$dbname="phpPortfolio";

// Connect to the database - using persistant connection 
$link = mysql_pconnect( "$dbhost", "$dbuser", "$dbpass" ) or die ( mysql_error() );
mysql_select_db( "$dbname", $link ) or die ( mysql_error() );
}else{
/* ...otherwise don't do anything! */
}

$messages = array();

?>
