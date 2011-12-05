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
		return '<tr><td>'.$row["id"].'</td><td>'.$row["name"].'</td><td>'.$row["date"].'</td><td>'.$row["description"].'</td><td><a href="admin.php?mod='.$this->Name().'&del='.$row["id"].'">del</a></td></tr>';
	}
	
	function Description(){
		return "<p>This module lets the user administer his/her projects.</p>";
	}
	function Content(){
		if( Login::LoggedIn() ){
			// List of projects
			$query = "SELECT * FROM cv_projects";
			$result = mysql_query( $query ) or die ( mysql_error() );
			
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
			$buffer .= '<tr class="banded"><td>Date:</td><td><input type="text" name="date" maxlength="32"></td></tr>';
			$buffer .= '<tr><td>Tags:</td><td><input type="text" name="tags" maxlength="128"></td></tr>';
			$buffer .= '<tr class="banded"><td>Select thumbnail (only .PNG):</td><td><input type="file" name="file" id="file" /></td></tr>';
			$buffer .= '<tr><td>Description:</td><td><textarea rows="5" cols="47" name="description"></textarea></td></tr>';
			$buffer .= '<tr class="banded"><td colspan="2"><input name="' . $this->Name() . '" type="submit" value="Save new project" /></td></tr>';
			
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
				$id = mysql_real_escape_string($_GET["del"]);
				
				// Delete directory
				$query = "SELECT name FROM cv_projects WHERE id='$id'";
				$result = mysql_query( $query ) or die ( mysql_error() );
				$row = mysql_fetch_assoc($result);
				$this->delete( "./media/".$row["name"] );
				
				// Delete database entry
				$query = "DELETE FROM cv_projects WHERE id='$id'";	
				$result = mysql_query( $query ) or die ( mysql_error() );
				
				// Redirect:
				header("Location: admin.php?mod=".$this->Name());
			}
		}
	}
	
	function POST(){
		if( Login::LoggedIn() ){
			// Add a new project
			$query = "INSERT INTO cv_projects (name, date, tags, description)
					  VALUES ('$_POST[name]', '$_POST[date]', '$_POST[tags]', '$_POST[description]')";
			
			mysql_query( $query ) or die ( mysql_error() );
			
			// Create the project directory under the media folder
			mkdir( "./media/" . $_POST["name"] );
			
			// Upload the icon file (200x200px thumb for the frontpage)
			// If the file is too big or too small, resize it to 200x200.
			// Also create a desaturated version of the image.
			// Rename the image to icon.png and 
			if( (($_FILES["file"]["type"] == "image/png")) && ($_FILES["file"]["size"] < 800000)){
				if ($_FILES["file"]["error"] > 0){
					$this->error[] = "Error: " . $_FILES["file"]["error"];
				}else{						
					if( file_exists("upload/" . $_FILES["file"]["name"]) ){
						$this->error[] = $_FILES["file"]["name"] . " already exists. ";
					}else{
						move_uploaded_file( $_FILES["file"]["tmp_name"], "media/".$_POST["name"]."/".$_FILES["file"]["name"] );
						rename( "media/".$_POST["name"]."/".$_FILES["file"]["name"], "./media/".$_POST["name"]."/icon.png" );
						
						$image = new SimpleImage();						
						$image->load( "./media/".$_POST["name"]."/icon.png" );
						$image->resize( 200, 200 );
						$image->save( "./media/".$_POST["name"]."/icon.png" );
						
						$imagegray = ImageCreateFromString(file_get_contents('./media/'.$_POST["name"].'/icon.png'));
						ImageFilter($imagegray, IMG_FILTER_GRAYSCALE);
						ImagePNG($imagegray, './media/'.$_POST["name"].'/icon_gray.png');
						ImageDestroy($imagegray);
					}
				}
			}else{
				$this->error[] = "Invalid file";
			}
		}
	}
}

?>