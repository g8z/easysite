<?php

define( 'USER', 1 );
define( 'GROUP', 2 );

function getRestrictedSections( $type, $id ) {

    global $db, $site;

    if ( $type == USER ) {
        $where = "user_id='$id'";
    } else {
        $where = "site_key = '$site' and group_id='$id'";
    }

    // determine the existing permissions of this group

    $restrictedSections1 = $db->getAll( "select resource_id, resource_type from " . PERMISSIONS_TABLE . " where $where order by resource_type" );

    $restrictedSections2 = array();

    // reformat the $restrictedSections array as a hash-table-like structure

    foreach( $restrictedSections1 as $index => $row ) {
        $restrictedSections2[ $row[resource_id] . '_' . $row[resource_type] ] = 1;
    }

    return $restrictedSections2;

}


function getModulePermissions( $restrictedSections2 ) {

    global $db, $site;

    $items = array();
    
    include_once( INCLUDE_DIR . 'internal/class.moduleManager.php' );

    $modMan = new Module_Manager();

    $modules = $modMan->getModules();

    foreach ( $modules as $module ) {

        if ( $site == DEFAULT_SITE || hasAdminAccess( 'cm_'.$module[module_key] ) ) {

        $items[] = array(
                    'id'        => 'cm_'.$module[module_key],
                    'title'     => $module[title],
                    'restricted'=> $restrictedSections2['0_cm_'.$module[module_key]],
                    'indent'    => 1
                );
                
        $modItems = $modMan->callFunction( $module[module_key], 'getPermissions', array( $restrictedSections2, $type ) );

        $items = array_append( $items, $modItems );


        if ( $site != DEFAULT_SITE ) {

                // user can only set permissions that he/she has

                $data = array();

                foreach ( $items as $i ) {
                    if ( hasAdminAccess( $i['id'] ) || $i['id'] == 'comment' )
                        $data[] = $i;
                }

                $items = $data;

        }

        }

    }

    return $items;
}


function fetchAll( $resourceType, $restrictedSections2, $udID ) {

    global $site, $db, $helpOptions;

    switch( $resourceType ) {
        case 'form_section':
            $field = 'field_name';
            $title = 'Form Sections';
            $table = FORMSECTIONS_TABLE;
            break;

        case 'page':
            $field = 'title';
            $title = 'Pages';
            $table = PAGES_TABLE;
            break;

        case 'page_section':
            $field = 'title';
            $title = 'Page Sections';
            $table = SECTIONS_TABLE;
            break;

        case 'layer':
            $field = 'title';
            $title = 'Layers';
            $table = LAYERS_TABLE;
            break;

        case 'menu':
            $field = 'title';
            $title = 'Whole Menus';
            $table = MENUS_TABLE;
            break;

        case 'menu_item':
            $field = 'title';
            $title = 'Single Menu Items';
            $table = MENUITEMS_TABLE;
            break;

        case 'file':
            $field = 'download_name';
            $title = 'Uploaded Files';
            $table = FILES_TABLE;
    }

    if ( $resourceType == 'cm_tools' ) {

        $data = array(
                array(
                    'id'        => 'cm_menu',
                    'title'     => 'Menu Manager',
                    'restricted'=> $restrictedSections2['0_cm_menu']
                ),
                    array(
                        'id'        => 'cm_menu_add',
                        'title'     => 'Add Menu',
                        'restricted'=> $restrictedSections2['0_cm_menu_add'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_menu_edit_structure',
                        'title'     => 'Edit Menu Structure',
                        'restricted'=> $restrictedSections2['0_cm_menu_edit_structure'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_menu_edit_settings',
                        'title'     => 'Edit Menu Settings',
                        'restricted'=> $restrictedSections2['0_cm_menu_edit_settings'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_menu_delete',
                        'title'     => 'Delete Menu',
                        'restricted'=> $restrictedSections2['0_cm_menu_delete'],
                        'indent'    => 1
                    ),
                array(
                    'id'        => 'cm_page',
                    'title'     => 'Page Manager',
                    'restricted'=> $restrictedSections2['0_cm_page']

                ),
                    array(
                        'id'        => 'cm_page_add',
                        'title'     => 'Add Page',
                        'restricted'=> $restrictedSections2['0_cm_page_add'],
                        'indent'    => 1
    
                    ),
                    array(
                        'id'        => 'cm_page_edit',
                        'title'     => 'Edit Page',
                        'restricted'=> $restrictedSections2['0_cm_page_edit'],
                        'indent'    => 1
    
                    ),
                    array(
                        'id'        => 'cm_page_delete',
                        'title'     => 'Delete Page',
                        'restricted'=> $restrictedSections2['0_cm_page_delete'],
                        'indent'    => 1
    
                    ),
                array(
                    'id'        => 'cm_layer',
                    'title'     => 'Layer Manager',
                    'restricted'=> $restrictedSections2['0_cm_layer']
                ),
                    array(
                        'id'        => 'cm_layer_add',
                        'title'     => 'Add Layer',
                        'restricted'=> $restrictedSections2['0_cm_layer_add'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_layer_edit',
                        'title'     => 'Edit Layer',
                        'restricted'=> $restrictedSections2['0_cm_layer_edit'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_layer_delete',
                        'title'     => 'Delete Layer',
                        'restricted'=> $restrictedSections2['0_cm_layer_delete'],
                        'indent'    => 1
                    ),
                array(
                    'id'        => 'cm_settings',
                    'title'     => 'Site Settings',
                    'restricted'=> $restrictedSections2['0_cm_settings']
                ),
                array(
                    'id'        => 'cm_style',
                    'title'     => 'Style Manager',
                    'restricted'=> $restrictedSections2['0_cm_style']
                ),
                    array(
                        'id'        => 'cm_style_add',
                        'title'     => 'Add Style',
                        'restricted'=> $restrictedSections2['0_cm_style_add'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_style_load_from_skin',
                        'title'     => 'Load From Skin',
                        'restricted'=> $restrictedSections2['0_cm_style_load_from_skin'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_style_edit',
                        'title'     => 'Edit Style',
                        'restricted'=> $restrictedSections2['0_cm_style_edit'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_style_delete',
                        'title'     => 'Delete Style',
                        'restricted'=> $restrictedSections2['0_cm_style_delete'],
                        'indent'    => 1
                    ),
                array(
                    'id'        => 'cm_skin',

                    'title'     => 'Skins ' . " [ <a href=\"javascript:launchCentered('help.php?type=skin_permissions',$helpOptions[width],$helpOptions[height],'resizable,scrollbars');\">about skin permissions</a> ] ",

                    'restricted'=> $restrictedSections2['0_cm_skin']
                ),
                array(
                    'id'        => 'cm_list',
                    'title'     => 'List Manager',
                    'restricted'=> $restrictedSections2['0_cm_list']
                ),
                    array(
                        'id'        => 'cm_list_add',
                        'title'     => 'Add Lists',
                        'restricted'=> $restrictedSections2['0_cm_list_add'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_list_edit',
                        'title'     => 'Edit Lists',
                        'restricted'=> $restrictedSections2['0_cm_list_edit'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_list_delete',
                        'title'     => 'Delete Lists',
                        'restricted'=> $restrictedSections2['0_cm_list_delete'],
                        'indent'    => 1
                    ),
                array(
                    'id'        => 'cm_form',
                    'title'     => 'Form Builder',
                    'restricted'=> $restrictedSections2['0_cm_form']
                ),
                    array(
                        'id'        => 'cm_form_add',
                        'title'     => 'Add Form',
                        'restricted'=> $restrictedSections2['0_cm_form_add'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_form_edit',
                        'title'     => 'Edit Form',
                        'restricted'=> $restrictedSections2['0_cm_form_edit'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_form_delete',
                        'title'     => 'Delete Form',
                        'restricted'=> $restrictedSections2['0_cm_form_delete'],
                        'indent'    => 1
                    ),
                array(
                    'id'        => 'cm_report',
                    'title'     => 'Reports Manager',
                    'restricted'=> $restrictedSections2['0_cm_report']
                ),
                array(
                    'id'        => 'cm_file',
                    'title'     => 'File Manager',
                    'restricted'=> $restrictedSections2['0_cm_file']
                ),
                array(
                    'id'        => 'cm_users',
                    'title'     => 'Users & Groups',
                    'restricted'=> $restrictedSections2['0_cm_users']
                ),
                    array(
                        'id'        => 'cm_users_add',
                        'title'     => 'Add Users',
                        'restricted'=> $restrictedSections2['0_cm_users_add'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_users_edit',
                        'title'     => 'Edit Users',
                        'restricted'=> $restrictedSections2['0_cm_users_edit'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_users_import',
                        'title'     => 'Import Users',
                        'restricted'=> $restrictedSections2['0_cm_users_import'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_users_download',
                        'title'     => 'Download Users',
                        'restricted'=> $restrictedSections2['0_cm_users_download'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_users_delete',
                        'title'     => 'Delete Users',
                        'restricted'=> $restrictedSections2['0_cm_users_delete'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_users_gr_add',
                        'title'     => 'Add Groups',
                        'restricted'=> $restrictedSections2['0_cm_users_gr_add'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_users_gr_edit',
                        'title'     => 'Edit Groups',
                        'restricted'=> $restrictedSections2['0_cm_users_gr_edit'],
                        'indent'    => 1
                    ),
                    array(
                        'id'        => 'cm_users_gr_delete',
                        'title'     => 'Delete Groups',
                        'restricted'=> $restrictedSections2['0_cm_users_gr_delete'],
                        'indent'    => 1
                    ),
                array(
                    'id'        => 'cm_backup',
                    'title'     => 'Backup/Restore',
                    'restricted'=> $restrictedSections2['0_cm_backup']
                ),
                array(
                    'id'        => 'cm_share',
                    'title'     => 'Shares',
                    'restricted'=> $restrictedSections2['0_cm_share']
                ),
                array(
                    'id'        => 'cm_module',
                    'title'     => 'Modules (specify below)',
                    'restricted'=> $restrictedSections2['0_cm_module']
                )
            );

            if ( $site != DEFAULT_SITE ) {

                // user can only set permissions that he/she has

                $items = array();

                foreach ( $data as $d ) {
                    if ( hasAdminAccess( $d['id'] ) || $d['id'] == 'comment' )
                        $items[] = $d;
                }

                $data = $items;

                if ( hasAdminAccess( 'cm_module' ) )
                    $data = array_append( $data, getModulePermissions( $restrictedSections2 ) );
            } else

            $data = array_append( $data, getModulePermissions( $restrictedSections2 ) );
    }
    else {
    	
    	if ( $resourceType != 'form' )
        	$data = $db->getAll( "select id, $field as title from $table where $field <> '' and site_key = '$site' order by $field" );
        else {
       		$data = $db->getAll( 'select resource_id as id, value as title from '.SETTINGS_TABLE." where resource_type='form' and property='title' and site_key='$site' order by value" );
       		$title = 'Forms';
        }
        
        //$data['title'] = htmlentities( $data['title'] );


        // add "All ..." option

        array_unshift( $data, array( 'id' => '0', 'title' => "Allow access to all $title (overrides the below settings)" ) );
    }

    // determine which parts are already restricted by this group

    if ( $resourceType != 'cm_tools' ) {
        foreach( $data as $index => $row ) {

            $row['restricted'] = ( $restrictedSections2[ $row[id] . '_' . $resourceType ] == true );

            // if we are adding a new group, then check the "Allow All" options
            // by default (but only for Forms, Pages, and Uploaded Files)

            if ( $row['id'] == '0' && !$udID )
                $row['restricted'] = true;

            $data[$index] = $row;
        }
    }
    
    return $data;
}


function updatePermissions( $type, $id, $site_key ) {

    global $db;

    $field = ( $type == USER ) ? 'user_id' : 'group_id';

    // get the existing permissions
    $permissions = $db->getAll( 'select resource_type, resource_id from ' . PERMISSIONS_TABLE . " where site_key = '$site_key' and $field = '$id'" );

    $oldPermissions = array();

    foreach( $permissions as $index => $row ) {
        $oldPermissions [] = $row[resource_type] . '_' . $row[resource_id];
    }

    $permissionsSection = false;
    $newPermissions = array();

    // get the new permissions for this group
    foreach( $_POST as $key => $value ) {

        if ( $key == 'start_permissions' ) {
            $permissionsSection = true;
            continue;
        }
        else if ( $key == 'end_permissions' )
            break;

        if ( stristr( $key, 'cm_tools_' ) )
            $key = str_replace( 'cm_tools_', '', $key ) . '_0';

        if ( $permissionsSection )
            $newPermissions [] = $key;
    }

    // the things that we need to delete from DB
    $intersection = array_intersect( $oldPermissions, $newPermissions );

    $deleteItems = array_diff( $oldPermissions, $intersection );
    $addItems = array_diff( $newPermissions, $intersection );

    $parts = array();

    // add new items
    foreach( $addItems as $item ) {

        // if the item is a content-management tool item, then resource id = 0

        $parts = explode( '_', $item );

        $resourceID = array_pop( $parts );
        $resourceType = implode( '_', $parts );

        $db->query( 'insert into ' . PERMISSIONS_TABLE . "
        (
            resource_type,
            resource_id,
            site_key,
            $field
        )
        values
        (
            '$resourceType',
            '$resourceID',
            '$site_key',
            '$id'
        )" );
    }

    $parts = array();

    // delete items
    foreach( $deleteItems as $item ) {
        $parts = explode( '_', $item );

        $resourceID = array_pop( $parts );
        $resourceType = implode( '_', $parts );

        $db->query( 'delete from ' . PERMISSIONS_TABLE . " where
            resource_type   = '$resourceType' and
            resource_id     = '$resourceID' and
            site_key        = '$site_key' and
            $field          = '$id'" );
    }
}

?>