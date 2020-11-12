<?php

if ( $manage ) {
    require_once '../../../init.php';
    include_once '../../include/functions.php';
} else {
    require_once '../../config.php';
    require_once '../../init.php';
    include_once '../include/functions.php';
}

include_once( INCLUDE_DIR . 'internal/class.navigation.php' );

include_once( 'api.php' );

$realtyOptions = realty_getOptions();
$t->assign( 'realtyOptions', $realtyOptions );

define( 'REALTYITEMS_TABLE', DB_PREFIX . '_realty_items' );


// determine the skin for this module

$moduleKey = 'realty';

/*$moduleInfo = $db->getRow( 'select id, skin_id from ' . MODULES_TABLE . " where module_key = '$moduleKey' and site_key = '$site'" );

if ( $moduleInfo[skin_id] > 0 ) {
	$skin->loadAll( $moduleInfo[skin_id] );
}*/

// get the realty module settings

$realtySettings1 = $db->getAll( 'select * from ' . MODULESETTINGS_TABLE . " where site_key = '$site' and module_key = 'realty'" );
$realtySettings = array();

foreach( $realtySettings1 as $index => $row ) {
	$realtySettings[$row[name]] = $row[value];
}

$t->assign( 'realtySettings', $realtySettings );

// get the category list (used in all forms, search + manage forms)

$add_fields = array( 'site_key', 'module_key' );
$add_values = array( $site, 'realty' );

$category = new Category( $db, MODULECATEGORIES_TABLE, $add_fields, $add_values );

$categories = $category->getCategoryArray();

$t->assign( 'categories', $categories );

?>