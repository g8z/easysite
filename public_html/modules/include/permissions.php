<?php

// ----------------------------------------------------------
// This file requires 'getPermissions' function to be defined
// in the file from which this file is included
// ----------------------------------------------------------

if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../../../init.php' );

include_once( INCLUDE_DIR . 'internal/class.site.php' );

define( 'USER', 1 );
define( 'GROUP', 2 );

function updatePermissions( $type, $permissions, $insertValue ) {

    global $db, $site;

    if ( $type == GROUP ) {

        $where = "group_id = '$insertValue'";
        $insertField = 'group_id';

    } else {

        $where = "user_id = '$insertValue'";
        $insertField = 'user_id';

    }

    $table = PERMISSIONS_TABLE;

    $allModulePermissions = getPermissionIds( getPermissions() );

    $list = '( \'' . implode( '\',\'', $allModulePermissions ) . ' \')';

    $db->query( "delete from $table where $where and site_key = '$site' and resource_type in $list" );

	foreach( $permissions as $p ) {

		if ( trim( $p ) ) {

			$db->query( 'insert into ' . PERMISSIONS_TABLE . "
			(
				resource_type,
				site_key,
				$insertField
			)
			values
			(
				'$p',
				'$site',
				'$insertValue'
			)
			" );
		}
	}
}

$users = $db->getAll( "select id, login_id from " . USERS_TABLE . " where site_key = '$site' order by login_id" );

$groups = $db->getAll( "select id, name from " . GROUPS_TABLE . " where site_key = '$site' order by name" );

$userList  = getAssocArray( 'id', 'login_id', $users );
$groupList = getAssocArray( 'id', 'name', $groups );

if ( $updatePermissions ) {

    // ------------------------
    // get array of permissions
    // ------------------------

    $permissions = array();

    foreach ( $_POST as $key => $value ) {

        if ( substr( $key, 0, 3) == 'cm_' )
            $permissions[] = $key;
    }

}


if ( $userID ) {

    // -----------------------------
    // managing permissions for user
    // -----------------------------

    $siteObj = new ES_Site( $db, DEFAULT_SITE );

    if ( $updatePermissions ) {

        updatePermissions( USER, $permissions, $userID );

    }

    $permissions = $siteObj->getPermissions( $site, $userID );

    $restrictedSections = array();
    foreach( $permissions as $p_id => $p_val ) {
        if ( $p_val )
            $restrictedSections[ '0_' . $p_id ] = true;
    }

    $permissions = getPermissions( $restrictedSections );

    $forTitle = $db->getOne( 'select login_id from '. USERS_TABLE . " where id='$userID'" );

    $t->assign( 'permissions', $permissions );
    $t->assign( 'forTitle', 'user '. $forTitle );

    $t->assign( 'showPermissions', true );

} elseif ( $groupID ) {

    // ------------------------------
    // managing permissions for group
    // ------------------------------

    if ( $updatePermissions ) {

        updatePermissions( GROUP, $permissions, $groupID );

    }

    // determine the existing permissions of this group

    $restrictedSections1 = $db->getAll( "select resource_id, resource_type from " . PERMISSIONS_TABLE . " where site_key = '$site' and group_id = '$groupID' order by resource_type" );

    $restrictedSections2 = array();

    // reformat the $restrictedSections array as a hash-table-like structure

    foreach( $restrictedSections1 as $index => $row ) {
        $restrictedSections2[ $row[resource_id] . '_' . $row[resource_type] ] = true;
    }

    // 'getPermissions' is defined in every module that call this file
    $data = getPermissions( $restrictedSections2 );

    $t->assign( 'permissions', $data );

    $forTitle = $db->getOne( 'select name from '. GROUPS_TABLE . " where id='$groupID'" );

    $t->assign( 'forTitle', 'group '. $forTitle );

    $t->assign( 'showPermissions', true );

} else {

    $t->assign( 'showPermissions', false );

}

$t->assign( 'users', $userList );
$t->assign( 'groups', $groupList );

$t->assign( 'userID', $userID );
$t->assign( 'groupID', $groupID );

$t->assign( 'navigation', 'modules/gallery/navigation.tpl' );
$t->assign( 'bodyTemplate', 'modules/permissions.tpl' );

// display the main template, with body content embedded
if ( $t->template_exists( "$site.tpl" ) )
    $t->display( "$site.tpl" );
else
    $t->display( 'default.tpl' );

?>