
<?php

/********************************************************\
 * File: 	admin.php				*
 * Author: 	Andreas Göransson			*
 * Date: 	2011-11-22				*
 * Organization: Andreas Göransson			*
 *							*
 * Project: 	Portfolio.				*
 *							*
 * Description:	Administrative interface.		*
\********************************************************/

/* Include the configuration file - contains the database connection */
include_once("config.php");

/* Use the global message array! */
global $messages;

/* Is the user logged in? */
$title = "admin";

/* If user added a new project */
if ( isset($_POST["save"]) && (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) ) {

	$name = $_POST["name"];
	$date = $_POST["date"];
	$target = $_POST["target"];
	$image = $_POST["image"];
	$tags = $_POST["tags"];
	$description = $_POST["description"];

	// Make sure the project name doesn't exist!
	$query = "SELECT * FROM projects WHERE name='$name'";
	
	// Run query:
	$result = mysql_query( $query, $link ) or die ( mysql_error() );

	// If a row exists with that name, issue an error message:
	if( $row = mysql_fetch_array($result) ){
		$messages[] = "Source already exist, try another!";
	}
	
	if( empty($messages) ) {
		$query = "INSERT INTO projects (name, date, target" . /*(strlen($image) > 0 ? ", image" : "") .*/ ", tags, description) VALUES ('$name', '$date', '$target'" . /*(strlen($image)>0? ", '$image'" : "") .*/ ", '$tags', '$description')";
		$result = mysql_query( $query, $link ) or die ( mysql_error() );
	}
}else if( isset($_POST["login"]) ){
	if( !attemptLogIn( $_POST["username"], $_POST["password"] ) ){
		header( "Location: index.php" );
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
		print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/webkit.css\">";
	}else{
		print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/desktop.css\">";
	}
?>
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
		if( !isset($_SESSION["loggedIn"]) ){
			// if-not loggedIn : Do PHP login script here...
			print "<form name=\"login\" action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"POST\">";
			print "Username: <input type=\"text\" name=\"username\" />";
			print "Password: <input type=\"password\" name=\"password\" />";
			print "<input type=\"submit\" value=\"Login\" name=\"login\"/>";
			print "</form>";
		}else if( isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true ){
			// else : Do project list
			$query = "SELECT * FROM projects";
			$result = mysql_query($query, $link);
		
			print "<table class=\"reference\"><tbody>";
			while( $row = mysql_fetch_assoc($result) ){
				print getTableRow($row);
			}
			print "</tbody></table>";

			print "<hr>";

			print "<form id=\"newproject\" action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"POST\">";
			print "<table class=\"reference\">";
			print "<tbody>";
			print "<tr><td>Name:</td><td><input type=\"text\" name=\"name\" maxlength=\"32\"></td></tr>";
			print "<tr><td>Year:</td><td><input type=\"text\" name=\"date\" maxlength=\"32\"></td></tr>";
			print "<tr><td>For:</td><td><input type=\"text\" name=\"for\" maxlength=\"32\"></td></tr>";
			print "<!--tr><td>Image:</td><td><input type=\"text\" name=\"image\" maxlength=\"32\"></td></tr-->";
			print "<tr><td>Tags:</td><td><input type=\"text\" name=\"tags\" maxlength=\"32\"></td></tr>";
			print "<tr><td>Description:</td><td><textarea rows=\"5\" cols=\"47\" name=\"description\"></textarea></td></tr>";

			print "<tr><td><input name=\"save\" type=\"submit\" value=\"Save\"></td></tr>";
			print "</tbody>";
			print "</table>";
			print "</form>";
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
