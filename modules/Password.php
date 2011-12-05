<?php

/********************************************************\
 * File: 	Password.php								*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-12-04									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Password admin module.					*
\********************************************************/

include_once("CvModule.php");

class Password extends CvModule {

	function Description(){
		return "<p>This module lets the user change his/her password in the system. It requires the current password for changes to take place.</p>";
	}
	
	// Creates an form for editing user details in the database. Anything but the username and password, for now...
	function Content(){
		global $link, $dbprefix;
		
		$query = "SELECT password, salt FROM " . $dbprefix . "main WHERE username = '$_SESSION[username]'";
		$result = mysql_query( $query, $link ) or die ( mysql_error() );
		
		$buffer = '<form acton="$_SERVER[PHP_SELF]" method="POST"><table><tbody>';
		while( $row = mysql_fetch_assoc($result) ){			
			$buffer .= '<tr><td>Username:</td><td>' . $_SESSION["username"] . '</td></tr>';
			$buffer .= '<tr class="banded"><td>Old password:</td><td><input type="password" name="oldpassword" placeholder="old password"/></td></tr>';
			$buffer .= '<tr><td>New password:</td><td><input type="password" name="newpassword1" placeholder="new password"/></td></tr>';
			$buffer .= '<tr class="banded"><td>New password again:</td><td><input type="password" name="newpassword2" placeholder="new password again"/></td></tr>';
			$buffer .= '<tr><td colspan="2"><input name="' . get_class($this) . '" type="submit" value="Save user details" /></td></tr>';
		}
		$buffer .= '</tbody></table></form>';
		
		return $buffer;
	}
	
	function POST(){
		global $link, $dbprefix;
		
		if( $_POST["newpassword1"] != $_POST["newpassword2"] ){
			$this->error[] = "New passwords don't match!";
			return false;
		}else{
			$load = "SELECT password, salt FROM " . $dbprefix . "main WHERE username = '$_SESSION[username]'";
			$result = mysql_query( $load, $link ) or die ( mysql_error() );
			$row = mysql_fetch_assoc( $result );
			
			// Get the new password hash
			$newhash = hash( "sha256", $_POST["newpassword1"] );
			
			// Add the randomizer
			$newhash = hash( "sha256", $row["salt"] . $newhash );
			
			$save = "UPDATE cv_main
			SET password='$newhash'
			WHERE username='$_SESSION[username]'";
						
			// Make sure the old password was correct!
			$hash = hash( "sha256", $row["salt"] . hash("sha256", $_POST["oldpassword"]) );
			if( $hash != $row["password"] )
				$this->error[] = "Wrong password!";
			else
				mysql_query( $save ) or die ( mysql_error() );
		}
	}
}

?>