<?php

if ( !defined( 'SMARTY_DIR' ) )
  include_once( '../../init.php' );
  
  
$menu = new Menu();
  
function linkFunction( $row ) {
    return "javascript: writePath(t0.getSelectedNode(), $row[id] );";
}
  
function getCategoryTitles( $module_key, $parent=0 ) {
    
    global $db;

    $script = '';
    
    $cats = array();

    $cats = $db->getAll( 'select id, title from ' . MODULECATEGORIES_TABLE . " where module_key='$module_key' and parent='$parent'" );
    
    foreach( $cats as $num=>$cat ) {
        
        $cat[child] = getCategoryTitles( $module_key, $cat[id] );
        $cats[$num] = $cat;
    }
    
    return $cats;
}

$modules = $db->getAll( 'select id, title, module_key from ' . MODULES_TABLE . " where site_key='$site'" );

$count = count( $modules );

if ( !$module )
    $module = $modules[0]['id'];

// -------------------------------------
// Find module_key of the current module
// -------------------------------------
$found = 0;
while ( !$found ) {
    $m = current( $modules );
    $found = ( $module == $m[id] );
    next( $modules );
}

$menuArray = getCategoryTitles( $m[module_key], 0 );

$nodes = $menu->getTreeNodes( $menuArray, 'linkFunction' );

$found = 0;

if ( strlen( $nodes ) )
    $found = 1;

$nodes = 'var TREE_NODES = [' . $nodes . '];';

$modules = getAssocArray( 'id', 'title', $modules );

$t->assign( 'found', $found );
$t->assign( 'count', $count );
$t->assign( 'modules', $modules );
$t->assign( 'module', $module );
$t->assign( 'nodes', $nodes );
$t->assign( 'module_name', $m[title] );

include_once( '../../init_bottom.php' );

$t->display( 'manage/popupCategory.tpl' );


?>


