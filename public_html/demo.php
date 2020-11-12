<?php

// one week of demo site life
// after that period sites will be deleted

//define( 'SITE_LIFETIME', 0 );
$week = 60*60*24*7;
define( 'SITE_LIFETIME', $week );

// check to see if the user IP address exists as a site...

$ip = $_SERVER['REMOTE_ADDR'];

// if we cannot use the IP (due to IP blocker?), attempt
// to use some other client setting as the site key

if ( !trim( $ip ) ) {
	$ip = serialize( $_SERVER['HTTP_USER_AGENT'] );
}

$site = $ip;
$_SESSION['site'] = $site;

if ( $_GET['site'] )
	die( 'You can not view this page.' );


include_once( INCLUDE_DIR . 'internal/class.site.php' );
$siteObj = new ES_Site( $db, DEFAULT_SITE );

// delete sites over SITE_LIFETIME period

$expiredTime = time() - SITE_LIFETIME;
$eUsers = $db->getAll( 'select * from '. USERS_TABLE ." where UNIX_TIMESTAMP(date_created)<'$expiredTime'" );
if ( $eUsers )
foreach( $eUsers as $idx=>$eUser ) {
	$c = new Cacher( FULL_PATH . TEMP_DIR, DOC_ROOT . TEMP_DIR, $eUser['site_key'] );
	$c->_db = $db;
	$siteObj->delete( $eUser['site_key'] );
	unset( $c );
}

$exists = $db->getOne( "select id from " . SITES_TABLE . " where site_key = '$ip'" );

if ( !$exists ) {

	$db->query( 'insert into ' . GROUPS_TABLE . " (
		name,
		description,
		site_key
		) values (
		'Administrators',
		'Access to all areas of the system and all content-management tools',
		'$site'
		)" );

	$demoGroupID = $db->getOne( 'select max(id) from ' . GROUPS_TABLE . " where site_key = '$site'" );

	// add the admin/pass user to USERS_TABLE

	$pass = $system->settings['use_md5'] == 'yes' ? md5('pass') : 'pass';
	$db->query( 'insert into ' . USERS_TABLE . " (
		login_id,
		login_pass,
		first_name,
		last_name,
		date_created,
		group_id,
		status,
		site_key
		) values (
		'admin',
		'$pass',
		'Test',
		'User',
		NOW(),
		'$demoGroupID',
		'active',
		'$site'
		)" );

	// get this user's id

	$demoUserID = $db->getOne( 'select max(id) from ' . USERS_TABLE . " where site_key = '$site'" );

	// need to specify the default site as a parameter so that we know where to
	// draw the permissions from

	// copy all of the default site permissions

	$demoPermissionsData = $db->getAll( 'select * from ' . PERMISSIONS_TABLE . " where site_key = '".DEFAULT_SITE."' and resource_id = '0'" );

	foreach( $demoPermissionsData as $index => $row ) {
		$db->query( 'insert into ' . PERMISSIONS_TABLE . " (
			resource_id,
			resource_type,
			group_id,
			user_id,
			site_key
			) values (
			'0',
			'$row[resource_type]',
			'$demoGroupID',
			'0',
			'$site'
			)" );
	}

	// insert quest persmissions
	$db->query( "INSERT INTO ". PERMISSIONS_TABLE . " ( resource_id, resource_type, group_id, user_id, site_key ) VALUES (0, 'form', ".GUEST_GROUP.", 0, '$site');");
	$db->query( "INSERT INTO ". PERMISSIONS_TABLE . " ( resource_id, resource_type, group_id, user_id, site_key ) VALUES (0, 'page', ".GUEST_GROUP.", 0, '$site');");
	$db->query( "INSERT INTO ". PERMISSIONS_TABLE . " ( resource_id, resource_type, group_id, user_id, site_key ) VALUES (0, 'file', ".GUEST_GROUP.", 0, '$site');");

	// add the remainder of the data for this new website

	$parentData = array(
		'c_pages'=>1,
		'c_forms'=>1,
		'c_reports'=> 1,
		'c_layers' => 1,
		'c_menus'  => 1,
		'c_settings' => 1,
		'c_styles' => 1,
		'c_skins'  => 1,
		'c_files'  => 1,
		'c_lists'  => 1,
		'c_module_categories' => 1,
		'c_module_items'      => 1,
	);

	$siteObj->create( $ip, $demoUserID, $parentData, DEFAULT_SITE );

}

?>