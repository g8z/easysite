<?php

if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );
	
include_once( INCLUDE_DIR . 'internal/emailBlastFunctions.php' );

if ( $mailFormSubmitted )
	saveMessage();
	
if ( $mlFormSubmitted ) {
	
	if ( $deleteList ) {
		// delete mailing list
		
		$db->query( 'delete from '. MAILINGLISTS_TABLE ." where id='$deleteList'" );
		$db->query( 'delete from '. MAILINGTO_TABLE ." where mailing_id='$deleteList'" );
	}
	else {
		saveMailingList( $name, $_POST );
	}
}

if ( $list_id ) {
	
	$list = $db->getRow( 'select * from '. MAILINGLISTS_TABLE." where id='$list_id'" );
	$sourceArray = getMailingListArray( $list_id );
	
	$t->assign( 'list', $list );
	$t->assign( 'sourceArray', $sourceArray );
}


// get available mailing lists

$lists = $db->getAll( 'select * from '. MAILINGLISTS_TABLE ." where site_key='$site'" );

$listValues = array();
$listTitles = array();

if ( $lists )
foreach( $lists as $idx=>$item ) {
	$listValues[] = $item['id'];
	$listTitles[] = $item['name'];
}

array_unshift( $listTitles, '- New List -' );
array_unshift( $listValues, 0 );

$t->assign( 'listValues', $listValues );
$t->assign( 'listTitles', $listTitles );
	
// get available users

$users = $db->getAll( 'select id, login_id from '. USERS_TABLE." where site_key='$site' order by login_id" );

$userValues = array();
$userTitles = array();

foreach( $users as $idx=>$user ) {
	$userValues[] = $user['id'];
	$userTitles[] = $user['login_id'];
}

$t->assign( 'userValues', $userValues );
$t->assign( 'userTitles', $userTitles );

// get available groups

$groups = $db->getAll( 'select * from '. GROUPS_TABLE." where site_key='$site' order by name" );
$t->assign( 'groups', $groups );

$t->assign( 'statuses', array(
	'active' 		=> 'Active',
	'pending' 		=> 'Pending',
	'suspended' 	=> 'Suspended',
	'terminated' 	=> 'Terminated'
	) 
);

$t->assign( 'list_id', $list_id );

if ( !hasAdminAccess( 'cm_users' ) ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', 'manage/editMailingLists.tpl' );
}

$session->updateLocation( 'mailing_list_manager', 'Mailing Lists' );
include_once( '../init_bottom.php' );
$t->display( $templateName );

?>