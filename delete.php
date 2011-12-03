<?php

/********************************************************\
 * File: 	delete.php				*
 * Author: 	Andreas Göransson			*
 * Date: 	2011-11-22				*
 * Organization: Andreas Göransson			*
 *							*
 * Project: 	Portfolio.				*
 *							*
 * Description:	Delete script.				*
\********************************************************/

include_once("config.php");

// Make sure we're logged in!!! So others can't delete...

// Variables
$id = $_GET["id"];
$query = "DELETE FROM projects WHERE idprojects='" . $id . "'";	
$result = mysql_query($query,$link) or die ( mysql_error() );

// Redirect:
header("Location: admin.php");

?>
