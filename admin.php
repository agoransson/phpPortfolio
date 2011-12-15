<?php

/********************************************************\
 * File: 	admin.php									*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-11-21									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Administrative interface.				*
\********************************************************/

/* Include the configuration file - contains the database connection */
include_once("config.php");

/* Is the user logged in? */
$title = "admin";


// Check if the site is installed correctly
if( checkInstalled() == false )
	header( "Location: install.php" );

// Make sure we're pointing to a module!
if( count($_GET) == 0 )
	header( (Login::LoggedIn() ? "Location: admin.php?mod=Projects" : "Location: admin.php?mod=Login") );
else if( count($_GET) > 0 && !Login::LoggedIn() && $_GET["mod"] !== "Login" )
	header( "Location: admin.php?mod=Login" );

// Load all modules (Except the Login module)
foreach( glob("modules/*.php") as $module ){
	$classname = preg_replace('/\.[^.]*$/', '', basename ( $module ));
	$modules[] = new $classname();
}

// Test POST methods for modules
foreach( $modules as $mod ){
	if( isset($_POST[$mod->Name()]) ){
		$mod->POST();
	}
}

// Test GET methods for modules
foreach( $modules as $mod ){
	if( isset($_GET["mod"]) && $_GET["mod"] == $mod->Name() && count($_GET) > 1 ){
		$mod->GET();
	}
}

?>

<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="./css/reset.css">
<?php
	include("head.php");
	
	$webkit = strpos($_SERVER['HTTP_USER_AGENT'],"AppleWebKit");		
	if($webkit === true){
		print '<link rel="stylesheet" type="text/css" href="./css/webkit.css">';
		print '<link rel="stylesheet" type="text/css" href="./css/modules.css">';
	}else{
		print '<link rel="stylesheet" type="text/css" href="./css/modules.css">';
		print '<link rel="stylesheet" type="text/css" href="./css/desktop.css">';
	}
	
	// Load module CSS, if they exist!
	foreach( $modules as $mod ){
		if( isset($_GET["mod"]) && $_GET["mod"] == $mod->Name() ){
			$css = "./modules/" . $mod->Name() . ".css";
			if( file_exists($css) ){
				print '<link rel="stylesheet" type="text/css" href="'.$css.'">';
			}
		}
	}
?>
</head>

<body>

<!-- BEGIN PAGE -->
<div id="wrapper">

	<!-- BEGIN HEADER -->
	<div id="header">
	<?php include("header.php") ?>
	</div>

	<!-- BEGIN SPECIFIC CONTENT -->
	<div id="content">
	<?php		
		// Do module mods
		print '<ul class="modulelist">';
		foreach( $modules as $mod ){
			print ( isset($_GET["mod"]) && get_class($mod) === $_GET["mod"] ? $mod->Menu(true) : $mod->Menu() );
		}
		print '</ul>';

		// Load selected module content
		foreach( $modules as $mod ){
			if( get_class($mod) === $_GET["mod"] ){
				print $mod->Description();
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

<!-- END PAGE -->
</div>

</body>
</html>
