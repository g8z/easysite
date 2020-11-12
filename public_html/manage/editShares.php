<?php

if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );
//


$resource = $_REQUEST['resource'];
$resourceId = intval( $_REQUEST['resource_id'] );

$where = 1;
	
switch ( $resource ) {
	
	case 'menu':
		$id = 'id';
		$table = MENUS_TABLE;
		$title = 'title';
		$editable = true;
		break;
 
	case 'page':
		$id = 'id';
		$table = PAGES_TABLE;
		$title = 'title';
		$editable = true;
		break;
 
	case 'layer':
		$id = 'id';
		$table = LAYERS_TABLE;
		$title = 'title';
		$editable = true;
		break;
 
/*	case 'list':
		$id = 'id';
		$table = LISTS_TABLE;
		$title = 'title';
		$editable = true;
		break;
*/ 
	case 'form':
		$id = 'resource_id';
		$table = SETTINGS_TABLE;
		$title = 'value';
		$where = "resource_type='form' and property='title'";
		$editable = true;
		break;
 
	case 'style':
		$id = 'id';
		$table = STYLES_TABLE;
		$title = 'name';
		$where = 'skin_id=0';
		$editable = true;
		break;
 
	case 'skin':
		$id = 'id';
		$table = SKINS_TABLE;
		$title = 'name';
		$editable = false;
		break;
 
	case 'file':
		$id = 'id';
		$table = FILES_TABLE;
		$title = 'download_name';
		$editable = true;
		break;
 
	case 'report':
		$id = 'id';
		$table = REPORTS_TABLE;
		$title = 'name';
		$editable = false;
		break;
		
	default:
		$id = 'id';
		$resource = 'menu';
		$resourceId = 0;
		$table = MENUS_TABLE;
		$title = 'title';
		$editable = true;
		break;
 
}

// ---------------
// get groups list
// ---------------

$groups = $db->getAll( 'select g.id, g.name, s.view, s.edit from '.GROUPS_TABLE." g left join ".SHARES_TABLE." s on s.resource_id='$resource_id' and s.resource_type='$resource' and s.group_id=g.id where g.site_key='$site'" );

// check shares for all groups
$allGroups = $db->getRow( 'select view, edit from '.SHARES_TABLE." where resource_id='$resource_id' and resource_type='$resource' and group_id=".ALL_TARGETS );

array_unshift( $groups, array( 'id'=>ALL_TARGETS, 'name'=>'All Groups', 'view'=>$allGroups['view'], 'edit'=>$allGroups['edit'] ) );


// ---------------------
// get list of all users
// ---------------------

$loggedUser = $_SESSION[es_auth][login_id];

$users = $db->getAll( 'select u.id, u.login_id, g.name as groupname, s.override, s.view, s.edit from '.USERS_TABLE." u left join ".GROUPS_TABLE." g on u.group_id=g.id left join ".SHARES_TABLE." s on s.resource_id='$resource_id' and s.resource_type='$resource' and s.user_id=u.id where u.site_key='$site' and u.login_id!='$loggedUser'" );

// check shares for all users
$allUsers = $db->getRow( 'select override, view, edit from '.SHARES_TABLE." where resource_id='$resource_id' and resource_type='$resource' and user_id=".ALL_TARGETS );

array_unshift( $users, array( 'id'=>ALL_TARGETS, 'login_id'=>'All Users', 'groupname'=>'All Groups', 'view'=>$allUsers['view'], 'edit'=>$allUsers['edit'], 'override'=>$allUsers['override'] ) );


$action = $_REQUEST['action'];

switch( $action ) {
	
	case 'save_resource':
		saveResource( $resource, $resourceId );
		break;
		
	default:
		break;
}

function saveResource( $resourceType, $resourceId ) {
	
	global $db, $site, $groups, $users;
	
	if ( !$resourceId )
		return;
		
	// delete previous shares
	
	$db->query( 'delete from '. SHARES_TABLE." where resource_id='$resourceId' and resource_type='$resourceType' and site_key='$site'" );
	
	foreach( $groups as $num=>$group ) {
		
		$fields = array(
			'resource_id' => $resourceId,
			'resource_type' => $resourceType,
			'group_id' => $group[id],
			'view' => $_POST['view_group_'.$group[id]],
			'edit' => $_POST['edit_group_'.$group[id]],
			'site_key' => $site,
		);
		
		$groups[$num][view] = $fields[view];
		$groups[$num][edit] = $fields[edit];
		
		$insertFields = implode( ', ', array_keys( $fields ) );
		$insertValues = '\'' . implode( '\', \'', array_values( $fields ) ) . '\'';
		
		$db->query( 'insert into '. SHARES_TABLE. " ($insertFields) values ($insertValues)" );
	}
	
	foreach( $users as $num=>$user ) {
		
		$fields = array(
			'resource_id' => $resourceId,
			'resource_type' => $resourceType,
			'user_id' => $user[id],
			'override' => $_POST['override_user_'.$user[id]],
			'view' => $_POST['view_user_'.$user[id]],
			'edit' => $_POST['edit_user_'.$user[id]],
			'site_key' => $site,
		);
		
		$users[$num][view] = $fields[view];
		$users[$num][edit] = $fields[edit];
		$users[$num][override] = $fields[override];
		
		$insertFields = implode( ', ', array_keys( $fields ) );
		$insertValues = '\'' . implode( '\', \'', array_values( $fields ) ) . '\'';
		
		$db->query( 'insert into '. SHARES_TABLE. " ($insertFields) values ($insertValues)" );
	}
		
}



$resourceItems = $db->getAll( "select $id, $title from $table where site_key='$site' and $where order by $title" );

$resourceValues = array();
$resourceIds = array();

foreach( $resourceItems as $num=>$resourceItem ) {
	$resourceValues[] = $resourceItem[$title];
	$resourceIds[] = $resourceItem[$id];
}

array_unshift( $resourceIds, 0 );
array_unshift( $resourceValues, '- Select Resource -' );

$t->assign( 'resourceIds', $resourceIds );
$t->assign( 'resourceValues', $resourceValues );

$t->assign( 'editable', $editable );

$resources = array( 
	'menu' => 'Menus',
	'page' => 'Pages',
	'layer' => 'Layers',
	//'list' => 'Lists',
	'form' => 'Forms',
	'style' => 'Styles',
	'skin' => 'Skins',
	'file' => 'Files',
	'report' => 'Reports',
);

$t->assign( 'resources', $resources );
$t->assign( 'countResources', count( $resources ) );

$t->assign( 'activeResource', $resource );
$t->assign( 'resourceId', $resourceId );

$t->assign( 'activeColor', $system->settings['main_color'] );
$t->assign( 'inactiveColor', '#DDDDDD' );

$t->assign( 'groups', $groups );
$t->assign( 'users', $users );

$t->assign( 'bodyTemplate', 'manage/editShares.tpl' );

$session->updateLocation( 'shares_manager', 'Edit Shares', array( 'resource', 'resource_id' ) );
include_once( '../init_bottom.php' );

$t->display( $templateName );

?>	