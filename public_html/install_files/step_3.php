<?php

import_request_variables( 'p' );

if ( $dbType == 'mysql' ) { /** This check for database feature is only available for mysql. **/
	$db = DB::connect( array(
		'username' => $user,
		'password' => $password,
		'hostspec' => $host,
		'database' => '',
		'phptype'  => $dbType
	), 0 );

	if ( DB::isError($db) ) {
		if ( $db->code == -24 )
			$t->assign( 'errorLogin', 1 );
		else if ( $db->code == -9 )
			$t->assign( 'errorDB', 1 );

		if ( @file_exists( 'step_2.php' ) )
			include 'step_2.php';
		else
			include 'install_files/step_2.php';

		return;
	}

	$result = $db->getAll( "show databases;" );

	// check if we have rights

	if ( !DB::isError( $result ) ) {

		foreach ( $result as $index => $dbarray ) {

			$dbname = $dbarray[0];

			if ( $name == $dbname ) {
				$db_valid = true;
				break;
			}
		}
	}
	else {

		// if no rights - assume user entered valid db name
		// if not - this will be found in the next lines

		$db_valid = true;
	}
}

if ( $dbType != 'mysql' || $db_valid != false ) {
	$db = DB::connect( array(
		'username' => $user,
		'password' => $password,
		'hostspec' => $host,
		'database' => $name,
		'phptype'  => $dbType
	), 0 );
}
else {
	$t->assign( 'errorDBname', $result );
	include 'step_2.php';
	return;
}

if ( DB::isError($db) ) {
	if ( $db->code == -24 )
		$t->assign( 'errorLogin', 1 );
	else if ( $db->code == -9 )
		$t->assign( 'errorDB', 1 );

	if ( @file_exists( 'step_2.php' ) )
		include 'step_2.php';
	else
		include 'install_files/step_2.php';

	return;
}

// Replacing config variables
$replace = array(
	'DB_USER'	=> $user,
	'DB_NAME'	=> $name,
	'DB_HOST'	=> $host,
	'DB_PASS'	=> $password,
	'DB_TYPE'	=> $dbType,
	'DB_PREFIX' => $dbPrefix,
	'DOC_ROOT' 	=> $docroot,
	'DEMO_MODE' => intval( $demo_mode ),
	'VERSION' 	=> $version,
	'EASYSITE_INSTALLED' => 1
);

$configData = getConfigData( $replace );
$configCreated = writeConfig( $configData );

$t->assign( 'upgrade', $upgrade );

// check if there are tables in hte database

$tables = $db->getAll( 'show tables' );

if ( $install_type == 'upgrade' ) {

	// do upgrade here
	$t->assign( 'upgrade', 1 );

	$succ = upgradeWithFile( SQL_FILE );
	$succ &= upgradeWithFile( 'sql/modules/gallery.sql' );
	//$succ &= upgradeWithFile( 'sql/modules/realty.sql' );
	$succ &= upgradeWithFile( 'sql/modules/poll.sql' );

	updateSettingsTable();
	updateForms();

	updateUsePageKey();

	updateLayerVisibility();

	// 3.1.6 upgrade
	updateGuestPermissions();
	updateSettingsTable316();
	updateGallery316();
	updateUsers316();

	// 3.2.4
	updateSef324();

	$t->assign( 'upgradeSuccessull', $succ );
}
else {

	// Creating tables
	$tr = executeFromFile( SQL_FILE , 'create' );
	$sr = executeFromFile( SAMPLE_FILE , 'insert' );

	$t->assign( 'tablesCreated', $tr['success'] );
	$t->assign( 'tableError', $tr['error'] );

	$t->assign( 'sampleInserted', $sr['success'] );
	$t->assign( 'sampleError', $sr['error'] );

	// install modules

	executeFromFile( 'sql/modules/gallery.sql' );
	//executeFromFile( 'sql/modules/realty.sql' );
	executeFromFile( 'sql/modules/poll.sql' );
}

$t->assign( 'configCreated', $configCreated );
$t->display( 'pages/install2.tpl' );

$db->disconnect();

?>