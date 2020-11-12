<?php
if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );


if ( !hasAdminAccess( 'cm_list' ) ) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
	$t->display( $templateName );
    exit;
}

$hasAccess = true;

if ( $formIsSubmitted ) {

	if ( !$id ) {

        if ( $hasAccess = hasAdminAccess( 'cm_list_add' ) ) {
	    
    	    // create a new list
    
    		// check to see if a list with this key name already exists or not
    		$exists = $db->getOne( 'select count(id) from ' . LISTS_TABLE . " where list_key = '$list_key' and site_key = '$site'" );
    
    		// ensures uniqueness for this list key
    
    		if ( $exists ) {
    			$list_key = $list_key . '_' . time();
    			$t->assign( 'listKeyExists', true );
    		}
    
    		// determine the _order value
    
    		$order = 1 + $db->getOne( 'select max(_order) from ' . LISTS_TABLE . " where site_key = '$site'" );
    
    		$db->query( 'insert into ' . LISTS_TABLE . " (
    			list_key,
    			site_key,
    			_order
    			) values (
    			'$list_key',
    			'$site',
    			'$order'
    			)" );
    
    		$id = $db->getOne( 'select max(id) from ' . LISTS_TABLE . " where list_key = '$list_key' and site_key = '$site'" );
        }
	}
	else {
        if ( $hasAccess = hasAdminAccess( 'cm_list_edit' ) ) {

            $exists = $db->getOne( 'select count(id) from ' . LISTS_TABLE . " where list_key = '$list_key' and site_key = '$site' and id <> '$id'" );

    		if ( $exists ) {
    			$list_key = $list_key . '_' . time();
    
    			// update $items with current_key = $current_key to list_key in list items table
    
    			$t->assign( 'listKeyExists', true );
    		}
    		
        }
	}
	
	
	if ( $hasAccess ) {

    	// delete all existing list data with this list_key and site_key
    
    	// update the LISTS_TABLE with the new or updated list data
    
    	$db->query( 'update ' . LISTS_TABLE . " set title = '$title', list_key = '$list_key' where id = '$id' and site_key = '$site'" );
    
    	$db->query( 'delete from ' . LISTITEMS_TABLE . " where list_key = '$list_key' and site_key = '$site'" );
    
    	if ( $current_key && $list_key != $current_key ) {
    		$db->query( 'delete from ' . LISTITEMS_TABLE . " where list_key = '$current_key' and site_key = '$site'" );
    	}
    
    	// add the new list data
    
    	$listDataParts = explode( "\n", $listData );
    	$listLabelParts = explode( "\n", $listLabels );
    
    	$numItems = max( sizeof( $listDataParts ), sizeof( $listLabelParts ) );
    
    	for( $i = 0; $i < $numItems; $i++ ) {
    
    		$listDataItem = rtrim( $listDataParts[$i] );
    		$listLabelItem = rtrim( $listLabelParts[$i] );
    
    		$db->query( 'insert into ' . LISTITEMS_TABLE . " (
    			list_key,
    			_order,
    			data,
    			label,
    			site_key
    			) values (
    			'$list_key',
    			'$i',
    			'$listDataItem',
    			'$listLabelItem',
    			'$site'
    			)" );
    	}
	
	}
}

// get $listData and $listLabels using $id

if ( $id ) {

	$listInfo = $db->getRow( 'select * from ' . LISTS_TABLE . " where id = '$id' and site_key = '$site' limit 1" );

	$list_key = $listInfo[list_key];

	$data = $db->getAll( 'select data, label from ' . LISTITEMS_TABLE . " where site_key = '$site' and list_key = '$list_key' order by _order" );

	$listData = array();
	$listLabels = array();

	foreach( $data as $index => $row ) {
		$listData [] = $row[data];
		$listLabels [] = $row[label];
	}

	$t->assign( 'listData', rtrim( implode( "\n", $listData ) ) );
	$t->assign( 'listLabels', rtrim( implode( "\n", $listLabels ) ) );
	$t->assign( 'list', $listInfo );
	
}


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
	$t->assign( 'bodyTemplate', 'manage/listEdit.tpl' );
}

if ( $id )
	$title = "Edit List ({$list_key})";
else
	$title = 'Add List';
	
$session->updateLocation( 'edit_list', $title, array( 'id' ) );

include_once( '../init_bottom.php' );

$t->display( $templateName );



?>