<?php

/********************************************************\
 * File: 	index.php				*
 * Author: 	Andreas Göransson			*
 * Date: 	2011-11-25				*
 * Organization: Andreas Göransson			*
 *							*
 * Project: 	Portfolio.				*
 *							*
 * Description:	Logout script				*
\********************************************************/

include_once("config.php");

unset( $_SESSION["loggedIn"] );

header("Location: index.php");
?>

