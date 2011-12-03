<?php

/********************************************************\
 * File: 	config.php				*
 * Author: 	Andreas Göransson			*
 * Date: 	2011-11-21				*
 * Organization: Andreas Göransson			*
 *							*
 * Project: 	Portfolio.				*
 *							*
 * Description:	Configurations script.			*
\********************************************************/

/* Set error reporting */
//error_reporting(E_ALL);

include_once("functions.php");

/* Database details */
$dbhost="host";
$dbuser="username";
$dbpass="password";
$dbname="phpPortfolio";

/* Connect to the database - using persistant connection */
global $link, $dbhost, $dbuser, $dbpass, $dbname, $dbname_app;
($link = mysql_pconnect("$dbhost", "$dbuser", "$dbpass")) || die("Couldn't connect to MySQL");
mysql_select_db("$dbname", $link) || die("Couldn't open db: $dbname. Error if any was: ".mysql_error() );

$messages = array();

?>
