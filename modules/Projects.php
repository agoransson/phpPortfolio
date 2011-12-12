<?php

/********************************************************\
 * File: 	Projects.php								*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-12-04									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Projects admin module.					*
\********************************************************/

include_once("CvModule.php");
include_once("SimpleImage.php");

class Projects extends CvModule {
	
	// Recursively delete a folder with contents
    private function delete( $file ){
        if( is_file($file) ){
            return @unlink($file);
        }else if( is_dir($file) ){
            $scan = glob( rtrim($file,'/') . '/*' );
            foreach( $scan as $index=>$path ){
                $this->delete($path);
            }
            return @rmdir($file);
        }
    }
	
	// Custom function for this module to retrieve a row from the table.
	private function getTableRow( $row ){
		return '<tr><td>'.$row["id"].'</td><td>'.$row["name"].'</td><td>'.$row["year"].'</td><td>'.$row["description"].'</td><td><a href="admin.php?mod='.$this->Name().'&del='.$row["id"].'">del</a></td></tr>';
	}
	
	function Description(){
		return "<p>This module lets the user administer his/her projects.</p>";
	}
	function Content(){
		if( Login::LoggedIn() ){
			global $link, $dbprefix;
		
			// List of projects
			$query = "SELECT * FROM " . $dbprefix . "projects";
			$result = mysql_query( $query, $link ) or die ( mysql_error() );
			
			$buffer = '<table>';
			$buffer .= '<tbody>';
			while( $row = mysql_fetch_assoc($result) ){
				$buffer .= $this->getTableRow($row);
			}
			$buffer .= '</tbody>';
			$buffer .= '</table>';
			
			// Add new project form
			$buffer .= '<form acton="$_SERVER[PHP_SELF]" method="POST" enctype="multipart/form-data"><table><tbody>';
			$buffer .= '<tr><td>Project Name:</td><td><input type="text" name="name" maxlength="32"></td></tr>';
			$buffer .= '<tr class="banded"><td>Date:</td><td><input type="text" name="year" maxlength="32"></td></tr>';
			$buffer .= '<tr><td>Tags:</td><td><input type="text" name="tags" maxlength="128"></td></tr>';
			$buffer .= '<tr class="banded"><td>Select thumbnail (only .PNG):</td><td><input type="file" name="icon" id="icon" /></td></tr>';
			$buffer .= '<tr><td>Desaturated version:</td><td><input type="file" name="icon_gray" id="icon_gray" /></td></tr>';
			$buffer .= '<tr class="banded"><td>Description:</td><td><textarea rows="5" cols="47" name="description"></textarea></td></tr>';
			$buffer .= '<tr><td colspan="2"><input name="' . $this->Name() . '" type="submit" value="Save new project" /></td></tr>';
			
			// TODO icon file upload! 
			$buffer .= '</tbody></table></form>';
						
			return $buffer;
		}
		return "";
	}
	
	function GET(){
		if( Login::LoggedIn() ){
			// Delete project, this should be safe since the admin.php requires to be logged in!
			if( isset($_GET["del"]) ){
				global $link, $dbprefix;
			
				$id = mysql_real_escape_string($_GET["del"]);
				
				// Delete directory
				$query = "SELECT name FROM " . $dbprefix . "projects WHERE id='$id'";
				$result = mysql_query( $query, $link ) or die ( mysql_error() );
				$row = mysql_fetch_assoc($result);
				$this->delete( "./media/".$row["name"] );
				
				// Delete database entry
				$query = "DELETE FROM " . $dbprefix . "projects WHERE id='$id'";	
				$result = mysql_query( $query, $link ) or die ( mysql_error() );
				
				// Redirect:
				header("Location: admin.php?mod=".$this->Name());
			}
		}
	}
	
	function POST(){
		if( Login::LoggedIn() ){
			global $link, $dbprefix;
			
			// Add a new project
			$query = "INSERT INTO " . $dbprefix . "projects (name, year, tags, description)
					  VALUES ('$_POST[name]', '$_POST[year]', '$_POST[tags]', '$_POST[description]')";
			
			// TODO: This should only execute if all other things are a success... move this later...
			mysql_query( $query, $link );
			
			// Create the project directory under the media folder
			$ret = mkdir( "./media/" . $_POST["name"] );
			if( !$ret )
				$this->error[] = "I tried to create the project folder, but failed. Could you set the permissions on ./media/ folder to 775 please?";
			else{
				// Upload the icon file (200x200px thumb for the frontpage)
				// If the file is too big or too small, resize it to 200x200.
				// Also create a desaturated version of the image.
				// Rename the image to icon.png and 
				if( ($_FILES["icon"]["type"] == "image/png") && ($_FILES["icon"]["size"] < 800000)){
					$filename = $_FILES["icon"]["name"];
					$path = "media/".$_POST["name"]."/";
					$iconname = "icon.png";

					if ($_FILES["icon"]["error"] > 0){
						$this->error[] = "Error: " . $_FILES["icon"]["error"];
					}else{						
						if( file_exists("upload/" . $filename) ){
							$this->error[] = $filename . " already exists. ";
						}else{
							move_uploaded_file( $_FILES["icon"]["tmp_name"], $path.$filename );
							rename( $path.$filename, $path.$iconname );
						}
					}
				}else{
					$this->error[] = "Invalid file in icon";
				}
				// Grayscale version
				if( ($_FILES["icon_gray"]["type"] == "image/png") && ($_FILES["icon_gray"]["size"] < 800000)){
					$filename = $_FILES["icon_gray"]["name"];
					$path = "media/".$_POST["name"]."/";
					$iconname = "icon_gray.png";

					if ($_FILES["icon_gray"]["error"] > 0){
						$this->error[] = "Error: " . $_FILES["icon_gray"]["error"];
					}else{						
						if( file_exists("upload/" . $filename) ){
							$this->error[] = $filename . " already exists. ";
						}else{
							move_uploaded_file( $_FILES["icon_gray"]["tmp_name"], $path.$filename );
							rename( $path.$filename, $path.$iconname );
						}
					}
				}else{
					$this->error[] = "Invalid file in icon";
				}

			}
		}
	}
}

?>
