<?php
if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );
	
$session->updateLocation( 'global_settings', 'Global Settings' );
include_once( '../init_bottom.php' );

// only the default site administrator has access to the users/groups tool
if ( !hasAdminAccess( 'cm_settings' ) ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', 'manage/globalSettings.tpl' );
}

$t->display( $templateName );