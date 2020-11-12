<?php

if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );

$users = $db->getAll( "select id, login_id, first_name, last_name from " . USERS_TABLE . " where site_key = '$site' order by login_id" );

$groups = $db->getAll( "select id, name from " . GROUPS_TABLE . " where site_key = '$site' order by name" );

$selectOne = '- Select One -';

$userList = array( $selectOne );
$groupList = array( $selectOne );

foreach( $users as $user ) {

	if ( !trim( $user[login_id] ) )
		$user[login_id] = '(' . $user[first_name] . ' ' . $user[last_name] . ')';


	$userList[$user[id]] = $user[login_id];
}

foreach( $groups as $group ) {
	$groupList[$group[id]] = $group[name];
}

$t->assign( 'users', $userList );
$t->assign( 'groups', $groupList );

$t->assign( 'siteTitle', $db->getOne( "select title from " . SITES_TABLE . " where site_key = '$site' limit 1" ) );

// if this is not the 'default' site administrator, then do not allow access

if ( !hasAdminAccess( 'cm_users' ) ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', 'manage/usersAndGroups.tpl' );
}

$permissions = array(
    'user_add'     => hasAdminAccess( 'cm_users_add' ),
    'user_edit'    => hasAdminAccess( 'cm_users_edit' ),
    'user_import'  => hasAdminAccess( 'cm_users_import' ),
    'user_download'=> hasAdminAccess( 'cm_users_download' ),
    'user_delete'  => hasAdminAccess( 'cm_users_delete' ),
    'group_add'    => hasAdminAccess( 'cm_users_gr_add' ),
    'group_edit'   => hasAdminAccess( 'cm_users_gr_edit' ),
    'group_delete' => hasAdminAccess( 'cm_users_gr_delete' )
);

$t->assign( 'permissions', $permissions );

$session->updateLocation( 'users_and_groups', 'Users & Groups Admin Index' );
include_once( '../init_bottom.php' );
$t->display( $templateName );

?>