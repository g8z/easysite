<?php
if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );

$resourceId = $skin_id = intval( $_REQUEST['skin_id'] );
$resourceType = 'skin';
$resourceWhere = "resource_type='$resourceType' and resource_id='$resourceId'";

$userID = intval( $_SESSION['es_auth']['id'] );

function changeSectionsActive( $sections, $active ) {
	global $skin_id, $db;
	$sections = "'" . @implode( "', '", $sections ) . "'";
	$db->query( 'update '. SETTINGS_TABLE ." set active='$active' where resource_type='layout_item' and skin_id='$skin_id' and resource_id in ($sections)" );
}

/**
 * If $addSkin == true, then
 */
function updateSettings( $table, $arr, $addSkin ) {

    global $skin_id, $db, $site, $c, $resourceId, $resourceType, $resourceWhere;

	// adjust defaultSkinSettings and currentSkinSettings so that they are of the form:
	// key => value

	// put these into associative arrays

	$defaultSkinSettings2 = array();
	$currentSkinSettings2 = array();

	$field = 'name';		// in the styles table, each value of the name field is unique
	
	$defaultSkinSettings = $db->getAll( "select * from $table where site_key = '$site' and skin_id < 1" );
	$currentSkinSettings = $db->getAll( "select * from $table where site_key='$site' and skin_id='$skin_id'" );
	
	$activeField = 'active';


	foreach( $defaultSkinSettings as $index => $row ) {
		$defaultSkinSettings2[$row[$field]] = $row;
	}

	foreach( $currentSkinSettings as $index => $row ) {
		$currentSkinSettings2[$row[$field]] = $row;
	}

	/**
	 * RULES:
	 * 1) If the property is in $arr, but not in $currentSkinSettings2, then add it from $defaultSkinSettings2/$row
	 * 2) If the property is in $currentSkinSettings2 and in $arr, then set active = 1
	 * 3) If the property is not in $arr, but is in $currentSkinSettings2, then set active = 0
	 */

	foreach( $defaultSkinSettings2 as $index => $row ) {

		$add = false;
		$update = false;
		$active = 1;

		if ( $arr[$index] && !$currentSkinSettings2[$index] ) {
			$add = true;
			$active = 1;
			//$data = $row;
		}
		else if ( $arr[$index] && $currentSkinSettings2[$index][$activeField] == 0 ) {
			$add = false;
			$active = 1;
			$update = true;
		}
		else if ( !$arr[$index] ) {
			$add = false;
			$active = 0;
			$update = true;
		}

		if ( !$add ) {

			
		    $updateFields = array();
			$updateFields[] = "$activeField=$active";
			
			$updateFields = implode( ', ', $updateFields );
			
   	    	$db->query( "update $table set $updateFields where $field='$index' and skin_id='$skin_id' and site_key='$site'" );
		}
		else if ( $add ) {

			$row[id] = '';
			$row[site_key] = $site;
			$row[$activeField] = 1;	// always active when inserting a new skin property
			$row[skin_id] = $skin_id;

			foreach( $row as $field => $value ) {
				$row[$field] = "'" . addslashes( $value ) . "'";
			}

			$db->query( "insert into $table values( " . implode( ",", $row ) . " )" );
		}
	}

}

if ( $_POST['formIsSubmitted'] ) {

	if ( !$skin_id ) {
		// add a new row

		$db->query( "insert into ".SKINS_TABLE." ( site_key, owner ) values ( '$site', '$userID' )" );

		$skin_id = $db->getOne( "select max(id) from ".SKINS_TABLE." where site_key = '$site'" );
		
		$resourceId = $skin_id;
		$resourceWhere = "resource_type='$resourceType' and resource_id='$resourceId'";

		$addSkin = true;	// used in updateSettings function
	}
	else {
		$addSkin = false;	// used in updateSettings function
	}

	// insert skin template areas
	
	if ( $addSkin ) {
		
		$settings = $db->getAll( 'select * from '. SETTINGS_TABLE ." where resource_type='layout_item' and skin_id=0 and site_key='$site'" );
		
		foreach ( $settings as $idx=>$row ) {
		
			$insertFields = array();
			$row[id] = '';
			$row[site_key] = $site;
			$row[active] = 0;	// will be updated later
			$row[skin_id] = $skin_id;
		    
			foreach( $row as $key => $value ) {
			    $insertFields[$key] = "'" . addslashes( $value ) . "'";
			}
			
			$insertValues = implode( ', ', array_values($insertFields) );
			$insertFields = implode( ', ', array_keys($insertFields) );
			
			$db->query( 'insert into '. SETTINGS_TABLE . " ($insertFields) values ($insertValues)" );
		}

	}	

	
	$oldSections = @unserialize( $db->getOne( 'select sections from '. SKINS_TABLE." where id='$skin_id'" ) );
	if ( !$oldSections ) 
		$oldSections = array();
	$newSections = $_POST['sections'];
	if ( !$newSections )
		$newSections = array();
	
	$sections = @serialize( $newSections );

	$db->query( "update ".SKINS_TABLE." set
		name 		= '$skinName',
		description	= '$description',
		sections    = '$sections'
			where id = '$skin_id'" );
	

	$sameSections = @array_intersect( $oldSections, $newSections );
	$activeSections = @array_diff( $newSections, $sameSections );
	$inactiveSections = @array_diff( $oldSections, $sameSections );
	
	changeSectionsActive( $activeSections, 1 );
	changeSectionsActive( $inactiveSections, 0 );
	
	// update the skins in STYLES_TABLE and SETTINGS_TABLE

	if ( $_POST['styles'] ) {
		foreach( $_POST['styles'] as $index => $property ) {
			$updateStyles[ $property ] = 1;
		}
	}
	
    updateSettings( STYLES_TABLE, $updateStyles, $addSkin );

}

if ( $deleteSkinId ) {

	$skin->remove( $skin_id );

	// get the next available skin
	$skin_id = $db->getOne( 'select min(id) from ' . SKINS_TABLE . " where site_key = '$site'" );

	$switchSkinId = $skin_id;
}

if ( $switchSkinId ) {

	// determine the parent to this site

	if ( $_SESSION['es_auth']['user_site_key'] == $site ) {

		// we are in a user-specific site
		// determine the parent site, so that we can copy over the settings

		$parentSite = $_SESSION['es_auth']['site_key'];

		$skin->setParentSite( $parentSite );
	}

	$t->assign( 'loadedSkin', $switchSkinId );

}


// get a list of all skins

$temp = $db->getAll( "select * from ".SKINS_TABLE." where site_key = '$site' and owner = '$userID' order by name" );

foreach( $temp as $index => $row ) {
	//$allSkins["$row[id]"] = $row[name];

	if ( $row[id] == $skin_id ) {

		$t->assign( 'data', $row );

		$data = $row;
	}
}

// determine which of these groups should be selected

if ( !$skin_id )
	$t->assign( 'type', 'Add' );
else
	$t->assign( 'type', 'Edit' );


// determine what level of access should be allowed to this user

$hasStyleAccess = hasAdminAccess( 'cm_style' );
$hasSettingsAccess = hasAdminAccess( 'cm_settings' );

$t->assign( 'stylesAccess', $hasStyleAccess );
$t->assign( 'settingsAccess', $hasSettingsAccess );

$allSkins = array();


if ( $hasStyleAccess || $hasSettingsAccess ) {
	$allSkins = $skin->getAll( 'edit' );
}

$t->assign( 'allSkins', $allSkins );

if ( sizeof( $allSkins ) == 0 )
	$t->assign( 'noSkins', 1 );

// get the available settings and styles

$temp = $db->getAll( 'select * from ' . STYLES_TABLE . " where site_key = '$site' and skin_id < 1" );

$skinStyles = array();
$skinStylesSelected = array();

$currentSkinStylesTemp = $db->getAll( 'select name from ' . STYLES_TABLE . " where site_key='$site' and skin_id='$skin_id' and active = '1'" );

$currentSkinStyles = array();

if ( sizeof( $currentSkinStylesTemp ) > 0 ) {
	foreach( $currentSkinStylesTemp as $index => $row ) {
		$currentSkinStyles[ $row[name] ] = 1;
	}
}

foreach( $temp as $index => $row ) {

	$skinStyles[$row[name]] = str_replace( '.', '', $row[name] );

	// if this is a new skin, then check all by default

	if ( !$skin_id || $currentSkinStyles[ $row[name] ] )
		$skinStylesSelected [] = $row[name];
}

// if we are editing an existing skin, then determine the existing settings for it

$t->assign( 'skinStyles', $skinStyles );
$t->assign( 'skinStylesSelected', $skinStylesSelected );

/*$temp = $db->getAll( 'select * from ' . SETTINGS_TABLE . " where resource_type='site' and resource_id='$site'" );

$skinSettings = array();
$skinSettingsSelected = array();

// this applies for global site settings as well as menu settings
$currentSkinSettingsTemp = $db->getAll( 'select property from ' . SETTINGS_TABLE . " where $resourceWhere and param = '1'" );

$currentSkinSettings = array();

if ( sizeof( $currentSkinSettingsTemp ) > 0 ) {
	foreach( $currentSkinSettingsTemp as $index => $row ) {
		$currentSkinSettings[ $row[property] ] = 1;
	}
}

foreach( $temp as $index => $row ) {

	$desc = getSettingDescription( $row[property], '' );

	if ( !$desc )
		continue;

	$skinSettings[$row[property]] = $desc;

	// if this is a new skin, then check all by default

	if ( !$skin_id || $currentSkinSettings[$row[property]] )
		$skinSettingsSelected [] = $row[property];
}

$t->assign( 'skinSettings', $skinSettings );
$t->assign( 'skinSettingsSelected', $skinSettingsSelected );*/

$templateSections = array( 
	'visible'=>"<a href='editSettings.php?area=visible&skin_id=$skin_id'>Visible Area</a>",
	'main'=>"<a href='editSettings.php?area=main&skin_id=$skin_id'>Main Area</a>",
	'a1'=>"<a href='editSettings.php?area=a1&skin_id=$skin_id'>A1 Area</a>",
	'a2'=>"<a href='editSettings.php?area=a2&skin_id=$skin_id'>A2 Area</a>",
	'a3'=>"<a href='editSettings.php?area=a3&skin_id=$skin_id'>A3 Area</a>",
	'a4'=>"<a href='editSettings.php?area=a4&skin_id=$skin_id'>A4 Area</a>",
	'a5'=>"<a href='editSettings.php?area=a5&skin_id=$skin_id'>A5 Area</a>",
	'a6'=>"<a href='editSettings.php?area=a6&skin_id=$skin_id'>A6 Area</a>",
	'a7'=>"<a href='editSettings.php?area=a7&skin_id=$skin_id'>A7 Area</a>",
	'a8'=>"<a href='editSettings.php?area=a8&skin_id=$skin_id'>A8 Area</a>",
	'footer'=>"<a href='editSettings.php?area=footer&skin_id=$skin_id'>Footer Area</a>",
	'screen'=>"<a href='editSettings.php?area=screen&skin_id=$skin_id'>Screen Area</a>",
);

if ( !$skin_id )
	$templateSectionSelected = array( 'visible','main','a1','a2','a3','a4','a5','a6','a7','a8','footer','screen' );
else 
	$templateSectionSelected = @unserialize( $data['sections'] );
	
$t->assign( 'templateSections', $templateSections );
$t->assign( 'templateSectionSelected', $templateSectionSelected );

// immediately update
$siteSettings = $system->getSettings();
/*$t->assign( 'settings', $siteSettings );
if ( $siteSettings[auto_center] == 'yes' ) {
    assignCenterVariables( $siteSettings[body_x], $siteSettings[body_w], $layerData );
}*/
if ( $skin_id )
	$title = "Edit Skin ({$data[name]})";
else
	$title = 'Add Skin';

$session->updateLocation( 'skin_manager', $title, array( 'switchSkinId', 'skin_id' ) );
include_once( '../init_bottom.php' );

// only the default site administrator has access to the users/groups tool
if ( !hasAdminAccess( 'cm_skin' ) ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', 'manage/editSkins.tpl' );
}

$t->display( $templateName );
 
?>