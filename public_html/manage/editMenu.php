<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );

if ( !hasAdminAccess( 'cm_menu' ) ) {
    include_once( '../init_bootom.php' );
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
    exit;
}

$hasAccess = true;

if ( $_GET['menu_id'] )
    $menu_id = $_GET['menu_id'];
else if ( $_POST['menu_id'] )
    $menu_id = $_POST['menu_id'];
else
    $menu_id = $currentMenuId;

$table = MENUITEMS_TABLE;

if ( $_POST['addMenu'] ) {

    if ( $hasAccess = hasAdminAccess( 'cm_menu_add' ) ) {

        $newMenuName = trim( $_POST['newMenuName'] );

        if ( $newMenuName )
            $db->query( 'insert into ' . MENUS_TABLE . " ( title, site_key ) values ( '$newMenuName', '$site' )" );

        // re-assign the menu id to this newly added menu

        $menu_id = $db->getOne( 'select max(id) from ' . MENUS_TABLE . " where site_key = '$site'" );
    }

}

elseif ( $_POST['deleteMenu'] ) {

    if ( $hasAccess = hasAdminAccess( 'cm_menu_delete' ) ) {

        $deleteMenuItemId = $_POST['menu_id'];

        $db->query( 'delete from ' . MENUS_TABLE . " where id = '$deleteMenuItemId' and site_key = '$site'" );

        // Deleting all menu items
        $db->query( "delete from " . $table . " where menu_id = '$deleteMenuItemId' and site_key = '$site'" );

        // Deleting all menu settings
        $db->query( "delete from " . SETTINGS_TABLE . " where resource_type='menu' and resource_id='$deleteMenuItemId' and site_key = '$site'" );

        // deleting cache
        deleteCache( 'menu_js', $deleteMenuItemId );
		deleteCache( 'menu_css', $deleteMenuItemId );
		deleteCache( 'menu_links', $deleteMenuItemId );

        // possibly delete skin settings, too?

        $menu_id = "";

    }

}


// $hasAccess is already set depending on adding or updating menu


// if there is no menu_id, then get the first available menu from the database
if ( !$menu_id )
    $menu_id = $db->getOne( 'select min(id) from ' . MENUS_TABLE . " where site_key = '$site'" );

$menu = $db->getRow( 'select title, site_key from '.MENUS_TABLE." where id='$menu_id'" );

$add_fields = array( 'menu_id', 'site_key' );
$add_values = array( $menu_id, $menu['site_key'] );
$category = new Category( $db, $table, $add_fields, $add_values );

if ( $_POST['formIsSubmitted'] ) {

if ( !$_POST['addMenu'] )
    $hasAccess = hasAdminAccess( 'cm_menu_edit_structure' );

if ( $hasAccess ) {

    if ( $_POST['killid'] ) {
        $kill = $_POST['killid'];
        $_POST[$kill] = 1;
    }

    foreach( $_POST as $key => $val ) {

        list( $action, $id ) = explode( "_", $key );

        if ( $id == 'id' || ( !trim( $id ) && $action != 'insert' ) )
            continue;

        // update title and url

        if ( $id ) {

            $linkedResource = addslashes( $_POST['link_'.$id] );

            $resource_type = '';
            $resource_id = '';

            if ( preg_match( '/^(page|form|file|module|url|frmurl|modcat|report)(.+)$/', $linkedResource, $matches ) ) {
                $resource_type = $matches[1];
                $resource_id = $matches[2];
            }

            $db->query( "update $table set
                resource_type   = '" . $resource_type . "',
                resource_id     = '" . $resource_id . "'
                    where
                        id          = '$id' and
                        site_key    = '$site' and
                        menu_id     = '$menu_id'" );
        }

        $category->execute( $action, $id );

        // this is for updating menu cache
        if ( $action == 'kill' ) {
        	$db->query( 'update '. MENUITEMS_TABLE." set last_change = NOW() where menu_id = '$menu_id' " );
        }
    }

} // hasAccess

}


$maxLevel = $category->getMaxLevel();


// get a list of all the files in this directory

if ( !$startid ) {
    $startid = 0;
    $startLevel = 0;
}
else {
    $startLevel = $category->getLevel( $startid );
}

$found = false;

// array for storing all table contents
$lines_array = array();
$linkAdd = array();

$maxLinkLen = 30;

function getLinkedResource( $data ) {
    return $data[resource_type].$data[resource_id];
}

/*function getPageLinkAdd( $data )
{
    global $db, $tempSiteSettings;

    if ( $data[resource_type] == 'page' ) {

        $id = $db->getOne( 'select id from ' . PAGES_TABLE . " where page_key='$data[resource_id]'" );
        if ( !$id )
            $data[resource_id] = $id;
    }
    else
        return '';

    return $data[resource_id];
}
*/
function getLinkAdd( $data ) {
    return ( $data[resource_type] == 'url' ) ? $data[resource_id] : '';
}

function getFrmLinkAdd( $data ) {
    return ( $data[resource_type] == 'frmurl' ) ? $data[resource_id] : '';
}

function getModCatLinkValue( $data ) {
    return ( $data[resource_type] == 'modcat' ) ? $data[resource_id] : '';
}

function getModCatLinkTitle( $data ) {
    global $db, $maxLinkLen;

    if ( $data[resource_type] == 'modcat' ) {
        list( $module_id, $cat_id, $overwrite) = split( '_', $data[resource_id] );

        $module = $db->getRow( 'select title, module_key from '. MODULES_TABLE ." where id='$module_id'" );
        $moduleTitle = $module['title'];
        $moduleKey = $module['module_key'];

        $catTitle = '';

        while ( $cat_id ) {

            $category = $db->getRow( 'select title, parent from '.MODULECATEGORIES_TABLE." where id='$cat_id' and module_key='$moduleKey'" );

            if ( $category ) {
                $catTitle = ' > ' . $category['title'] . $catTitle;
            }

            $cat_id = $category['parent'];

        }

        return cut ( ' > '. $moduleTitle . $catTitle, $maxLinkLen );

    } else
        return;
}

$drawFields = array( 'linkedResource'   => getLinkedResource,
                     //'linkedPage'       => getPageLinkAdd,
                     'linkAdd'          => getLinkAdd,
                     'frmLinkAdd'       => getFrmLinkAdd,
                     'modcatLinkTitle'       => getModCatLinkTitle,
                     'modcatLinkValue'       => getModCatLinkValue,
                   );

$category->setDrawFields( $drawFields );
$category->clearLines();
$lines_array = $category->draw( $startid, $levelLimit, $noedit );

$t->assign( 'lines_array', $lines_array );
$t->assign( 'span', $category->span() );
$t->assign( 'found', !( $category->isEmpty() ) );
$t->assign( 'noedit', $noedit );
$t->assign( 'levelLimit', $levelLimit );
$t->assign( 'startid', $startid );
$t->assign( 'startLevel', $startLevel );

$sharedMenuIds = @array_keys( $_SESSION['shares']['menu'] );
$shared = getSQLShares( 'menu', 'edit' );

$data = $db->getAll( 'select *, if(id in ('.$shared.'), concat(title, \'[shared]\'), title) as title from ' . MENUS_TABLE . " where (site_key = '$site' or id in ($shared)) order by title" );


$menuIds = array();
$menuTitles = array();

foreach( $data as $row ) {
    $menuIds [] = $row[id];

    $menuTitles [] = $row[title];
}

// Check if menu exists
if ( count( $menuIds ) )
    $menuExists = 1;
else
    $menuExists = 0;

$t->assign( 'menuExists', $menuExists );

$t->assign( 'menu_id', $menu_id );
$t->assign( 'menuIds', $menuIds );
$t->assign( 'menuTitles', $menuTitles );


// get all existing objects for the "link to" combo box

$sharedPages = getSQLShares( 'page' );
$sharedForms = getSQLShares( 'form' );
$sharedFiles = getSQLShares( 'file' );
$sharedReports = getSQLShares( 'report' );

$allPages = $db->getAll( "select id, if(id in ($sharedPages), concat(title, '[shared]'), title) as title, page_key from " . PAGES_TABLE . " where site_key = '$site' or id in ($sharedPages) order by title" );
//$allForms = $db->getAll( "select *, if(id in ($sharedForms), concat(form_title, '[shared]'), form_title) as form_title from " . FORMS_TABLE . " where site_key = '$site' or id in ($sharedForms) order by form_title" );
$allForms = $db->getAll( 'select resource_id as id, property, if(resource_id in ('.$sharedForms.'), concat(value, \'[shared]\'), value) as value from '.SETTINGS_TABLE." where resource_type='form' and (property='title' or property='form_key') and (site_key='$site' or resource_id in ($sharedForms) ) order by value" );
$allFiles = $db->getAll( "select *, if(id in ($sharedFiles), concat(download_name, '[shared]'), download_name) as download_name from " . FILES_TABLE . " where site_key = '$site' or id in ($sharedFiles) order by download_name" );
$allModules = $db->getAll( "select * from " . MODULES_TABLE . " where site_key = '$site' order by title" );
$allReports = $db->getAll( "select *, if(id in ($sharedPages), concat(name, '[shared]'), name) as name from " . REPORTS_TABLE . " where site_key = '$site' or id in ($sharedReports) order by name" );

$objects['none'] = 'no link';

foreach ( $allPages as $page ) {

    //if ( $system->settings[use_page_key] == 'yes' && $page[page_key] )
    //    $page[id] = $page[page_key];

    $objects['page'.$page[id]] = 'page - '.cut( $page[title], $maxLinkLen );
}

foreach( $allForms as $num=>$form ) {
	$tallForms[$form[id]][$form[property]] = $form[value];
}
$allForms = $tallForms;
foreach ( $allForms as $id=>$form ) {

	//if ( $system->settings[use_page_key] == 'yes' && $form[form_key] )
        //$id = $form[form_key];

    $objects['form'.$id] = 'form - '.cut( $form[title], $maxLinkLen );
}
foreach ( $allFiles as $file ) {
    $objects['file'.$file[id]] = 'file - '.cut( $file[download_name], $maxLinkLen );
}
foreach ( $allReports as $file ) {
    $objects['report'.$file[id]] = 'report - '.cut( $file[name], $maxLinkLen );
}

// do not need this since we have category selection
/*
foreach ( $allModules as $module ) {
    $objects['module'.$module[module_key]] = 'module - '.cut( $module[module_key], $maxLinkLen );
}
*/

$objects[url] = 'new url';
$objects[modcat] = 'new module category';
// iframed urls temporarily disabled
//$objects[frmurl] = 'new framed url';

for ($i1=0, $n1 = count( $lines_array ); $i1 < $n1; $i1++) {
    for ($i=0, $n = count( $lines_array[$i1] ); $i < $n; $i++) {

        if ( strlen( $lines_array[$i1][$i]['linkAdd'] ) ) {
            $allObjects[$lines_array[$i1][$i]['id']] =
                array_merge(
                    $objects,
                    array(
                        'url' . stripslashes( $lines_array[$i1][$i]['linkAdd'] ) => 'url - '.stripslashes( $lines_array[$i1][$i]['linkAdd'] )
                    ) );
        }
        else if ( strlen( $lines_array[$i1][$i]['frmLinkAdd'] ) ) {
            $allObjects[$lines_array[$i1][$i]['id']] =
                array_merge(
                    $objects,
                    array(
                        'frmurl' . stripslashes( $lines_array[$i1][$i]['frmLinkAdd'] ) => 'frm url - '.stripslashes( $lines_array[$i1][$i]['frmLinkAdd'] )
                    ) );
        }
        else if ( strlen( $lines_array[$i1][$i]['modcatLinkValue'] ) ) {
            $allObjects[$lines_array[$i1][$i]['id']] =
                array_merge(
                    $objects,
                    array(
                        'modcat' . stripslashes( $lines_array[$i1][$i]['modcatLinkValue'] ) => stripslashes( $lines_array[$i1][$i]['modcatLinkTitle'] )
                    ) );
        }
        else
            $allObjects[$lines_array[$i1][$i]['id']] = $objects;
    }
}

$t->assign( 'allObjects', $allObjects );


$permissions = array(
    'add' => hasAdminAccess( 'cm_menu_add' ),
    'edit_settings' => hasAdminAccess( 'cm_menu_edit_settings' ),
    'edit_structure' => hasAdminAccess( 'cm_menu_edit_structure' ),
    'delete' => hasAdminAccess( 'cm_menu_delete' )
);

$t->assign( 'permissions', $permissions );

$session->updateLocation( 'menu_manager', "Menu Manager ($menu[title])", array( 'menu_id' ) );

deleteCache( 'menu_js', $menu_id );
deleteCache( 'menu_css', $menu_id );

include_once( '../init_bottom.php' );

if ( !$hasAccess ) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
    $t->assign( 'bodyTemplate', 'manage/editMenu.tpl' );
}

$t->display( $templateName );
?>