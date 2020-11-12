<?php

if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );
    
$resource_id = intval( $_REQUEST['resource_id'] );

switch( $resource_type ) {
	
	case 'layer':
		$table = LAYERS_TABLE;
		$title = 'title';
		$hasAccess = hasAdminAccess( 'cm_layer_edit' );
		break;
		
	case 'menu':
		$table = MENUS_TABLE;
		$title = 'title';
		$hasAccess = hasAdminAccess( 'cm_menu_edit_structure' );
		break;
		
	case 'menuitem':
		$table = MENUITEMS_TABLE;
		$title = 'title';
		$hasAccess = hasAdminAccess( 'cm_menu_edit_structure' );
		break;
		
	default:
		exit();
		break;
		
	
}

$resource = $db->getRow( "select id, $title, restrict_to from $table where id='$resource_id'" );

$sharedPages = getSQLShares( 'page' ); 
$sharedForms = getSQLShares( 'form' ); 
$sharedReports = getSQLShares( 'report' ); 

$allPages = $db->getAll( "select id, if(id in ($sharedPages), concat(title, '[shared]'), title) as title, page_key from " . PAGES_TABLE . " where site_key = '$site' or id in ($sharedPages) order by title" );
$allForms = $db->getAll( 'select resource_id as id, property, if(resource_id in ('.$sharedForms.'), concat(value, \'[shared]\'), value) as title from '.SETTINGS_TABLE." where resource_type='form' and (property='title') and (site_key='$site' or resource_id in ($sharedForms) ) order by value" );
$allReports = $db->getAll( "select *, if(id in ($sharedPages), concat(name, '[shared]'), name) as title from " . REPORTS_TABLE . " where site_key = '$site' or id in ($sharedReports) order by name" );

$resources = array(
	'page' => array( 'title'=>'Pages', 'items'=>$allPages ),
	'form' => array( 'title'=>'Forms', 'items'=>$allForms ),
	'report' => array( 'title'=>'Reports', 'items'=>$allReports ),
);

$t->assign( 'resources', $resources );

// -------------------------
// check for form submission
// -------------------------

$restrict = array();

if ( $formIsSubmitted ) {
	
	foreach ( $resources as $key=>$item ) {
		
		if ( $_POST[$key] == 'selective' ) {
			
			foreach ( $item['items'] as $idx=>$r ) {
				if ( $_POST[$key.'_'.$r['id']] )
					$restrict[$key][$r['id']] = 1;
			}
		}
		else {
			
			$restrict[$key] = $_POST[$key];
		}
	}
	
	$restrict['module'] = $_POST['module'];
	$restrict['cm_tools'] = $_POST['cm_tools'];
	
	$restrict = serialize( $restrict );
	
	$resource['restrict_to'] = $restrict;
	
	$db->query( "update $table set restrict_to='$restrict' where id='$resource_id'" );
}

if ( $resource_type == 'menuitem' )
	$resource_type = 'menu item';

$t->assign( 'resource_type', $resource_type );
$t->assign( 'resource_id', $resource_id );
$t->assign( 'resource_name', $resource[$title] );

$r = unserialize( $resource['restrict_to'] );

// visible anywhere if is not set
if ( !$r ) {
	$r['page'] = 'all';
	$r['form'] = 'all';
	$r['report'] = 'all';
	$r['module'] = '1';
	$r['cm_tools'] = '1';
};

$t->assign( 'restrict_to', $r );

$t->assign( 'title', 'Edit Visibility' );

if ( !$hasAccess ) {
	$template = 'manage/authError.tpl';
}
else {
	$template = 'manage/editRestriction.tpl';
}

include_once( '../init_bottom.php' );

$t->display( 'popupHeader.tpl' );
$t->display( $template );
$t->display( 'popupFooter.tpl' );

?>