<?php

/********************************************************\
 * File: 	Cv.php										*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-12-04									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Cv frontpage module.					*
\********************************************************/

include_once("CvModule.php");

class Cv extends CvModule {

	function Description(){
		// No description for base modules
	}
	
	// Creates an form for editing user details in the database. Anything but the username and password, for now...
	function Content(){
		global $link, $dbprefix;

		// Details
		$buffer = '<section id="cv_head" class="cv"> <p>Curriculum vitae</p></section>';
		$buffer .= '<section id="cv_contact" class="cv">';
		$query = "SELECT name, street, city, phone, email FROM " . $dbprefix . "main ORDER BY id DESC LIMIT 1";
		$result = mysql_query($query, $link) or die( mysql_error() );
		$buffer .= '<table style="text-align:center">';
		while( $row = mysql_fetch_array($result, MYSQL_NUM) ){
			/* Do HTML */
			$buffer .= "<tr><td>";
			$numcols = count($row);
			for( $i = 0; $i < $numcols; $i++)
				$buffer .= "<p " . ($i == 0 ? 'class="rowtitle"' : 'class="nontitle"') . ">" . $row[$i] . "</p>";
			$buffer .= "</td></tr>";
			/* Do PDF */
		}
		$buffer .= '</table></section>';

		// Ambitions
		$buffer .= '<section id="cv_ambitions" class="cv">';
		$query = "SELECT ambitions FROM " . $dbprefix . "main ORDER BY id DESC LIMIT 1";
		$result = mysql_query($query, $link) or die( mysql_error() );
		while( $row = mysql_fetch_assoc($result) ){
			$paragraphs = explode("\n", $row["ambitions"]);
		}
		$lines = "";
		foreach( $paragraphs as $paragraph ){
			$lines .= '<p class="description">' . $paragraph . '</p>';
		}
		$buffer .= '<table style="text-align:justify">';
		$buffer .= '<tr><td style="padding-bottom:'.$rowpadding.'px">' . $lines . '</td></tr>';
		$buffer .= '</table>';
		$buffer .= '</section>';

		// Education
		$buffer .= '<section id="cv_education" class="cv">';
		$query = "SELECT school, start, end, degree, thesisname FROM " . $dbprefix . "education";
		$result = mysql_query($query, $link) or die( mysql_error() );
		$numrows = mysql_num_rows( $result );
		$buffer .= '<table style="text-align:left">';
		$rowindex = 1;
		while( $row = mysql_fetch_assoc($result) ){
			$buffer .= '<tr>';
			$buffer .= ( $rowindex === $numrows ? '<td class="leftcol" style="padding-bottom:"'.$rowpadding.'px">' : '<td class="leftcol">' );
			$buffer .= '<p class="rowtitle">' . $row["school"] . '</p><p class="nontitle">' . $row["start"] . ' - ' . $row["end"] . '</p></td>';
			$buffer .= ( $rowindex === $numrows ? '<td class="rightcol" style="padding-bottom:'.$rowpadding.'px">' : '<td class="rightcol">' );
			$buffer .= '<p class="rowtitle">' . $row["degree"] . '</p><p class="description">' . $row["thesisname"] . '</p></td></tr>';
		}
		$buffer .= "</table>";
		$buffer .= '</section>';

		// Experience
		$buffer .=  '<section id="cv_experience" class="cv">';
		$query = "SELECT title, institution, organization, start, stop, description FROM " . $dbprefix . "experience ORDER BY stop DESC, start ASC";
		$result = mysql_query($query, $link) or die( mysql_error() );
		$numrows = mysql_num_rows( $result );

		$buffer .= '<table style="text-align:left">';
		$rowindex = 1;
		while( $row = mysql_fetch_assoc($result) ){
			$buffer .= '<tr>';
			$buffer .= ( $rowindex === $numrows ? '<td class="leftcol" style="padding-bottom:'.$rowpadding.'px">' : '<td class="leftcol">' );
			$buffer .= '<p class="rowtitle">' . $row["title"] . '</p><p class="nontitle">' . $row["institution"] . '</p><p class="nontitle">' . $row["organization"] . '</p><p class="nontitle">' .  $row["start"] . ' - ' . $row["stop"] .'</p>';
			$buffer .= "</td>";

			$buffer .= ( $rowindex === $numrows ? '<td class="rightcol" style="padding-bottom:'.$rowpadding.'px">' : '<td class="rightcol">' );
			$buffer .= '<p class="description">' . $row["description"] . '</p>';
			$buffer .= "</td></tr>";
			$rowindex++;				
		}
		$buffer .= '</table></section>';

		// Works
		$buffer .= '<section id="cv_works" class="cv">';

		$query = "SELECT publisher, institution, country, year, title, description FROM " . $dbprefix . "works";
		$result = mysql_query($query, $link) or die( mysql_error() );
		$numrows = mysql_num_rows( $result );

		$buffer .= '<table style="text-align:left">';
		$rowindex = 1;
		while( $row = mysql_fetch_assoc($result) ){
			$buffer .= "<tr>";
			$buffer .= ( $rowindex === $numrows ? '<td class="leftcol" style="padding-bottom:'.$rowpadding.'px">' : '<td class="leftcol">' );
			$buffer .= '<p class="rowtitle">' . $row["publisher"] . '</p><p class="nontitle">' . $row["institution"] . '</p><p class="nontitle">' . $row["country"] . '</p><p class="nontitle">' .  $row["year"] . '</p>';
			$buffer .= "</td>";
			$buffer .= ( $rowindex === $numrows ? '<td class="rightcol" style="padding-bottom:'.$rowpadding.'px">' : '<td class="rightcol">' );
			$buffer .= '<p class="rowtitle">' . $row["title"] . '<p class="description">' . $row["description"] . '</p>';
			$buffer .= "</td></tr>";
			$rowindex++;
		}
		$buffer .= '</table></section>';

		$buffer .= '<section id="cv_skills" class="cv">';

		$query = "SELECT title, description FROM " . $dbprefix . "other ORDER BY title ASC";
		$result = mysql_query($query, $link) or die( mysql_error() );
		$numrows = mysql_num_rows( $result );

		$buffer .= '<table style="text-align:left">';
		$rowindex = 1;
		while( $row = mysql_fetch_assoc($result) ){
			$buffer .= "<tr>";

			$buffer .= ( $rowindex === $numrows ? '<td class="leftcol" style="padding-bottom:'.$rowpadding.'px">' : '<td class="leftcol">' );
			$buffer .= '<p class="rowtitle">' . $row["title"] . '</p></td>';

			$buffer .= ( $rowindex === $numrows ? '<td class="rightcol" style="padding-bottom:'.$rowpadding.'px">' : '<td class="rightcol">' );
			$buffer .= '<p class="description">' . $row["description"] . '</p></td></tr>';
			$rowindex++;
		}
		$buffer .= "</table>";

		$buffer .= '</section>';

		return $buffer;
	}
	
	function POST(){
		// No post methods available in this module.
	}
}

?>
