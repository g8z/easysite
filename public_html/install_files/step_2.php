<?php
global $t;

// type list
$typeValues = array( 'mysql', 'pgsql', 'ibase', 'msql', 'mssql', 'oci8', 'odbc', 'sybase', 'ifx', 'fbsql');
$typeNames  = array(  	'MySQL',
						'PostgreSQL',
						'InterBase',
						'Mini SQL',
						'Microsoft SQL Server',
						'Oracle 7/8/8i',
						'ODBC (Open Database Connectivity)',
						'SyBase',
						'Informix',
						'FrontBase'
					);

// REQUEST_URI env. var. not available in Apache
$request = $_SERVER['PHP_SELF'];

if ( strlen( $_SERVER['QUERY_STRING'] ) > 0 )
	$request .= '?' . $_SERVER['QUERY_STRING'];

$docroot = ( preg_match( '/\/(.+)\/.*/', $request, $matches ) ) ? '/'. $matches[1] .'/' : '/';

$t->assign( 'docroot', $docroot );
$t->assign( 'host' , 'localhost' );
$t->assign( 'dbPrefix' , 'es' );
$t->assign( 'typeValues', $typeValues );
$t->assign( 'typeNames' , $typeNames );

if ( isset($user) ) { /** Already posted. **/
	$t->assign ( 'user' , $user );
	$t->assign ( 'host' , $host );
	$t->assign ( 'password' , $password );
	$t->assign ( 'name' , $_POST['name'] );

	$t->assign ( 'dbType' , $dbType );
	$t->assign ( 'dbPrefix' , $dbPrefix );
}

// fix this later
//$t->assign( 'version', $version );


$t->display( 'pages/install.tpl' );

?>