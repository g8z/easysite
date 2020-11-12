<?php
if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );

// get the names of all pages and pass to the template

$sharedPages = getSQLShares( 'page', 'edit' ); 
$pages = $db->getAll( 'select id, if(id in ('.$sharedPages.'), concat(title, \'[shared]\'), title) as title from '. PAGES_TABLE . " where site_key = '$site' or id in ($sharedPages) order by title" );


if ( hasAdminAccess( 'cm_page_add' ) ) {
    $pageIds = array( '' );
    $pageTitles = array( ' - New Page - ' );
} else {
    $pageIds = array();
    $pageTitles = array();
}

if ( hasAdminAccess( 'cm_page_edit' ) || hasAdminAccess( 'cm_page_delete' ) )
foreach( $pages as $page ) {
	$pageIds [] = $page['id'];

	if ( strlen( $page['title'] ) > 30 ) {
		$page['title'] = substr( $page['title'], 0, 29 ) . '...';
	}
	$pageTitles [] = $page['title'] . ' (' . $page[id] . ')';
}

$menus = $db->getAll( 'select id, title from ' . MENUS_TABLE . " where site_key = '$site' order by title" );

$menuIds = array();
$menuNames = array();

foreach( $menus as $menu ) {
	$menuIds [] = $menu['id'];

	$menuNames [] = $menu['title'];
}

$sharedForms = getSQLShares( 'form', 'edit' ); 
$forms = $db->getAll( 'select resource_id as id, if(resource_id in ('.$sharedForms.'), concat(value, \'[shared]\'), value) as title from '.SETTINGS_TABLE." where resource_type='form' and property='title' and ( site_key='$site' or resource_id in ($sharedForms) ) order by value" );
//$forms = $db->getAll( 'select id, form_title as title from ' . FORMS_TABLE . " where site_key = '$site' order by form_title" );

if ( hasAdminAccess( 'cm_form_add' ) ) {
    $formIds = array( '' );
    $formNames = array( ' - New Form - ' );
} else {
    $formIds = array();
    $formNames = array();
}

if ( hasAdminAccess( 'cm_form_edit' ) || hasAdminAccess( 'cm_form_delete' ) )
foreach( $forms as $form ) {
	$formIds [] = $form['id'];

	$formNames [] = $form['title'];
}


$sharedLayers = getSQLShares( 'layer', 'edit' ); 
$layers = $db->getAll( 'select id, if(id in ('.$sharedLayers.'), concat(title, \'[shared]\'), title) as title from '. LAYERS_TABLE . " where (site_key = '$site' or id in ($sharedLayers)) order by title" );
if ( hasAdminAccess( 'cm_layer_add' ) ) {
    $layerIds = array( '' );
    $layerNames = array( ' - New Layer - ' );
} else {
    $layerIds = array();
    $layerNames = array();
}

if ( hasAdminAccess( 'cm_layer_edit' ) || hasAdminAccess( 'cm_layer_delete' ) )
foreach( $layers as $layer ) {
	$layerIds [] = $layer['id'];

	$layerNames [] = $layer['title'];
}


$mod = $db->getAll( 'select * from ' . MODULES_TABLE . "
                            where site_key = '$site' order by module_key" );

$modules = array();

foreach ( $mod as $m ) {

    if ( $_SESSION['cm_auth']['cm_'.$m[module_key]] == $site )
        $modules[] = $m;
}

$moduleList = array();

for ( $i = 0, $n = count( $modules ); $i < $n; $i++ )
{
	$moduleList[$modules[$i][id]] = $modules[$i][title];

    $logo = DOC_ROOT . 'modules/'. $modules[$i][module_key] .'/images/'. $modules[$i][module_key]. '.gif';

	$modules[$i][logo] = $logo;
}

$allSkins = $skin->getAll();// $skin object is from init.php

if ( sizeof( $allSkins ) == 0 ) {
	$allSkins [] = '(no skins present)';
}
else {
	$t->assign( 'useDefaultSkin', '(use default skin)' );
}

$t->assign( 'skins', $allSkins );
$t->assign( 'moduleList', $moduleList );// for the default module select list
$t->assign( 'modules', $modules );



// update the "last updated" date for this site

$db->query( 'update ' . SITES_TABLE . " set last_updated = now() where site_key = '$site'" );

$t->assign( 'page_ids', $pageIds );
$t->assign( 'page_titles', $pageTitles );

$t->assign( 'menu_ids', $menuIds );
$t->assign( 'menu_titles', $menuNames );

$t->assign( 'form_ids', $formIds );
$t->assign( 'form_titles', $formNames );

$t->assign( 'layer_ids', $layerIds );
$t->assign( 'layer_titles', $layerNames );

$t->assign( 'bodyTemplate', 'manage/index.tpl' );

$session->updateLocation( 'cm_index', 'Admin Index' );

include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );

?>