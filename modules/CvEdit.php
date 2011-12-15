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

class CvEdit extends CvModule {
	
	function Description(){
		return "<p>This plugin allows the user to administer the CV part of the website.</p>";
	}

	function Content(){
		if( Login::LoggedIn() ){
			global $link, $dbprefix;
			
			$formstart = '<form acton="$_SERVER[PHP_SELF]" method="POST" enctype="multipart/form-data"><table><tbody>';
			$formend = '</tbody></table></form>';

			// Ambitions
			$query = "SELECT ambitions FROM " . $dbprefix . "main ORDER BY id DESC LIMIT 1";
			$result = mysql_query($query, $link);
			$row = mysql_fetch_row($result);

			$buffer = $formstart;
			$buffer .= '<tr class="banded"><td colspan="2">
							<textarea name="amb_text" style="width: 100%; border: 0;" rows="6" placeholder="Write a new ambitions text here">'.$row[0].'</textarea></td></tr>
						<tr><td colspan="2"><input name="' . $this->Name() . '" type="submit" value="Save new ambitions text" /></td></tr>'; 
			$buffer .= $formend;

			// Educations
			$buffer .= $formstart;
			$buffer .= '<tr class="banded"><td>School/institution:</td><td colspan="3"><input type="text" name="edu_school" maxlength="128" placeholder="Lund School of Technology"></td></tr>
						<tr><td>Years:</td><td><input type="text" name="edu_start" maxlength="128" placeholder="2006"></td><td>to</td>
										   <td><input type="text" name="edu_end" maxlength="128" placeholder="2009"></td></tr>
						<tr class="banded"><td>Education title:</td><td colspan="3"><input type="text" name="edu_title" maxlength="128" placeholder="M.Sc. Computer Science"></td></tr>
						<tr><td>Thesis title:</td>
							<td colspan="3"><input type="text" name="edu_thesis" maxlength="256" placeholder="Architectural Styles and the Design of Network-based Software Architectures"></td>
						</tr>
						<tr class="banded"><td colspan="4"><input name="' . $this->Name() . '" type="submit" value="Save new education" /></td></tr>'; 
			$buffer .= $formend;

			// Professional experience
			$buffer .= $formstart;
			$buffer .= '<tr class="banded"><td>Title:</td><td colspan="3"><input type="text" name="pro_title" maxlength="128" placeholder="Project manager"></td></tr>
						<tr><td>Department:</td><td colspan="3"><input type="text" name="pro_department" maxlength="128" placeholder="R&D"></td></tr>
						<tr class="banded"><td>Organization:</td><td colspan="3"><input type="text" name="pro_organization" maxlength="128" placeholder="Sony Ericsson"></td></tr>
						<tr><td>Years:</td><td><input type="text" name="pro_start" maxlength="128" placeholder="2009"></td><td>to</td>
										   <td><input type="text" name="pro_end" maxlength="128" placeholder="2011"></td></tr>
						<tr class="banded">
							<td colspan="4"><textarea name="pro_description" style="width: 100%; border: 0;" rows="6" placeholder="Description of assignment"></textarea></td>
						</tr>
						<tr><td colspan="4"><input name="' . $this->Name() . '" type="submit" value="Save new professional experience" /></td></tr>';
			$buffer .= $formend;

			// (Published) works
			$buffer .= $formstart;
			$buffer .= '<tr class="banded"><td>Publisher:</td><td><input type="text" name="pub_name" maxlength="128" placeholder="Prentice Hall"></td></tr>
						<tr><td>Conference/main work:</td><td><input type="text" name="pub_mainwork" maxlength="128" placeholder="Filthy Rich Clients"></td></tr>
						<tr class="banded"><td>Country:</td><td><input type="text" name="pub_country" maxlength="128" placeholder="United States"></td></tr>
						<tr><td>Year:</td><td><input type="text" name="pub_year" maxlength="128" placeholder="2007"></td></tr>
						<tr class="banded">
							<td>Title:</td><td><input type="text" name="pub_title" maxlength="256" placeholder="Only use this if you wrote a specific part of the work."></td>
						</tr>
						<tr><td colspan="2"><textarea name="pub_description" style="width: 100%; border: 0;" rows="6" placeholder="Description of published work"></textarea></td></tr>
						<tr class="banded"><td colspan="4"><input name="' . $this->Name() . '" type="submit" value="Save new published work" /></td></tr>';
			$buffer .= $formend;

			// Interests, skills, other information...
			$buffer .= $formstart;
			$buffer .= '<tr class="banded">
							<td>Name:</td><td><input type="text" name="other_name" maxlength="128" placeholder="Language, exhibition, or other interesting skills/experience"></td></tr>
						<tr><td colspan="2"><textarea name="other_description" style="width: 100%; border: 0;" rows="6" placeholder="Description of skill or interest"></textarea></td></tr>
						<tr class="banded"><td colspan="4"><input name="' . $this->Name() . '" type="submit" value="Save new skill" /></td></tr>'; 
			$buffer .= $formend;

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
					$title = $_POST["pro_title"];
					$department = $_POST["pro_department"];
					$organization = $_POST["pro_organization"];
					$start = $_POST["pro_start"];
					$end = $_POST["pro_end"];
					$description = $_POST["pro_description"];
					$query = "INSERT INTO " . $dbprefix . "experience (title, institution, organization, start, stop, description) 
							  VALUES('$title', '$department', '$organization', '$start', '$end', '$description')";
					if( !mysql_query($query,$link) )
						$this->error[] = "Failed to add experience: " . mysql_error();
					break;
				case "Save new published work":
					$name = $_POST["pub_name"];
					$mainwork = $_POST["pub_mainwork"];
					$country = $_POST["pub_country"];
					$year = $_POST["pub_year"];
					$title = $_POST["pub_title"];
					$description = $_POST["pub_description"];

					$query = "INSERT INTO " . $dbprefix . "works (publisher, institution, country, year, title, description) 
							  VALUES('$name', '$mainwork', '$country', '$year', '$title', '$description')";
					if( !mysql_query($query,$link) )
						$this->error[] = "Failed to add published work: " . mysql_error();
					break;
				case "Save new skill":
					$name = $_POST["other_name"];
					$description = $_POST["other_description"];

					$query = "INSERT INTO " . $dbprefix . "other (title, description) VALUES('$name', '$description')";
					if( !mysql_query($query,$link) )
						$this->error[] = "Failed to add skill: " . mysql_error();
					break;
			}
		}
	}
}

?>
