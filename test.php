<?php 
// connect to your MySQL database here

$connection = mysql_connect( "localhost", "ante", "ante" ) or die ( mysql_error() );
mysql_select_db( "mydb", $connection ) or die ( mysql_error() );


// Execute the SQL script
$file = file_get_contents('test.sql');

$sqlArr = explode( ";", $file );

$prefix = "nom_";
$pattern = "/cv_/";

if( !$sql ){
	foreach ($sqlArr as $query){
		$q = preg_replace($pattern, $prefix, $query);
		print "q: " . $q;
		$result = mysql_query( $q, $connection );
		print "result: " . $result;
		if( $result != 1 ){
			$error = mysql_error();
			$code = mysql_errno();
		}
	}
}

if( $code != 1 ){
	print "error code: " . $code;
	print $error;
}

mysql_close();

?>
