<?php

/********************************************************\
 * File: 	CV.php										*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-12-14									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
  Description:	CV admin module.						*
\********************************************************/

include_once("CvModule.php");

class Cv extends CvModule {
	
	function Description(){
		return "<p>Asd</p>";
	}

	function Content(){
		if( Login::LoggedIn() ){
			global $link, $dbprefix;

			print "<pre>";
			print_r( $_POST );
			print "</pre>";

			// Ambitions
			$query = "SELECT ambitions FROM " . $dbprefix . "main";
			$result = mysql_query($query, $link);
			$text = "";

			$buffer = '<form acton="$_SERVER[PHP_SELF]" method="POST" enctype="multipart/form-data"><table><tbody>';
			$buffer .= '<tr class="banded"><td colspan="2"><textarea name="amb_text" style="width: 100%; border: 0;" rows="6" placeholder="Write a new ambitions text here"></textarea></td></tr>';
			$buffer .= '<tr><td colspan="2"><input name="' . $this->Name() . '" type="submit" value="Save new ambitions text" /></td></tr>'; 
			$buffer .= '</tbody></table></form>';

			// Educations
			$buffer .= '<form acton="$_SERVER[PHP_SELF]" method="POST" enctype="multipart/form-data"><table><tbody>';
			$buffer .= '<tr class="banded"><td>School/institution:</td><td colspan="3"><input type="text" name="edu_school" maxlength="128" placeholder="Lund School of Technology"></td></tr>';
			$buffer .= '<tr><td>Years:</td><td><input type="text" name="edu_start" maxlength="128" placeholder="2006"></td><td>to</td>
										   <td><input type="text" name="edu_end" maxlength="128" placeholder="2009"></td></tr>';
			$buffer .= '<tr class="banded"><td>Education title:</td><td colspan="3"><input type="text" name="edu_title" maxlength="128" placeholder="M.Sc. Computer Science"></td></tr>';
			$buffer .= '<tr><td>Thesis title:</td><td colspan="3"><input type="text" name="edu_thesis" maxlength="256" placeholder="Architectural Styles and the Design of Network-based Software Architectures"></td></tr>';
			$buffer .= '<tr class="banded"><td colspan="4"><input name="' . $this->Name() . '" type="submit" value="Save new education" /></td></tr>'; 
			$buffer .= '</tbody></table></form>';

			// Professional experience
			$buffer .= '<form acton="$_SERVER[PHP_SELF]" method="POST" enctype="multipart/form-data"><table><tbody>';
			$buffer .= '<tr class="banded"><td>Title:</td><td colspan="3"><input type="text" name="pro_title" maxlength="128" placeholder="Project manager"></td></tr>';
			$buffer .= '<tr><td>Department:</td><td colspan="3"><input type="text" name="pro_department" maxlength="128" placeholder="R&D"></td></tr>';
			$buffer .= '<tr class="banded"><td>Organization:</td><td colspan="3"><input type="text" name="pro_organization" maxlength="128" placeholder="Sony Ericsson"></td></tr>';
			$buffer .= '<tr><td>Years:</td><td><input type="text" name="pro_start" maxlength="128" placeholder="2009"></td><td>to</td>
										   <td><input type="text" name="pro_end" maxlength="128" placeholder="2011"></td></tr>';
			$buffer .= '<tr class="banded"><td colspan="4"><textarea name="pro_description" style="width: 100%; border: 0;" rows="6" placeholder="Description of assignment"></textarea></td></tr>';
			$buffer .= '<tr><td colspan="4"><input name="' . $this->Name() . '" type="submit" value="Save new professional experience" /></td></tr>'; 
			$buffer .= '</tbody></table></form>';

			// (Published) works
			$buffer .= '<form acton="$_SERVER[PHP_SELF]" method="POST" enctype="multipart/form-data"><table><tbody>';
			$buffer .= '<tr class="banded"><td>Publisher:</td><td><input type="text" name="pub_name" maxlength="128" placeholder="Prentice Hall"></td></tr>';
			$buffer .= '<tr><td>Conference/main work:</td><td><input type="text" name="pub_mainwork" maxlength="128" placeholder="Filthy Rich Clients"></td></tr>';
			$buffer .= '<tr class="banded"><td>Country:</td><td><input type="text" name="pub_country" maxlength="128" placeholder="United States"></td></tr>';
			$buffer .= '<tr><td>Year:</td><td><input type="text" name="pub_year" maxlength="128" placeholder="2007"></td></tr>';
			$buffer .= '<tr class="banded"><td>Title:</td><td><input type="text" name="pub_title" maxlength="256" placeholder="Only use this if you wrote a specific part of the work."></td></tr>';
			$buffer .= '<tr><td colspan="2"><textarea name="pub_description" style="width: 100%; border: 0;" rows="6" placeholder="Description of published work"></textarea></td></tr>';
			$buffer .= '<tr class="banded"><td colspan="4"><input name="' . $this->Name() . '" type="submit" value="Save new published work" /></td></tr>'; 
			$buffer .= '</tbody></table></form>';

			// Interests, skills, other information...
			$buffer .= '<form acton="$_SERVER[PHP_SELF]" method="POST" enctype="multipart/form-data"><table><tbody>';

			$buffer .= '<tr class="banded"><td>Name:</td><td><input type="text" name="other_name" maxlength="128" placeholder="Language, exhibition, or other interesting skills/experience"></td></tr>';
			$buffer .= '<tr><td colspan="2"><textarea name="other_description" style="width: 100%; border: 0;" rows="6" placeholder="Description of skill or interest"></textarea></td></tr>';

			$buffer .= '<tr class="banded"><td colspan="4"><input name="' . $this->Name() . '" type="submit" value="Save new skill" /></td></tr>'; 
			$buffer .= '</tbody></table></form>';

			return $buffer;
		}
		return "";
	}
	
	function GET(){
		if( Login::LoggedIn() ){
			// todo delete row?
		}
	}
	
	function POST(){
		if( Login::LoggedIn() ){
			global $link, $dbprefix;

			switch($_POST["Cv"]){
				case "Save new ambitions text":
					$text = $_POST["amb_text"];
					// TODO this might not be the best way, should probably get the right id from the database too...
					$query = "UPDATE " . $dbprefix . "main
							  SET ambitions='".mysql_real_escape_string($text)."' WHERE id=1";
					if( !mysql_query($query,$link) )
						$this->error[] = "Failed to update ambitions: " . mysql_error();
					break;
				case "Save new education":
					$school = $_POST["edu_school"];
					$start = $_POST["edu_start"];
					$end = $_POST["edu_end"];
					$title = $_POST["edu_title"];
					$thesis = $_POST["edu_thesis"];
					$query = "INSERT INTO " . $dbprefix . "education (school, start, end, degree, thesisname) 
							  VALUES('$school', '$start', '$end', '$title', '$thesis')";
					if( !mysql_query($query,$link) )
						$this->error[] = "Failed to add education: " . mysql_error();
					break;
				case "Save new professional experience":
					// TODO add new prof. experience row
					break;
				case "Save new published work":
					// TODO
					break;
				case "Save new skill":
					break;
			}
		}
	}
}

?>
