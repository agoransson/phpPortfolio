<?php

/********************************************************\
 * File: 	Login.php									*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-12-04									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Login module.							*
\********************************************************/

include_once("CvModule.php");

class Login extends CvModule {
	
	
	// Attempt a login to the system
	private function attemptLogIn($username, $password){
		global $link, $dbprefix;
	
		$username = mysql_real_escape_string($username);

		$query = "SELECT password, salt FROM " . $dbprefix . "main WHERE username = '$username';";
		$result = mysql_query( $query, $link ) or die ( mysql_error() );

		if( mysql_num_rows($result) < 1 ){
			$this->error[] = "No such user";
				
			return false;
		}

		$row = mysql_fetch_array( $result, MYSQL_ASSOC );
		$hash = hash( "sha256", $row["salt"] . hash("sha256", $password) );
		if( $hash != $row["password"] ){
			$this->error[] = "Wrong password";
			
			return false;
		}
		
		// Logged in
		return true;
	}
	
	private static function Logout(){
		unset( $_SESSION["loggedIn"] );
		unset( $_SESSION["username"] );
		header( "Location: admin.php" );
	}
	
	public static function LoggedIn(){
		return ( isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true );
	}

	function Description(){
		return "";
	}
	
	function Menu(){
		// We don't want the login to appear in the list of modules, overriding with an empty function will achieve this!
	}
	
	// Creates an form for editing user details in the database. Anything but the username and password, for now...
	// When submitting forms it's important that we place the Class Name (f.ex. using $this->Name()) as the name attribute
	function Content(){
		if( Login::LoggedIn() ){
			// Should probably relay to another module here...
		}else{
			$buffer = '<form acton="$_SERVER[PHP_SELF]" method="POST"><table><tbody>';
			$buffer .= '<tr><td>Username:</td><td><input type="text" name="username" placeholder="username"/></td></tr>';
			$buffer .= '<tr><td>Password:</td><td><input type="password" name="password" placeholder="password"/></td></tr>';
			$buffer .= '<tr><td colspan="2"><input name="' . $this->Name() . '" type="submit" value="Login" /></td></tr>';
			$buffer .= '</tbody></table></form>';
			
			return $buffer;
		}
	}
	
	function POST(){
		if( !$this->attemptLogIn($_POST["username"], $_POST["password"]) ){
			$this->error[] = "Wrong username and/or password! Try again.";
		}else{
			$_SESSION["loggedIn"] = true;
			$_SESSION["username"] = $_POST["username"];
			header( "Location: admin.php" );
		}
	}
	
	function GET(){
		if( isset($_GET["logout"]) && $_GET["logout"] == true ){
			Login::Logout();
		}
	}
}

?>