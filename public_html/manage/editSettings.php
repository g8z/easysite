<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );

$menuID = intval( $_REQUEST['menu_id'] );
$reportID = intval( $_REQUEST['reportID'] );
$formID = intval( $_REQUEST['formID'] );
$skin_id = intval( $_REQUEST['skin_id'] );

if ( $menuID ) {
	
    $menu_level = $_REQUEST['menu_level'];
    
	deleteCache( 'menu_js', $menuID );
	deleteCache( 'menu_css', $menuID );

	if ( !is_numeric( $menu_level ) )
        $menu_level = -1; // all levels
        
    $param = $menu_level;

    if ( $menu_level == -1 ) 
        $paramWhere = '(param=-1 or param=\'\')';
    else
        $paramWhere = "param='$menu_level'";
        
    $resourceType = 'menu';
    $resourceId = $menuID;
    
	// Two types of menu possible
	$standard 	= 'Standard';
	$tree 		= 'Tree';
	
	$all = "Standard Tree";

    $shared = getSQLShares( 'menu', 'edit' ); 
	$resourceTitle = $db->getOne( 'select if(id in ('.$shared.'), concat(title, \'[shared]\'), title) as title  from ' . MENUS_TABLE . " where id = '$menuID' and (site_key = '$site' or id in ($shared)) limit 1" );
	
    $t->assign( 'resourceTitle', "Edit \"$resourceTitle\" Menu Settings" );
    $t->assign( 'resourceDesc', 'These are global settings for your website\'s menu. For any single menu item, these settings may be over-ridden by using the small, pencil icon in the Menu Manager.' );
    $t->assign( 'returnLink', "[ <a href=editMenu.php?menu_id=$menuID>Return to the Menu Manager</a> ]" );
    $t->assign( 'menuID', $menuID );

}
else if ( $formID ) {
    
    $resourceType = 'form';
    $resourceId = $formID;
	$paramWhere = 1;
	
    $t->assign( 'returnLink', "[ <a href=editForms.php?form_id=$formID>Continue Editing This Form</a> ]" );
    $t->assign( 'formID', $formID );

}else if ( $reportID ) {
    
    $resourceType = 'report';
    $resourceId = $reportID;
	$paramWhere = 1;
	
    $shared = getSQLShares( 'report', 'edit' ); 
	$resourceTitle = $db->getOne( 'select if(id in ('.$shared.'), concat(name, \'[shared]\'), name) as name from ' . REPORTS_TABLE . " where id = '$reportID' and (site_key = '$site' or id in ($shared)) limit 1" );
	
    $t->assign( 'resourceTitle', "Edit \"$resourceTitle\" Report Settings" );
    $t->assign( 'returnLink', "[ <a href=reports.php?id=$reportID>Return to the Report Manager</a> ]" );
    $t->assign( 'reportID', $reportID );

}
else if ( $area ) {
	
	if ( $skin_id ) {
	    
	    $shared = getSQLShares( 'skin', 'edit' ); 
		$skinTitle = $db->getOne( 'select if(id in ('.$shared.'), concat(name, \'[shared]\'), name) as name from ' . SKINS_TABLE . " where id = '$skin_id' and (site_key = '$site' or id in ($shared))" );
	
	    $t->assign( 'resourceDesc', 'NOTE: You are editing the styles associated with the skin named "'.$skinTitle.'". You will not be able to observe these styles until the skin is loaded.' );
	    $t->assign( 'returnLink', "[ <a href=editSkins.php?skin_id=$skin_id>Return to the Skin Manager</a> ]" );
		$t->assign( 'skin_id', $skin_id );
		$resourceWhere = "skin_id='$skin_id' and ";
		
	}
	else {
		$resourceWhere = "skin_id=0 and ";
	}
	
	$resourceType = 'layout_item';
	$resourceId = $area;
	$areas = array(
		'visible' => 'Visible Area',
		'main' => 'Main Area',
		'footer' => 'Footer',
		'screen' => 'Screen Area',
		'a1' => 'A1 Area',
		'a2' => 'A2 Area',
		'a3' => 'A3 Area',
		'a4' => 'A4 Area',
		'a5' => 'A5 Area',
		'a6' => 'A6 Area',
		'a7' => 'A7 Area',
		'a8' => 'A8 Area',
	);	
	
	$paramWhere = 1;
	
	$t->assign( 'resourceTitle', "Edit " . $areas[$area]. " Settings" );
	$t->assign( 'area', $area );
}
else {
    
    $resourceType = 'site';
    $resourceId = $site;
	$paramWhere = 1;
}

$shared = getSQLShares( $resourceType ); 
$resourceWhere .= "resource_type='$resourceType' and resource_id='$resourceId' and $paramWhere and (site_key='$site' or resource_id in ($shared))";

$phpDateFormats = "Date/Time Format [ <a href=\"javascript:launchCentered('help.php?type=date_codes',$helpOptions[width],$helpOptions[height],'resizable,scrollbars');\">view codes</a> ]";

include_once( 'settingsList.php' );

// get the parent site

if ( $_SESSION['es_auth']['user_site_key'] == $site ) {

	// we are in a user-specific site
	// determine the parent site, so that we can copy over the settings

	$parentSite = $_SESSION['es_auth']['site_key'];
	
	//$userSite = $site;
	//$site = $parentSite;

	$skin->setParentSite( $parentSite );
}

if ( $loadFromSkin && !$_POST['formIsSubmitted'] ) {

	$skin->makeDefault( $loadFromSkin, SETTINGS_TABLE );

	// reload the skin settings (thus overriding the settings gotten in init.php)
}

if ( $_POST['formIsSubmitted'] ) {

	// set last update for this site (so that we can get the current cached images)

	// this may not be necessary... ?
	include_once( INCLUDE_DIR . 'internal/class.site.php' );
	include_once( INCLUDE_DIR . 'internal/class.File.php' );
	
	$siteObj = new ES_Site( $db );
	$siteObj->setLastUpdate( $site );

    $skip = true;

    foreach( $_POST as $key => $value ) {

        if ( $key == 'startFields' )
            $skip = false;

        if ( $skip )
            continue;
            
        if ( is_array( $value ) )
        	$value = implode( ',', $value );

		// NOTE: this routine may change so that we are not required to have an 'id' field

        // check for the existence of this property - add if does not exist
        $s = $db->getAll( 'select id, value from ' . SETTINGS_TABLE . " where property = '$key' and $resourceWhere" );

        $n = @count( $s );
        if ( $n>1 ) {
        	$db->query( 'delete from '.SETTINGS_TABLE. " where property = '$key' and $resourceWhere" );
        }
        if ( !$n || $n>1 ) {
            $db->query( 'insert into ' . SETTINGS_TABLE . " ( site_key, property, value, resource_type, resource_id, param ) values ( '$site', '$key', '$value', '$resourceType', '$resourceId', '$param' )" );
        }
        else if ( $n==1 ) {
        	$setId = $s[0]['id'];
            $db->query( 'update ' . SETTINGS_TABLE . " set value = '$value' where id = '$setId'" );
            
            // call onChange setting function
            if ( function_exists( $siteSettings[$key][3] ) ) {
                $siteSettings[$key][3]( $key, $s[value], $value );
            }
            if ( function_exists( $formSettings[$key][3] ) ) {
                $formSettings[$key][3]( $key, $s[value], $value );
            }
            
        }
    }

	// delete a background image, horizontal color bar, or vertical color bar
    if ( $_POST['deleteImage'] ) {
    	$key = str_replace( 'delete_', '', $_POST['deleteImage'] );

    	$db->query( 'update ' . SETTINGS_TABLE . " set value = '' where property = '$key' and $resourceWhere" );

		// get the primary key id of this item

		$imgId = $db->getOne( 'select id from ' . SETTINGS_TABLE . " where property = '$key' and $resourceWhere" );

    	// remove the cached image
    	// $c is the 'Cacher' class object, created in init.php

		$c->_table = SETTINGS_TABLE;
		$c->_id = $imgId;
		$c->_field = 'value';
		$c->remove();
    }


	foreach( $_FILES as $key => $data ) {

		$file = new File( $key );
		
		if ( $file->isUploaded() ) {
			
			$value = $file->getContent();
			
	        // check for the existence of this property - add if does not exist
	        $id = $db->getOne( 'select id from ' . SETTINGS_TABLE . " where property = '$key' and $resourceWhere limit 1" );
	
	        if ( !$id ) {
	            $db->query( 'insert into ' . SETTINGS_TABLE . " ( site_key, property, value, resource_type, resource_id, param ) values ( '$site', '$key', '$value', '$resourceType', '$resourceId', '$param' )" );
	        }
	        else {
	            $db->query( 'update ' . SETTINGS_TABLE . " set value = '$value' where id = '$id'" );
	
	            // update the cache
				$c->_table = SETTINGS_TABLE;
				$c->_id = $id;
				$c->_field = 'value';
				$c->remove();
	        }
        
		}
		
		$file->delete();
		unset( $file );
	}

    // reload the site settings so that we can immediately see the changes in settings
    
    $system->getSettings();
    
}


$styleList = $system->getStyleList();
$t->assign( 'styleList', $styleList );

// outlook & html menus have been temporarily disabled b/c they don't work yet
$t->assign( 'typeCombo', array( $standard, $tree ) );

// menu properties can be defined for each menu type

//This is the menu settings array used for smarty template
//$menuSettings[0] - Text to be written next to each field
//$menuSettings[1] - Field type
//$menuSettings[2] - Default Value
//$menuSettings[3] - To which menus applies

// alignment choices
$t->assign( 'menuImagesFolder', DOC_ROOT . "images/menu/" );
$t->assign( 'eimageCombo', array( 'blank.gif', 'arrowE.gif', 'bigArrowE.gif', 'dotE.gif', 'minus.gif', 'bookE.gif', 'folderE.gif', 'arrow2E.gif', 'arrowE_white.gif' ) );
$t->assign( 'cimageCombo', array( 'blank.gif', 'arrowC.gif', 'bigArrowC.gif', 'dotC.gif', 'plus.gif', 'bookC.gif', 'folderC.gif', 'arrow2C.gif', 'arrowC_white.gif' ) );

$t->assign( 'plusCombo', array( 'blank.gif', 'plus.gif', 'plus_2.gif', 'plus_4.gif', 'plus_5.gif' ) );
$t->assign( 'minusCombo', array( 'blank.gif', 'minus.gif', 'minus_2.gif', 'minus_4.gif', 'minus_5.gif' ) );
$t->assign( 'plusBottomCombo', array( 'blank.gif', 'plusbottom.gif', 'plusbottom_2.gif', 'plusbottom_4.gif', 'plusbottom_5.gif' ) );
$t->assign( 'minusBottomCombo', array( 'blank.gif', 'minusbottom.gif', 'minusbottom_2.gif', 'minusbottom_4.gif', 'minusbottom_5.gif' ) );
$t->assign( 'arupCombo', array( 'blank.gif', 'arup.gif'));
$t->assign( 'ardnCombo', array( 'blank.gif', 'ardn.gif'));

// the following are the explorer tree image

$t->assign( 'joinCombo', array( 'blank.gif', 'join.gif', 'join_2.gif', 'join_4.gif', 'join_5.gif' ) );
$t->assign( 'joinBottomCombo', array( 'blank.gif', 'joinbottom.gif', 'joinbottom_2.gif', 'joinbottom_4.gif', 'joinbottom_5.gif' ) );
$t->assign( 'lineImageCombo', array( 'blank.gif', 'line.gif', 'line_2.gif', 'line_4.gif', 'line_5.gif' ) );

$t->assign( 'cfolderImageCombo', array( 'blank.gif', 'folder.gif', 'folder_2.gif', 'folder_3.gif', 'folder_4.gif', 'folder_5.gif' ) );
$t->assign( 'ofolderImageCombo', array( 'blank.gif', 'folderopen.gif', 'folderopen_2.gif', 'folderopen_3.gif', 'folderopen_4.gif', 'folderopen_5.gif' ) );
$t->assign( 'docimageCombo', array( 'blank.gif', 'docimage.gif', 'docimage_2.gif', 'docimage_3.gif', 'docimage_4.gif', 'docimage_5.gif' ) );

$t->assign( 'alignCombo', array( 'left', 'center', 'right' ) );
//$t->assign( 'styleCombo', array( 'normal', 'italic' ) );
$t->assign( 'valignCombo', array( 'top', 'center', 'bottom' ) );
$t->assign( 'fontCombo', array( 'Arial', 'Verdana', 'Georgia', 'Times', 'Courier' ) );
$t->assign( 'booleanCombo', array( 'yes', 'no' ) );

$t->assign( 'flyouttypeCombo', array( 'Vertical', 'Horizontal' ) );
$t->assign( 'weightCombo', array( 'normal', 'bold' ) );

$t->assign( 'locationCombo', array( 'top and bottom', 'top only', 'bottom only' ) );
$t->assign( 'editableByCombo', array( 'All Users', 'Submitter Only', 'User of same group as submitter' ) );

if ( $formID ) {
	
	// denerare field list
	
	$list = $db->getAll( 'select * from ' . FORMSECTIONS_TABLE ." where form_id='$formID'" );
	
	$fieldListTitles = array( '- Select Field -' );
	$fieldListValues = array( '0' );
	
	foreach ( $list as $idx=>$item ) {
		$fieldListTitles[] = $item['label'];
		$fieldListValues[] = $item['id'];
	}
	
	$t->assign( 'fieldListTitles', $fieldListTitles );
	$t->assign( 'fieldListValues', $fieldListValues );
}


$availableSkins = $skin->getAll();

if ( sizeof( $availableSkins ) == 0 )
	$availableSkins = array( '' => '(no skins present)' );

// to populate the skins combo box only
$t->assign( 'skinsCombo', $availableSkins );
$t->assign( 'availableSkins', $availableSkins );

if ( $menuID ) {
	// get a list of all forms & pages
	
	$restrictValues = array( '0', 'all', 'cm_tools' );
	$restrictOutput = array( 'Show this layer on all pages and forms', 'Show on all, except content management tools', 'Show only on content management tools' );

	$shared = getSQLShares( 'page' ); 
	$allPages = $db->getAll( 'select id, if(id in ('.$shared.'), concat(title, \'[shared]\'), title) as title from ' . PAGES_TABLE . " where (site_key = '$site' or id in ($shared)) order by title" );
	
	$shared = getSQLShares( 'form' ); 
	$allForms = $db->getAll( 'select resource_id as id, if(resource_id in ('.$shared.'), concat(value, \'[shared]\'), value) as form_title from '.SETTINGS_TABLE." where resource_type='form' and property='title' and (site_key='$site' or resource_id in ($shared)) order by value" );
	
	foreach( $allPages as $index => $row ) {
		$restrictValues [] = 'p-' . $row[id];
		$restrictOutput [] = 'page - ' . $row[title];
	}
	foreach( $allForms as $index => $row ) {
		$restrictValues [] = 'f-' . $row[id];
		$restrictOutput [] = 'form - ' . $row[form_title];
	}
	
	$t->assign( 'restrictValues', $restrictValues );
	$t->assign( 'restrictOutput', $restrictOutput );
}


$temp = array();

// get all page data for the page with page_id = $_POST['page_id']

if ( $menuID ) {
    $t->assign( 'menuLevel', $menu_level );
    
    $maxLevel = $db->getOne( 'select max(level) from '. MENUITEMS_TABLE . " where resource_type='$reosurceType' and resource_id='$resourceId'" );
    
    $menuLevelCombo = array( '-1'=>'All Levels' );
    for ( $i=0; $i<=$maxLevel; $i++ )
        $menuLevelCombo[$i] = "Level ".($i+1);
        
    $t->assign( 'menuLevelCombo', $menuLevelCombo );
}

$data = $db->getAll( 'select property, value from ' . SETTINGS_TABLE . " where $resourceWhere order by id" );

foreach( $data as $arr ) {
    //See the type of menu that we have
    
    $temp[$arr[property]] = $arr[value];
}


// if we are not editing the menus, then clear out data.type
if ( !$menuID )
    $temp[type] = '';

if ( !isset( $temp[type] ) )
	$temp[type] = $standard;

if ( $menuID ) {
    
    $notLevelSettings = array( 'flyouttype', 'menu_x', 'menu_y', 'restrict_to' );
    
    foreach ( $menuSettings as $key => $value ) {

        if ( ( strpos( $value[3], $temp[type] ) !== false ) or ( $value[3] == "all" ) ) {
            
            // skip some not level settings
            
            if ( $menu_level != -1 && in_array( $key, $notLevelSettings ) )
                continue;
                
            $structure[$key] = $menuSettings[$key];
        }
    };
    
    $t->assign( 'menuType', $temp[type] );
}
else if ( $formID ) {
	
    $structure = $formSettings;
	$resourceTitle = htmlentities( $db->getOne( 'select value from ' . SETTINGS_TABLE . " where resource_type='form' and resource_id = '$formID' and property='page_title'" ) );
	
	if ( !$resourceTitle ) {
		$resourceTitle = htmlentities( $db->getOne( 'select value from ' . SETTINGS_TABLE . " where resource_type='form' and resource_id = '$formID' and property='title'" ) );
	}
	
    $t->assign( 'resourceTitle', "Edit \"$resourceTitle\" Form Settings" );

}else if ( $reportID ) {
    $structure = $reportSettings;
}
else if ( $area ) {
	$structure = $commonAreaSettings;
	switch ( $area ) {
		case 'main':
			$structure = array_merge( $structure, $areaSettings['main'] );
		case 'a1':
		case 'a2':
		case 'a3':
		case 'a4':
			$structure = array_merge( $structure, $areaSettings['corner'] );
			break;
			
		case 'footer':
			$structure = array_merge( $structure, $areaSettings['footer'] );
		case 'a7':
		case 'a5':
			$structure = array_merge( $structure, $areaSettings['hstrip'] );
			break;
		
		case 'a6':
		case 'a8':
			$structure = array_merge( $structure, $areaSettings['vstrip'] );
			break;
			
		case 'visible':
			$structure = array_merge( $structure, $areaSettings['visible'] );
			break;
	}
	
	$t->assign( 'footerAreaCombo', array( 'main'=>'Main Area', 'visible'=>'Visible Area' ) );
	$t->assign( 'visibleHeightCombo', array( 'content'=>'Actual content height', 'screen'=>'At least screen height' ) );
}
else {
    $structure = $siteSettings;
}

$t->assign( 'data', $temp );
$t->assign_by_ref( 'structure', $structure );

$t->assign( 'siteTitle', $db->getOne( 'select title from ' . SITES_TABLE . " where site_key = '$site' limit 1" ) );

// check to ensure that this user has access to this content-management tools

if ( (!hasAdminAccess( 'cm_menu_edit_settings' ) || !hasAdminAccess( 'cm_menu' )) && $menuID ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else if ( !hasAdminAccess( 'cm_settings' ) ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else if ( !hasAdminAccess( 'cm_report' ) && $reportID ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', 'manage/editSettings.tpl' );
}


$title = strtoupper( substr( $resourceType, 0, 1 ) ) . substr( $resourceType, 1 ) . ' Settings';
if ( $resourceTitle )
	$title .= ' ('.$resourceTitle.')';
	
if ( $area )
	$title = $areas[$area] . " Settings";
	
$session->updateLocation( $resourceType.'_settings', $title, array( 'menu_id', 'reportID', 'formID', 'skin_id', 'menu_level', 'area' ) );

include_once( '../init_bottom.php' );

$t->display( $templateName );

?>
