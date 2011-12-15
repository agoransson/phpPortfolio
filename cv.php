<?php

/********************************************************\
 * File: 	cv.php										*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-11-21									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Curriculum Vitae.						*
\********************************************************/

/* Include the configuration file - contains the database connection */
include_once("config.php");

/* Use the global message array! */
global $link, $dbprefix;

/* Page title */
$title = "cv";

/* Global row padding (matching CSS padding-top for the last rows in each section) */
$rowpadding = 8;

if( isset($_GET["print"]) ){
	//generatePrint();
	include( "generatepdf.php" );
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
			print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/desktop.css\">";
			print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/emailpopup.css\">";
			print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/cv.css\">";
		}else{
			print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/desktop.css\">";
			print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/emailpopup.css\">";
			print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/cv.css\">";
		}
	?>

	<script type="text/javascript" src="./scripts/jquery-1.7.min.js"></script>
	<script type="text/javascript" src="./scripts/cv.js"></script>
</head>

<body>

<!-- BEGIN PAGE -->
<div id="wrapper">

	<!-- BEGIN HEADER -->
	<div id="header">
	<?php include("header.php") // We'll skip the normal header here! ?>
	</div>

	<!-- BEGIN SPECIFIC CONTENT -->
	<div id="content">
	
		<section id="cv_head" class="cv">
			<p>Curriculum vitae</p>

		</section>

		<section id="cv_contact" class="cv">
		<?php
			$query = "SELECT name, street, city, phone, email FROM " . $dbprefix . "main ORDER BY id DESC LIMIT 1";
			$result = mysql_query($query, $link) or die( mysql_error() );
			print "<table style=\"text-align:center\">";
			while( $row = mysql_fetch_array($result, MYSQL_NUM) ){
				/* Do HTML */
				print "<tr><td>";
				$numcols = count($row);
				for( $i = 0; $i < $numcols; $i++)
					print "<p " . ($i == 0 ? "class=\"rowtitle\"" : "class=\"nontitle\"") . ">" . $row[$i] . "</p>";
				print "</td></tr>";
				/* Do PDF */
			}
			print "</table>";
		?>
		</section>



		<section id="cv_ambitions" class="cv">
		<?php
			$query = "SELECT ambitions FROM " . $dbprefix . "main ORDER BY id DESC LIMIT 1";
			$result = mysql_query($query, $link) or die( mysql_error() );

			while( $row = mysql_fetch_assoc($result) ){
				$paragraphs = explode("\n", $row["ambitions"]);
			}
	
			$buffer = "";
			foreach( $paragraphs as $paragraph ){
				$buffer .= '<p class="description">' . $paragraph . '</p>';
			}

			print '<table style="text-align:justify">';
			print '<tr><td style="padding-bottom:'.$rowpadding.'px">' . $buffer . '</td></tr>';
			print '</table>';
		?>
		</section>

		<section id="cv_education" class="cv">
		<?php
			$query = "SELECT school, start, end, degree, thesisname FROM " . $dbprefix . "education";
			$result = mysql_query($query, $link) or die( mysql_error() );
			$numrows = mysql_num_rows( $result );

			print "<table style=\"text-align:left\">";
			$rowindex = 1;
			while( $row = mysql_fetch_assoc($result) ){
				print "<tr>";

				print ( $rowindex === $numrows ? "<td class=\"leftcol\" style=\"padding-bottom:".$rowpadding."px\">" : "<td class=\"leftcol\">" );
				print "<p class=\"rowtitle\">" . $row["school"] . "</p><p class=\"nontitle\">" . $row["start"] . " - " . $row["end"] . "</p>";
				print "</td>";

				print ( $rowindex === $numrows ? "<td class=\"rightcol\" style=\"padding-bottom:".$rowpadding."px\">" : "<td class=\"rightcol\">" );
				print "<p class=\"rowtitle\">" . $row["degree"] . "</p><p class=\"description\">" . $row["thesisname"] . "</p>";
				print "</td>";

				print "</tr>";
			}
			print "</table>";
		?>
		</section>

		<section id="cv_experience" class="cv">
		<?php
			$query = "SELECT title, institution, organization, start, stop, description FROM " . $dbprefix . "experience ORDER BY stop DESC, start ASC";
			$result = mysql_query($query, $link) or die( mysql_error() );
			$numrows = mysql_num_rows( $result );

			print "<table style=\"text-align:left\">";
			$rowindex = 1;
			while( $row = mysql_fetch_assoc($result) ){
				print "<tr>";
				print ( $rowindex === $numrows ? "<td class=\"leftcol\" style=\"padding-bottom:".$rowpadding."px\">" : "<td class=\"leftcol\">" );
				print "<p class=\"rowtitle\">" . $row["title"] . "</p><p class=\"nontitle\">" . $row["institution"] . "</p><p class=\"nontitle\">" . $row["organization"] . "</p><p class=\"nontitle\">" .  $row["start"] . " - " . $row["stop"] ."</p>";
				print "</td>";

				print ( $rowindex === $numrows ? "<td class=\"rightcol\" style=\"padding-bottom:".$rowpadding."px\">" : "<td class=\"rightcol\">" );
				print "<p class=\"description\">" . $row["description"] . "</p>";
				print "</td>";

				print "</tr>";
				$rowindex++;					
			}
			print "</table>";
		?>
		</section>

		<section id="cv_works" class="cv">
		<?php
			$query = "SELECT publisher, institution, country, year, title, description FROM " . $dbprefix . "works";
			$result = mysql_query($query, $link) or die( mysql_error() );
			$numrows = mysql_num_rows( $result );

			print "<table style=\"text-align:left\">";
			$rowindex = 1;
			while( $row = mysql_fetch_assoc($result) ){
				print "<tr>";

				print ( $rowindex === $numrows ? "<td class=\"leftcol\" style=\"padding-bottom:".$rowpadding."px\">" : "<td class=\"leftcol\">" );
				print "<p class=\"rowtitle\">" . $row["publisher"] . "</p><p class=\"nontitle\">" . $row["institution"] . "</p><p class=\"nontitle\">" . $row["country"] . "</p><p class=\"nontitle\">" .  $row["year"] . "</p>";
				print "</td>";

				print ( $rowindex === $numrows ? "<td class=\"rightcol\" style=\"padding-bottom:".$rowpadding."px\">" : "<td class=\"rightcol\">" );
				print "<p class=\"rowtitle\">" . $row["title"] . "<p class=\"description\">" . $row["description"] . "</p>";
				print "</td>";

				print "</tr>";
				$rowindex++;
			}
			print "</table>";
		?>
		</section>

		<section id="cv_skills" class="cv">
		<?php
			$query = "SELECT title, description FROM " . $dbprefix . "skills ORDER BY title ASC";
			$result = mysql_query($query, $link) or die( mysql_error() );
			$numrows = mysql_num_rows( $result );

			print "<table style=\"text-align:left\">";
			$rowindex = 1;
			while( $row = mysql_fetch_assoc($result) ){
				print "<tr>";

				print ( $rowindex === $numrows ? "<td class=\"leftcol\" style=\"padding-bottom:".$rowpadding."px\">" : "<td class=\"leftcol\">" );
				print "<p class=\"rowtitle\">" . $row["title"] . "</p>";
				print "</td>";

				print ( $rowindex === $numrows ? "<td class=\"rightcol\" style=\"padding-bottom:".$rowpadding."px\">" : "<td class=\"rightcol\">" );
				print "<p class=\"description\">" . $row["description"] . "</p>";
				print "</td>";

				print "</tr>";
				$rowindex++;
			}
			print "</table>";
		?>
		</section>

	</div>

	<!-- BEGIN FOOTER -->
	<div id="footer">
	<?php include("footer.php") ?>
	</div>

	<!-- POPUP -->
	<div id="email_popup">
		<p>Feel free to email me, just click my name.</p>
	</div>


<!-- END PAGE -->
</div>

</body>
</html>
