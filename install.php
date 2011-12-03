<?php

/********************************************************\
 * File: 	install.php									*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-11-21									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Installation script.					*
\********************************************************/


/* Is the user logged in? */
$title = "install";

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
			print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/install.css\">";
		}else{
			print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/desktop.css\">";
			print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/install.css\">";
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
		<form action="<?php print $_SERVER["PHP_SELF"]; ?>" method="POST">
		<table style="text-align:left">
<?php
		if( !isset($_POST["server"]) ){
			// Enter login details
			print "<tr><td colspan=\"2\">Create your user</td></tr>";
			print "<tr><td>login:</td><td><input type=\"text\" name=\"username\" placeholder=\"username\" /></td></tr>";
			print "<tr><td>password:</td><td><input type=\"text\" name=\"password1\" placeholder=\"password\" /></td></tr>";
			print "<tr><td>password (again):</td><td><input type=\"text\" name=\"password2\" placeholder=\"password again\" /></td></tr>";
			// Enter personal details
			print "<tr><td>full name:</td><td><input type=\"text\" name=\"fullname\" placeholder=\"John Doe\" /></td></tr>";
			print "<tr><td>street:</td><td><input type=\"text\" name=\"street\" placeholder=\"My street 1A\" /></td></tr>";
			print "<tr><td>city:</td><td><input type=\"text\" name=\"city\" placeholder=\"Atlantis\" /></td></tr>";
			print "<tr><td>country:</td><td><input type=\"text\" name=\"country\" placeholder=\"A lost empire\" /></td></tr>";
			print "<tr><td>phone:</td><td><input type=\"text\" name=\"phone\" placeholder=\"+007 007 007\" /></td></tr>";
			print "<tr><td>email:</td><td><input type=\"text\" name=\"email\" placeholder=\"john@doe.org\" /></td></tr>";
			// Enter server details
			print "<tr><td colspan=\"2\">Enter MySQL server details</td></tr>";
			print "<tr><td>mysql server:</td><td><input type=\"text\" name=\"dbhost\" placeholder=\"localhost\" /></td></tr>";
			print "<tr><td>db user:</td><td><input type=\"text\" name=\"dbusername\" placeholder=\"username\" /></td></tr>";
			print "<tr><td>db password:</td><td><input type=\"text\" name=\"dbpassword\" placeholder=\"password\" /></td></tr>";
			print "<tr><td colspan=\"2\"><input name=\"server\" type=\"submit\" value=\"Create\"></td></tr>";
		}else{
			// New values
			$dbhost = $_POST["dbhost"];
			$dbuser = $_POST["dbusername"];
			$dbpass = $_POST["dbpassword"];
			
			// Edit config.php
			$source = "config.php";
			$target = "new_" . $source;
			$sh = fopen($source, "r");
			$th = fopen($target, "w");
			
			while( !feof($sh) ){
				// Get the line
				$line = fgets($sh);
				
				// Replace the values
				if( strpos($line, '$dbhost="host";') !== false ){
					$line = '$dbhost="' . $dbhost . '";' . PHP_EOL;
				}else if( strpos($line, '$dbuser="username";') !== false ){
					$line = '$dbuser="' . $dbuser . '";' . PHP_EOL;
				}else if( strpos($line, '$dbpass="password";') !== false ){
					$line = '$dbpass="' . $dbpass . '";' . PHP_EOL;
				}
				
				// Write the line to the "new_" file
				fwrite( $th, $line );
			}
			
			// Close files
			fclose( $sh );
			fclose( $th );
			
			// Delete old file and rename the new file
			unlink( $source );			
			rename( $target, $source );
						
			// Execute the SQL script
			$sqlFileToExecute = 'phpPortfolio.sql';
			$f = fopen($sqlFileToExecute,"r+");
			$sqlFile = fread($f, filesize($sqlFileToExecute));
			$sqlArray = explode(';',$sqlFile);
			
			mysql_connect( $dbhost, $dbuser, $dbpass ) or die ( mysql_error() );
			
			$sqlErrorCode = 0;
			
			foreach ($sqlArray as $stmt) {
				if( strlen($stmt)>3 && substr( ltrim($stmt), 0, 2 ) != '/*' ){
					$result = mysql_query( $stmt );
					if ( !$result ) {
						$sqlErrorCode = mysql_errno();
						$sqlErrorText = mysql_error();
						$sqlStmt = $stmt;
						break;
					}
				}
			}
			if( $sqlErrorCode == 0 ){
				echo "Script is executed succesfully!";
			}else{
				echo "An error occured during installation!<br/>";
				echo "Error code: $sqlErrorCode<br/>";
				echo "Error text: $sqlErrorText<br/>";
				echo "Statement:<br/> $sqlStmt<br/>";
			}
			
			// Add the user to the database!
			$userlogin = $_POST["username"];
			$userpw1 = $_POST["password1"];
			$userpw2 = $_POST["password2"];
			
			$fullname = $_POST["fullname"];
			$street = $_POST["street"];
			$city = $_POST["city"];
			$country = $_POST["country"];
			$phone = $_POST["phone"];
			$email = $_POST["email"];
			
			mysql_select_db( "phpportfolio" ) or die ( mysql_error() );
			
			include_once( "functions.php" );
			
			if( register( $userlogin, $userpw1, $userpw2, $fullname, $street, $city, $country, $phone, $email ) === true ){
				$filename = "installed.now";
				$filehandle = fopen($filename, 'w') or die ( "can't create installed.now!" );
				fclose($filehandle);
				
				header('Location: index.php');
			}
		}
?>
		</table>
		</form>
	</div>

	<!-- BEGIN FOOTER -->
	<div id="footer">
	<?php include( ($webkit === true ? "":"footer.php") ); ?>
	</div>

<!-- END PAGE -->
</div>

</body>
</html>
	
