<?php

if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );

include_once( '../init_bottom.php' );

if ( !hasAdminAccess( 'cm_users' ) || !hasAdminAccess( 'cm_users_download' )) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
	$t->display( $templateName );
    exit;
}   


if ( $delimiter == 'comma' ) {
	$separator = ',';
	$ext = 'csv';
}
else if ( $delimiter == 'tab' ) {
	$separator = "\t";
	$ext = 'xls';
}
else if ( $delimiter == 'other' ) {
	$separator = $other;
	$ext = '';
}
else { // web output
	$separator = '';
	$ext = '';
}

$downloadData = array();

$usersTable 	= USERS_TABLE;
$groupsTable 	= GROUPS_TABLE;

if ( $groupID )
	$group = "and $groupsTable.id = '$groupID'";
else
	$group = "and $groupsTable.id = $usersTable.group_id";

$data = $db->getAll( "select * from $usersTable, $groupsTable
	where $usersTable.site_key = '$site' and $groupsTable.site_key = '$site' $group" );

// mapping of fields with descriptive headers. This also determines the
// order of fields, and which fields to exclude

$map = array(
	'name'       	=> 'Group Name',
	'login_id'		=> 'Login Username',
	'login_pass'	=> 'Login Password',
	'first_name'	=> 'First Name',
	'last_name'		=> 'Last Name',
	'email'			=> 'E-Mail',
	'url'			=> 'URL',
	'phone'			=> 'Phone Number',
	'address_1'		=> 'Address Line 1',
	'address_2'		=> 'Address Line 2',
	'date_created'	=> 'Date Created',
	'last_login'	=> 'Last Login Date',
	'date_expires'	=> 'Expiration Date',
	'comments'		=> 'Comments'
	);

// add the header row

if ( $headers && function_exists( 'array_values' ) ) {
	$headers = array_values( $map );

	if ( $quotes ) {
		foreach( $headers as $index => $value ) {
			$headers[$index] = '"' . $value . '"';
		}
	}

	if ( $delimiter == 'web' )
		$downloadData [] = '<tr valign=bottom><th>' . implode( '</th><th>', $headers ) . "</th></tr>\n";
	else
		$downloadData [] = implode( $separator, $headers );
}

foreach( $data as $index => $row ) {

	$row2 = array();

	foreach( $map as $key => $val ) {
		$row2[$key] = str_replace( $separator, '.', $row[$key] );

		if ( $quotes )
			$row2[$key] = '"' . $row2[$key] . '"';
	}
	
	if ( $delimiter == 'web' )
		$downloadData [] = '<tr><td>' . implode( '</td><td>', $row2 ) . "</td></tr>\n";
	else
		$downloadData [] = implode( $separator, $row2 );
}

$db->disconnect();

if ( $delimiter == 'web' ) {
	// output the data to an standard HTML table

	echo "<table border=1 width=100% cellpadding=1 cellspacing=1 style='font-family:Arial;font-size:10pt;'>";
	echo implode( $downloadData, "\n" );
	echo "</table>";
}
else {
	session_cache_limiter("");

	header("Cache-Control: public");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=users.$ext");

	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Pragma: public");

	echo implode( $downloadData, "\n" );
}

?>