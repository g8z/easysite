<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );

if ( !hasAdminAccess( 'cm_users' ) || ( !$groupID && !hasAdminAccess( 'cm_users_gr_add' ))) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
	$t->display( $templateName );
    exit;
}


require_once( INCLUDE_DIR . 'internal/editPermissions.php' );

$groupID = GUEST_GROUP; // no group

if ( $_POST['formIsSubmitted'] ) {
	updatePermissions( GROUP, $groupID, $site );
	include_once( FULL_PATH . 'manage/usersAndGroups.php' );
	exit();
}


$restrictedSections2 = getRestrictedSections( GROUP, $groupID );

$permissions = array(
    //'cm_tools'      => fetchAll( 'cm_tools', $restrictedSections2, $groupID ),
    'form'          => fetchAll( 'form', $restrictedSections2, $groupID ),
    //'form_section'    => fetchAll( 'form_section' ),
    'page'          => fetchAll( 'page', $restrictedSections2, $groupID ),
    //'page_section'    => fetchAll( 'page_section' ),
    //'layer'       => fetchAll( 'layer' ),
    //'menu'            => fetchAll( 'menu' ),
    //'menu_item'   => fetchAll( 'menu_item' ),
    'file'          => fetchAll( 'file', $restrictedSections2, $groupID )
);

$t->assign( 'resources', $permissions );

$t->assign( 'bodyTemplate', 'manage/guestPermissions.tpl' );
$session->updateLocation( 'guest_manager', 'Guest Permissions', array( 'groupID' ) );

include_once( '../init_bottom.php' );

$t->display( $templateName );

?>