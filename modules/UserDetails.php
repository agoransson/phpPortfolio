<?php

/********************************************************\
 * File: 	UserDetails.php								*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-12-04									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	User details admin module.				*
\********************************************************/

include_once("CvModule.php");

class UserDetails extends CvModule {

	function Description(){
		return "<p>This module lets the user change his/her contact details, it requires the current password for changes to be commited.</p>";
	}
	
	// Creates an form for editing user details in the database. Anything but the username and password, for now...
	function Content(){
		if( Login::LoggedIn() ){
			global $link, $dbprefix;
		
			$query = "SELECT street, city, country, phone, email FROM " . $dbprefix . "main WHERE username = '$_SESSION[username]'";
			$result = mysql_query( $query, $link ) or die ( mysql_error() );
			
			$buffer = '<form acton="$_SERVER[PHP_SELF]" method="POST"><table><tbody>';

			while( $row = mysql_fetch_assoc($result) ){
				$numcols = count($row);
				
				$buffer .= '<tr><td>Username:</td><td>' . $_SESSION["username"] . '</td></tr>';
				$counter = 0;
				foreach( $row as $key => $val ){
					$buffer .= '<tr'.(($counter%2) ? '' : ' class="banded"').'><td>'.$key.':</td><td><input type="text" name="'.$key.'" value="'.$val.'"/></td></tr>';
					//$buffer .= '<tr' . ( ($counter%2) ? '' : ' class="banded"' ) . '><td>'.$key.':</td><td><input type="text" name="street" value="'.$val.'"/></td></tr>';
					$counter++;
				}
				$buffer .= '<tr><td>Password:</td><td><input type="password" name="password" placeholder="password"/></td></tr>';
				$buffer .= '<tr><td colspan="2"><input name="' . $this->Name() . '" type="submit" value="Save user details" /></td></tr>';
			}
			
			$buffer .= '</tbody></table></form>';
			
			return $buffer;
		}
	}
	
	function POST(){
		if( Login::LoggedIn() ){
			global $link, $dbprefix;
			
			$load = "SELECT password, salt FROM " . $dbprefix . "main WHERE username = '$_SESSION[username]'";
			$result = mysql_query( $load, $link ) or die ( mysql_error() );
			$row = mysql_fetch_assoc( $result );
			
			$save = "UPDATE cv_main
			SET street='$_POST[street]', city='$_POST[city]', country='$_POST[country]', phone='$_POST[phone]', email='$_POST[email]'
			WHERE username='$_SESSION[username]'";
			
			$hash = hash( "sha256", $row["salt"] . hash("sha256", $_POST["password"]) );
			if( $hash != $row["password"] )
				$this->error[] = "Wrong password!";
			else
				mysql_query( $save ) or die ( mysql_error() );
		}
	}
}

?>