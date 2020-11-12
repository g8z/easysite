<?php
if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );
	
$session->updateLocation( 'layout_manager', 'Edit Layout', array( 'switchSkinId', 'skin_id' ) );
include_once( '../init_bottom.php' );

// only the default site administrator has access to the users/groups tool
if ( !hasAdminAccess( 'cm_settings' ) ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', 'manage/editLayout.tpl' );
}

$t->display( $templateName );