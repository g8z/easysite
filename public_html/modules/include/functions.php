<?php

// ---------------------------------
// This file contains functions that
// can be used for every module
// ---------------------------------


/**
* Shows the 'no access' message
*/
function noAccessMessage( $navigation ) {
    global $t;

    $t->assign( 'navigation', $navigation );
    $t->assign( 'bodyTemplate', 'modules/noAccess.tpl' );
}


/**
* Returns the permission ids array from getPermissions functrion
*/
function getPermissionIds( $permissions ) {

    $ids = array();
    foreach( $permissions as $p ) {
        $ids[] = $p[id];
    }

    return $ids;
}


function getCategoryPermissionsArray( $moduleKey, $key,  $restrictedSections2, $indent, $parent ) {

    global $db, $site;

    $items = array();

    $categories = $db->getAll( 'select id, title from '. MODULECATEGORIES_TABLE ." where site_key='$site' and module_key='$moduleKey' and parent='$parent' order by _order" );

    foreach ( $categories as $category ) {
        $items[] = array(
                        'id'        => "cm_{$moduleKey}_{$key}_{$category[id]}",
                        'title'     => $category[title],
                        'restricted'=> $restrictedSections2["0_cm_{$moduleKey}_{$key}_{$category[id]}"],
                        'indent'    => $indent
                    );

        $items = array_append( $items, getCategoryPermissionsArray( $moduleKey, $key,  $restrictedSections2, $indent+1, $category[id] ) );
    }

    return $items;
}
?>