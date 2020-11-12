<?php

$configFile = dirname(__FILE__) . '/../config.php';
require_once( $configFile );

$dsn = DB_TYPE . '://' . DB_USER . ':' . DB_PASS . '@' . DB_HOST . '/' . DB_NAME;
$db = @DB::connect( $dsn );
$db->setFetchMode( DB_FETCHMODE_ASSOC );

$t->assign( 'submitted', 0 );
$t->assign( 'error', 0 );

if ( $_POST['adminLogin'] ) {
	
	$t->assign( 'submitted', 1 );
	
	$password = ( $_POST['use_md5']=='yes' ? md5($_POST[adminPassword1]) : $_POST[adminPassword1] );
	
	$error = $db->getOne( 'select id from '.USERS_TABLE." where login_id='$_POST[adminLogin]'" );
	
	if ( !$error )
		$result = $db->query( 'insert into '.USERS_TABLE." (login_id,login_pass,first_name,email,group_id,date_created,status,site_key) values ('$_POST[adminLogin]','$password','$_POST[adminName]','$_POST[adminEmail]','1',NOW(),'active','default')" );
	
	$t->assign( 'error', $error );
		
	// now update all forms 'to' addresses
	$formTos = $db->getAll( 'select id, value from '.SETTINGS_TABLE." where resource_type='form' and property='mail_to_address'" );
	if ( $formTos )
	foreach ( $formTos as $idx=>$formTo ) {
		if ( $formTo['value'] != '' )
			$db->query( 'update '.SETTINGS_TABLE." set value='{$_POST['adminEmail']}' where id='$formTo[id]'" );
	}
	
	// update site settings
	
	$db->query( 'update '. SETTINGS_TABLE ." set value='{$_POST['adminName']}' where resource_type='site' and property='admin_name'" );
	$db->query( 'update '. SETTINGS_TABLE ." set value='{$_POST['adminEmail']}' where resource_type='site' and property='admin_email'" );
}

if ( $_POST['use_md5'] ) {
	
	$id = $db->getOne( 'select id from '.SETTINGS_TABLE." where resource_type='site' and property='use_md5'" );
	if ( $id ) {
		$db->query( 'update '. SETTINGS_TABLE ." set value='{$_POST['use_md5']}' where id='$id'" );
	}
	else {
		$db->query( 'insert into '. SETTINGS_TABLE." (id,resource_type,resource_id,property,value,site_key) values ('','site','default','use_md5','{$_POST['use_md5']}','default')" );
	}
}

$t->display( 'pages/install4.tpl' );

?>