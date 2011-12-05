<?php

/********************************************************\
 * File: 	UploadImage.php								*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-12-05									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
  Description:	Upload image admin module.				*
\********************************************************/

include_once("CvModule.php");
include_once("SimpleImage.php");

class UploadImage extends CvModule {
	
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
		return "<p>Upload a file to the selected project. Note! The file will be resized to 810x550 to suit the presentation area, you might need to apply some sort of image manipulation on the image before you upload it for best results.</p>";
	}
	function Content(){
		if( Login::LoggedIn() ){
			global $link, $dbprefix;
			
			// TODO list all projects in some sort of expandable/contractable view... with ability to delete files
			// Probably needs work with JQuery, and then we need to add a script loader similar to the css loader in admin.php.
			
			// Add new file to project form
			$buffer = '<form acton="$_SERVER[PHP_SELF]" method="POST" enctype="multipart/form-data"><table><tbody>';
			$buffer .= '<tr><td>Save to project:</td><td><select name="project">';
			$query = "SELECT name FROM " . $dbprefix . "projects";
			$result = mysql_query( $query, $link ) or die ( mysql_error() );
			while( $row = mysql_fetch_assoc($result) ){
				$buffer .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
			}
			$buffer .= '</select></td></tr>';
			$buffer .= '<tr class="banded"><td>Select image (only .PNG):</td><td><input type="file" name="file" id="file" /></td></tr>';
			$buffer .= '<tr class="banded"><td colspan="2"><input name="' . $this->Name() . '" type="submit" value="Save image to project" /></td></tr>';
			
			// TODO icon file upload! 
			$buffer .= '</tbody></table></form>';
						
			return $buffer;
		}
		return "";
	}
	
	function GET(){
		if( Login::LoggedIn() ){
			// TODO, delete image!
		}
	}
	
	function POST(){
		if( Login::LoggedIn() ){
			if( (($_FILES["file"]["type"] == "image/gif")
			|| ($_FILES["file"]["type"] == "image/jpeg")
			|| ($_FILES["file"]["type"] == "image/pjpeg")
			|| ($_FILES["file"]["type"] == "image/png"))
			&& ($_FILES["file"]["size"] < 5000000)){
				if ($_FILES["file"]["error"] > 0){
					$this->error[] = "Error: " . $_FILES["file"]["error"];
				}else{						
					if( file_exists("upload/" . $_FILES["file"]["name"]) ){
						$this->error[] = $_FILES["file"]["name"] . " already exists. ";
					}else{
						move_uploaded_file( $_FILES["file"]["tmp_name"], "media/".$_POST["project"]."/".$_FILES["file"]["name"] );
						rename( "media/".$_POST["project"]."/".$_FILES["file"]["name"], "./media/".$_POST["project"]."/".$_FILES["file"]["name"] );
						
						$image = new SimpleImage();						
						$image->load( "./media/".$_POST["project"]."/".$_FILES["file"]["name"] );
						$image->resize( 810, 550 );
						$image->save( "./media/".$_POST["project"]."/".$_FILES["file"]["name"] );
					}
				}
			}else{
				$this->error[] = "Invalid file";
			}
		}
	}
}

?>