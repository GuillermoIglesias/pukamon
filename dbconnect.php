<?php
	error_reporting( ~E_DEPRECATED & ~E_NOTICE );
	
	define('DBHOST', 'URL DATA BASE HOST');
	define('DBUSER', 'DATA BASE USER NAME');
	define('DBPASS', 'DATA BASE PASS');
	define('DBNAME', 'DATA BASE NAME');
	
	$conn = mysql_connect(DBHOST,DBUSER,DBPASS);
	$dbcon = mysql_select_db(DBNAME);
	
	if ( !$conn ) {
		die("Connection failed : " . mysql_error());
	}
	
	if ( !$dbcon ) {
		die("Database Connection failed : " . mysql_error());
	}