<?php

/********************************************************\
 * File: 	index.php				*
 * Author: 	Andreas Göransson			*
 * Date: 	2011-11-21				*
 * Organization: Andreas Göransson			*
 *							*
 * Project: 	Portfolio.				*
 *							*
 * Description:	Main page.				*
\********************************************************/
 
/* Include the configuration file - contains the database connection */
include_once("config.php");

/* Use the global message array! */
global $messages;

/* Is the user logged in? */
$title = "portfolio";

?>


<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="./css/reset.css">
	<?php
		include("head.php");
	
		$webkit = strpos($_SERVER['HTTP_USER_AGENT'],"AppleWebKit");

		if($webkit === true){
			print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/desktop.css\">";
			print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/emailpopup.css\">";
			print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/index.css\">";
		}else{
			print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/desktop.css\">";
			print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/emailpopup.css\">";
			print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/index.css\">";
		}
	?>

	<script type="text/javascript" src="./scripts/jquery-1.7.min.js"></script>
	<script type="text/javascript" src="./scripts/index.js"></script>
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
	<?php include("header.php"); ?>
	</div>

	<!-- BEGIN SPECIFIC CONTENT -->
	<div id="content">
	<?php
		$query = "SELECT * FROM projects ORDER BY date DESC, name ASC";
		$result = mysql_query($query, $link) or die ( mysql_error() );
		
		while( $row = mysql_fetch_assoc($result) ){
			print htmlifyProject($row);
		}
	?>
	</div>

	<!-- BEGIN FOOTER -->
	<div id="footer">
	<?php include( ($webkit === true ? "":"footer.php") ); ?>
	</div>

	<!-- POPUP -->
	<div id="email_popup">
		<p>Feel free to email me, just click my name.</p>
	</div>

<!-- END PAGE -->
</div>

</body>
</html>
	
