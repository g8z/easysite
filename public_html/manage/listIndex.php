<?php
if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );

if ( !hasAdminAccess( 'cm_list' ) ) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
	$t->display( $templateName );
    exit;
}

$hasAccess = true;

if ( $del ) {
    
    if ( $hasAccess = hasAdminAccess( 'cm_list_delete' ) ) {

    	// delete the list and all list items
    
    	$db->query( 'delete from ' . LISTS_TABLE . " where site_key = '$site' and id = '$del'" );
    
    	// delete all items within the this list
	
    }
}

if ( $bump ) {
    
    if ( $hasAccess = hasAdminAccess( 'cm_list_edit' ) ) {
    
    	// adjust the _order
    
    	// get the order # just before this one
    
    	$current = $db->getRow( 'select _order, id from ' . LISTS_TABLE . " where site_key = '$site' and id = '$bump'" );
    	$previous = $db->getRow( 'select _order, id from ' . LISTS_TABLE . " where _order < $current[_order] and site_key = '$site' order by _order desc limit 1" );
    
    	// swap $prevOrder and $curOrder
    
    	$db->query( 'update ' . LISTS_TABLE . " set _order = '$previous[_order]' where id = '$current[id]' and site_key = '$site'" );
    	$db->query( 'update ' . LISTS_TABLE . " set _order = '$current[_order]' where id = '$previous[id]' and site_key = '$site'" );
	
    }
}

$data = $db->getAll( 'select * from ' . LISTS_TABLE . " where site_key = '$site' order by _order" );

// re-number all _order fields

if ( $bump ) {

    if ( $hasAccess = hasAdminAccess( 'cm_list_edit' ) ) {

        $order = 0;

    	foreach( $data as $index => $row ) {
    
    		$db->query( 'update ' . LISTS_TABLE . " set _order = '$order' where id = '$row[id]' and site_key = '$site'" );
    		$order++;
    	}
    	
    }
}

$t->assign( 'listData', $data );

// now assign the lists that we have in $arr to the template

// only the default site administrator has access to the users/groups tool
$permissions = array(
    'add'    => hasAdminAccess( 'cm_list_add' ),
    'edit'   => hasAdminAccess( 'cm_list_edit' ),
    'delete' => hasAdminAccess( 'cm_list_delete' )
);

$t->assign( 'permissions', $permissions );

if ( !$hasAccess ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', 'manage/listIndex.tpl' );
}

$session->updateLocation( 'my_lists', 'My Lists' );
include_once( '../init_bottom.php' );
$t->display( $templateName );


?>