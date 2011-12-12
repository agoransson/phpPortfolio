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
include_once("config.php");

/* Is the user logged in? */
$title = "install";

if( checkInstalled() === true ){
	die( 'Please delete, or rename, this file ("install.php"). phpPortfolio is already installed.' );
}

function deleteTables($link, $dbname, $dbprefix){
	$query = "SHOW TABLES IN $dbname LIKE '$dbprefix%'";
		
	$result = mysql_query( $query, $link );
	
	if( !$result )
		return mysql_error();

	$numtables = mysql_num_rows($result);

	if( $numtables > 0 ){
		$query = "DROP TABLE IF EXISTS ";

		$i = 1;
		while( $row = mysql_fetch_array( $result ) ){
			$query .= $row[0] . ($i++<$numtables?", ":"");
		}

		$result = mysql_query( $query, $link );

		if( !$result )
			return mysql_error();

	}else{
		return "No tables found with prefix $dbprefix";
	}

	return true;
}

function register( $link, $user ){ //username, $userpw1, $userpw2, $fullname, $street, $city, $country, $phone, $email ){
	$errors = "";
	// Make sure the two passwords are the same, and that the username doesn't exeed the limit
	if( $user["password1"] != $user["password2"] )
		$errors .= "The user passwords don't match. ";

	if( strlen($user["fullname"]) > 30 )
		$errors .= "The username is too long. ";

	if( strlen($errors) > 0 )
		return $errors;

	// Get the hash
	$hash = hash( "sha256", $user["password1"] );
	
	// Add the randomizer
	$salt = createSalt();
	$hash = hash( "sha256", $salt . $hash );

	$_POST["salt"] = $salt;
	$_POST["hash"] = $hash;

	// ...and make sure someone isn't trying to hack the db.
	$username = mysql_real_escape_string($user["username"]);
	$query = "INSERT INTO " . $_POST["dbprefix"] . "main ( username, password, salt, name, street, city, country, phone, email )
		VALUES ( '$username', '$hash', '$salt', '$user[fullname]', '$user[street]', '$user[city]', '$user[country]', '$user[phone]', '$user[email]' );";

	return mysql_query( $query, $link );
}

function editConfigFile( $host, $schema, $prefix, $user, $pass ){
	// Select file
    $filename = "config.php";
	
	// Patterns
	$patterns = Array();
	$patterns[] = '/(\s+)(\$)(dbhost)(=")(\w+)*(";)/';
	$patterns[] = '/(\s+)(\$)(dbuser)(=")(\w+)*(";)/';
	$patterns[] = '/(\s+)(\$)(dbpass)(=")(\w+)*(";)/';
	$patterns[] = '/(\s+)(\$)(dbname)(=")(\w+)*(";)/';
	$patterns[] = '/(\s+)(\$)(dbprefix)(=")(\w+)(";)/';

	// Replacements
	$replacements = Array();
	$replacements[] = '$1$2$3$4'.$host.'$6';
	$replacements[] = '$1$2$3$4'.$user.'$6';
	$replacements[] = '$1$2$3$4'.$pass.'$6';
	$replacements[] = '$1$2$3$4'.$schema.'$6';
	$replacements[] = '$1$2$3$4'.$prefix.'$6';
	
	// Edit file
	$config = file_get_contents($filename);
	$newconfig = preg_replace($patterns, $replacements, $config);
	
	$file = fopen( $filename, 'w' );

	return (fwrite( $file, $newconfig ) === false ? false : true );
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
			print '<link rel="stylesheet" type="text/css" href="./css/desktop.css">';
			print '<link rel="stylesheet" type="text/css" href="./css/install.css">';
		}else{
			print '<link rel="stylesheet" type="text/css" href="./css/desktop.css">';
			print '<link rel="stylesheet" type="text/css" href="./css/install.css">';
		}
	?>

	<script type="text/javascript" src="./scripts/jquery-1.7.min.js"></script>
	<script type="text/javascript" src="./scripts/index.js"></script>
</head>

<body>

<!-- For debugging purposes -->
<?php
//print('<pre>');
//print_r($_SESSION);
//print('</pre>');
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
		if( !isset($_POST["create"]) ){
			// Enter login details
			print '<tr class="headingrow"><td colspan="2"><p class="heading">Create your user</p></td></tr>';
			print '<tr><td>login:</td><td><input type="text" name="username" placeholder="username" /></td></tr>';
			print '<tr><td>password:</td><td><input type="password" name="password1" placeholder="password" /></td></tr>';
			print '<tr><td>password (again):</td><td><input type="password" name="password2" placeholder="password again" /></td></tr>';
			// Enter personal details
			print '<tr class="headingrow"><td colspan="2"><p class="heading">Enter your personal details</p></td></tr>';
			print '<tr><td>full name:</td><td><input type="text" name="fullname" placeholder="John Doe" /></td></tr>';
			print '<tr><td>street:</td><td><input type="text" name="street" placeholder="My street 1A" /></td></tr>';
			print '<tr><td>city:</td><td><input type="text" name="city" placeholder="Atlantis" /></td></tr>';
			print '<tr><td>country:</td><td><input type="text" name="country" placeholder="A lost empire" /></td></tr>';
			print '<tr><td>phone:</td><td><input type="text" name="phone" placeholder="+007 007 007" /></td></tr>';
			print '<tr><td>email:</td><td><input type="email" name="email" placeholder="john@doe.org" /></td></tr>';
			// Enter server details
			print '<tr class="headingrow"><td colspan="2"><p class="heading">Enter database details</p></td></tr>';
			print '<tr><td>mysql server:</td><td><input type="text" name="dbhost" placeholder="localhost" /></td></tr>';
			print '<tr><td>database:</td><td><input type="text" name="dbschema" placeholder="schema" /></td></tr>';
			print '<tr><td>table prefix:</td><td><input type="text" name="dbprefix" placeholder="prefix" value="cv_"/></td></tr>';
			print '<tr><td>db user:</td><td><input type="text" name="dbusername" placeholder="username" /></td></tr>';
			print '<tr><td>db password:</td><td><input type="password" name="dbpassword" placeholder="password" /></td></tr>';
			print '<tr><td colspan="2"><input class="button" name="create" type="submit" value="Create"></td></tr>';
		}else{
			// SQL Server values
			//$dbhost = $_POST["dbhost"];
			//$dbschema = $_POST["dbschema"];
			//$dbuser = $_POST["dbusername"];
			//$dbpass = $_POST["dbpassword"];
			
			//$connection = mysql_connect( $dbhost, $dbuser, $dbpass ) or die ( "#1 Error connecting to database: " . mysql_error() );
			//mysql_select_db( $dbschema, $connection ) or die ( "#2 Error selecting schema: " . mysql_error() );

			$link = connect_to_db($_POST["dbhost"], $_POST["dbusername"], $_POST["dbpassword"], $_POST["dbschema"] );

			if( $link != true )
				die( "Failed to connect to database" );

			$dbprefix = $_POST["dbprefix"];
			
			// Execute the SQL script
			$sqlfile = file_get_contents( "database.sql" );
			$queryArr = explode( ";", $sqlfile );
			$pattern = "/cv_/";
			
			$sqlErrorCode = 0;

			foreach ($queryArr as $query) {
				if( strlen($query)>3 && substr( ltrim($query), 0, 2 ) != '/*' ){
					$result = mysql_query( preg_replace($pattern, $dbprefix, $query), $link );
					if ( !$result ) {
						$sqlErrorCode = mysql_errno();
						$sqlErrorText = mysql_error();
						$sqlStmt = $query;
						break;
					}
				}
			}
			
			if( $sqlErrorCode == 0 ){
				// Add the user to the database
				$user = array();
				$user["username"] = $_POST["username"];
				$user["password1"] = $_POST["password1"];
				$user["password2"] = $_POST["password2"];
				$user["fullname"] = $_POST["fullname"];
				$user["street"] = $_POST["street"];
				$user["city"] = $_POST["city"];
				$user["country"] = $_POST["country"];
				$user["phone"] = $_POST["phone"];
				$user["email"] = $_POST["email"];

				$reg = register( $link, $user); //, $userlogin, $userpw1, $userpw2, $fullname, $street, $city, $country, $phone, $email );
				
				if( $reg !== true ){
					// User registration failed, delete the created tables and abort.
					$del = deleteTables();
					if( $del !== true )
						die( printError($reg) . ". " . printError($del) );

					die( printError($reg) );
				}
			
				// Finally, edit the config file.
				if( !editConfigFile( $_POST["dbhost"], $_POST["dbschema"], $dbprefix, $_POST["dbusername"], $_POST["dbpassword"] ) ){
					if( !chmod( "config.php", 0777 ) ){
						$errmsg = 'Failed to edit file "config.php" - I tried changing the permission myself but failed. Could you help me please? Set the "config.php" file to mode 0777 and then press refresh please';

						$del = deleteTables();
						if( $del !== true )
							die( printError($errmsg) . ". " . printError($del) );

						die( printError($errmsg) );
					}
				}

				// If we get here without any errors, we're all set! Almost...
				if( !chmod("config.php", 0755) ){
					$errmsg = 'Installation was a success, but when I tried changing the "config.php" file back to more appropriate permissions I failed. Could you give me a hand please, set it to mode 0755 please.';

					die( printError($errmsg) );
				}
			}else{
				// TODO print errors in the footer instead?
				$msg = "An error occured during installation!. ";
				$msg .= "Error code: $sqlErrorCode. ";
				$msg .= "Error text: $sqlErrorText. ";
				$msg .= "Statement:. $sqlStmt. ";
				die( printError($msg) );
			}
			
			// Close the connection.
			mysql_close();
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
	
