<?php

/********************************************************\
 * File: 	project.php									*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-11-21									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Project details.						*
\********************************************************/

/* Include the configuration file - contains the database connection */
include_once("config.php");

global $dbprefix, $link;

if( !$link )
	$link = connect_to_db();

/* Use the global message array! */
global $messages;

/* Is the user logged in? */
$id = $_GET["id"];
	
/* If user pressed login button, check credentials! */
if (isset($_POST["login"])) {
	/* phpBB3 session login */
	$username = $_POST["username"];
	$password = $_POST["password"];
	$remember = false;
	$auth->login($username, $password, $remember, 1, 0);
}

/* Load project from db */
$query = "SELECT * FROM " . $dbprefix."projects WHERE id = " . $id;
$result = mysql_query($query, $link) or die ( mysql_error() );

if( $row = mysql_fetch_assoc($result) ){
	$name = $row["name"];
	$gallery = $row["gallery"];
	$desc = $row["description"];
	$year = $row["year"];
	$for = $row["target"];
}

$title = $name;

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
		print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/gallery.css\">";
		print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/project.css\">";
	}else{
		print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/desktop.css\">";
		print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/gallery.css\">";
		print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/project.css\">";
	}
?>

	<script type="text/javascript" src="scripts/jquery-1.7.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.cycle.all.js"></script>
	<script type="text/javascript" src="scripts/gallery.js"></script>
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
	
	<div id="slideshow">
		<ul id="nav">
			<li id="prev"><a href="#">Previous</a></li>
			<li id="next"><a href="#">Next</a></li>
		</ul>

		<ul id="slides">
		<?php
			// We need to make sure to remove certain illegal characters!
			$dir = preg_replace( "{\?}i", "", "./media/" . $title );

			$files = getImageList( $dir );
			for( $i = 0; $i < count($files); $i++ ){
				$file = preg_replace( "{\?}i", "", $files[$i] );			
				if( strpos($file,"icon") === false ){
					print "<li><img src=\"" . $dir . "/" . $file . "\" alt=\"" . $file . "\" /></li>";
				}
			}
		?>
		</ul>
	</div>

	<div id="projectinfo">
		<?php 
		print "<h2>" . $title . "</h2>";
		print "<h4>(" . $year . ")</h4>";
		$strings = explode( "\n", $desc );
		for( $i = 0; $i < count($strings); $i++ )
			print "<p>" . $strings[$i] . "</p>";
		//print "<p>" . str_replace("\n", "</br>", $desc) . "</p>";
		?>
	</div>
	
	</div>

	<!-- BEGIN FOOTER -->
	<div id="footer">
	<?php include( ($webkit === true ? "":"footer.php") ); ?>
	</div>

<!-- END PAGE -->
</div>

</body>
</html>
