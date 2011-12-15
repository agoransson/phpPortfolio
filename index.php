<?php

/********************************************************\
 * File: 	index.php									*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-11-21									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Start page.								*
\********************************************************/

/* Include the configuration file - contains the database connection */
include_once("config.php");

$defaultpage = "Portfolio";

if( isset($_GET["page"]) )
	$title = strtolower($_GET["page"]);

global $link, $dbprefix;

if( !checkInstalled() ){
	header( "Location: install.php" );
}else if( checkInstallFile() ){
	die( printError('Please remove, or rename, the "install.php" file') );
}

// Make sure we've selected a base module
if( !isset($_GET["page"]) ){
	header( "Location: index.php?page=$defaultpage" );
}else{
	// TODO regex to make sure the page exists in ./base/ otherwise point to default
}




$basemodules = array();

// Load all basemodules (Except the Login module)
foreach( glob("base/*.php") as $module ){
	$classname = preg_replace( '/\.[^.]*$/', '', basename($module) );
	$basemodules[] = new $classname();
}

?>


<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="./css/reset.css">
	<?php
		include("head.php");
	
		// TODO this needs to be fixed!
		
		$webkit = strpos($_SERVER['HTTP_USER_AGENT'],"AppleWebKit");

		// Load common CSS
		if($webkit === true){
			print '<link rel="stylesheet" type="text/css" href="./css/webkit.css">';
			print '<link rel="stylesheet" type="text/css" href="./css/emailpopup.css">';
		}else{
			print '<link rel="stylesheet" type="text/css" href="./css/desktop.css">';
			print '<link rel="stylesheet" type="text/css" href="./css/emailpopup.css">';
		}

		// Load mod CSS
		foreach( $basemodules as $mod ){
			if( get_class($mod) === $_GET["page"] ){
				print '<link rel="stylesheet" type="text/css" href="./css/'.$mod->Name().'.css">';
				break;
			}
		}
	?>

	<script type="text/javascript" src="./scripts/jquery-1.7.min.js"></script>
	<script type="text/javascript" src="./scripts/index.js"></script>
</head>

<body>

<!-- BEGIN PAGE -->
<div id="wrapper">

	<!-- BEGIN HEADER -->
	<div id="header">
	<?php include("header.php"); ?>
	</div>

	<!-- BEGIN SPECIFIC CONTENT -->
	<div id="content">
	<?php
		foreach( $basemodules as $mod ){
			if( get_class($mod) === $_GET["page"] ){
				print $mod->Content();
				break;
			}
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
	
