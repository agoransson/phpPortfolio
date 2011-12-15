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

global $link, $dbprefix, $dbname, $dbuser, $dbpass, $dbhost;

// Include all base modules
foreach( glob("base/*.php") as $module ){
    include $module;
}

// Include all extra modules
foreach( glob("modules/*.php") as $module ){
    include $module;
}

// Database details
$dbhost="host";
$dbuser="user";
$dbpass="pass";
$dbname="schema";
$dbprefix="prefix";

// Try to connect, might not work since it might not be installed yet
$link = connect_to_db( $dbhost, $dbuser, $dbpass, $dbname );

?>
