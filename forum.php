
<?php

/********************************************************\
 * File: 	index.php				*
 * Author: 	Andreas Göransson			*
 * Date: 	2011-11-21				*
 * Organization: Andreas Göransson			*
 *							*
 * Project: 	SenseMemory, webeditor interface.	*
 *							*
 * Description:	Portfolio - main page.			*
\********************************************************/

/* Include the configuration file - contains the database connection */
include_once("config.php");
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

/* This is a members only page, user needs to be logged in! */
//checkLoggedIn("yes");
if( !$user->data['is_registered'] )
	header("Location: index.php");

/* Use the global message array! */
global $messages;

/* Page title */
$title = "Forum";

/* If user pressed save button */
if (isset($_POST["save"])) {
	// TODO: save pattern to the database here...
}

?>

<!DOCTYPE html>
<html>

<head>
<!-- All the shared scripts, css, etc are loaded in that file -->
<?php include("head.php") ?>
<!-- Needs specific files loaded? -->
<!-- ... do it here... -->
</head>

<body>

<!-- For debugging purposes -->
<?php
//print("<pre>");
//print_r($_SESSION);
//print("</pre>");
?>

<!-- BEGIN PAGE -->
<div id="wrapper">

	<!-- BEGIN HEADER -->
	<div id="header">
	<?php include("header.php") ?>
	</div>

	<!-- BEGIN SPECIFIC CONTENT -->
	<div id="content">
	<?php

// Output page
page_header($user->lang['INDEX']);
	?>
	</div>

	<!-- BEGIN FOOTER -->
	<div id="footer">
	<?php include("footer.php") ?>
	</div>

<!-- END PAGE -->
</div>

</body>
</html>
