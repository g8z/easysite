<?php

if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );
	
include_once( INCLUDE_DIR . 'internal/emailBlastFunctions.php' );
	
if ( $mailFormSubmitted ) {
	// save current message in the session in case we have 
	// posted it to this page
	saveMessage();
}

if ( $selectionFormSubmitted ) {
	
	$_SESSION['email']['status'] = $_POST['status'];
	$_SESSION['email']['groups'] = $_POST['groups'];
	$_SESSION['email']['users'] = $_POST['users'];
	
	$numSelected = getSelectedCount();
	
	$t->assign( 'numSelected', $numSelected );
	
	// check if we need to save mailing list
	
	if ( $save_selection ) {
		saveMailingList( $selection_name, $_SESSION['email'] );
	}
}

	
$users = $db->getAll( 'select id, login_id from '. USERS_TABLE." where site_key='$site' order by login_id" );

$userValues = array();
$userTitles = array();

foreach( $users as $idx=>$user ) {
	$userValues[] = $user['id'];
	$userTitles[] = $user['login_id'];
}

$t->assign( 'userValues', $userValues );
$t->assign( 'userTitles', $userTitles );

$groups = $db->getAll( 'select * from '. GROUPS_TABLE." where site_key='$site' order by name" );
$t->assign( 'groups', $groups );

$t->assign( 'statuses', array(
	'active' 		=> 'Active',
	'pending' 		=> 'Pending',
	'suspended' 	=> 'Suspended',
	'terminated' 	=> 'Terminated'
	) 
);

$t->assign( 'sourceArray', $_SESSION['email'] );

$t->assign( 'selectionFormSubmitted', $selectionFormSubmitted );

if ( !hasAdminAccess( 'cm_users' ) ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', 'manage/selectUsers.tpl' );
}

$session->updateLocation( 'users_select', 'User Selection' );
include_once( '../init_bottom.php' );
$t->display( $templateName );

?>