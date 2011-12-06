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

global $dbprefix, $dbname, $dbuser, $dbpass, $dbhost;

// Include all modules
foreach( glob("modules/*.php") as $module ){
    include $module;
}

// Database details
$dbhost="host";
$dbuser="user";
$dbpass="pass";
$dbname="schema";
$dbprefix="prefix";

?>
