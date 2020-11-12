<?php

include_once( '../config.php' );
include_once '../include/adminFunctions.php';

$table = MODULECATEGORIES_TABLE;

$startid = 0;
$startLevel = 0;
$levelLimit = 0;

// determine which module we are editing by analyzing the directory structure

$moduleInfo = $db->getRow( "select * from $table where site_key = '$site' and module_key = '$moduleKey'" );

$add_fields = array( 'site_key', 'module_key' );
$add_values = array( $site, $moduleKey );

function getCustomLink( $data ) {
	
	return "<a href='settings.php?cat_id=$data[id]'><img border=0 src='".DOC_ROOT."images/editor.gif' alt='Advanced Category Editor'></a>";
}


if( hasAdminAccess( 'cm_module' ) && hasAdminAccess( 'cm_'.$moduleKey ) && hasAdminAccess( 'cm_'.$moduleKey.'_manage_categories' ) ) {

    $category = new Category( $db, $table, $add_fields, $add_values );

    // Assign delete handler (which function to process when a category is deleted)
    $category->onDelete( 'onAlbumDelete' );
    
    foreach( $_POST as $key => $val ) {

        list( $action, $id ) = explode( '_', $key );

        if ( $id == 'id' || ( !trim( $id ) && $action != 'insert' ) )
            continue;

        $category->execute( $action, $id );
    }

    $drawFields = array( 'customLink'   => getCustomLink );
    
    $category->setDrawFields( $drawFields );
    $category->clearLines();

    $data = $category->draw( $startid, $levelLimit, $noedit );
    
    $t->assign( 'data', $data );
    $t->assign( 'span', $category->span() );
    $t->assign( 'noedit', $noedit );
    $t->assign( 'levelLimit', $levelLimit );
    $t->assign( 'startid', $startid );
    $t->assign( 'startLevel', $startLevel );

    // insert the body content
    $t->assign( 'bodyTemplate', 'modules/gallery/manage/categories.tpl' );

} else {
    noAccessMessage( 'modules/gallery/navigation.tpl' );
}

// display the main template, with body content embedded

$session->updateLocation( 'manage_gallery_categories', 'Gallery Categories' );
include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );


?>