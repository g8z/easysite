<?php

$manage = true;
include_once( '../config.php' );

$table = MODULECATEGORIES_TABLE;

$startid = 0;
$startLevel = 0;
$levelLimit = 0;

// determine which module we are editing by analyzing the directory structure

$moduleInfo = $db->getRow( "select * from $table where site_key = '$site' and module_key = 'realty'" );

$add_fields = array( 'site_key', 'module_key' );
$add_values = array( $site, 'realty' );


function onCategoryDelete( $id ) {
	// delete all real estate listings in this category

	global $db;
	$db->query( 'delete from ' . REALTYITEMS_TABLE . " where cat_id = '$id'" );
}

if( hasAdminAccess( 'cm_module' ) && hasAdminAccess( 'cm_'.$moduleKey ) && hasAdminAccess( 'cm_'.$moduleKey.'_manage_categories' ) ) {

    $category = new Category( $db, $table, $add_fields, $add_values );

    // Assign delete handler (which function to process when a category is deleted)
    $category->onDelete( 'onCategoryDelete' );

    foreach( $_POST as $key => $val ) {

    	list( $action, $id ) = explode( '_', $key );

    	if ( $id == 'id' || ( !trim( $id ) && $action != 'insert' ) )
    		continue;

    	$category->execute( $action, $id );
    }

    $category->clearLines();

    $data = $category->draw( $startid, $levelLimit, $noedit );

    $t->assign( 'data', $data );
    $t->assign( 'span', $category->span() );
    $t->assign( 'noedit', $noedit );
    $t->assign( 'levelLimit', $levelLimit );
    $t->assign( 'startid', $startid );
    $t->assign( 'startLevel', $startLevel );

    // insert the body content
    $t->assign( 'bodyTemplate', 'modules/realty/manage/categories.tpl' );
} else {
    noAccessMessage( 'modules/realty/navigation.tpl' );
}

$session->updateLocation( 'realty_categories', 'Realty Categories' );
include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );


?>