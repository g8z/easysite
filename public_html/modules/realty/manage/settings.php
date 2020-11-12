<?php

$manage = true;
include_once( '../config.php' );

$table = MODULESETTINGS_TABLE;

// default settings for this module

/**
 * NOTE: properties which directly correspond to DB fields use the *exact* name
 * of the field, for example: fitness_center
 */

$defaultSettings = array(
    'search_title'		=> array( 'Search Title', 'text', 'Real Estate' ),
    'search_desc'		=> array( 'Search Description', 'textarea', '' ),
    'search_title2'		=> array( 'Search Results Title', 'text', 'Your Search Results' ),
    'saved_title'		=> array( 'Saved Results Title', 'text', 'Your Saved Properties' ),
    'searchResultCount' => array( 'Search Results Per Page', 'number', '5' ),
    'priceFormat'       => array( 'Price Format', 'priceFormat', '0,.' ),
    'spaceUnit'         => array( 'Space Units', 'spaceUnit', 'sq. ft.' ),
    'resizeImage'       => array( 'Resize Image on Upload<br /><small>Note: GD library must be installed</small>', 'isThumb', 'yes' ),
    'imageWidth'        => array( 'Image Max Width', 'number', '400' ),
    'imageHeight'       => array( 'Image Max Height', 'number', '400' ),
    //'propertyIdTemplate' => array( 'Property ID Text', 'text', 'Property ID:' ),
    'imagePosition'     => array( 'Image Position', 'leftRight', 'right' ),
    'imgBorderSize'     => array( 'Image Border Size', 'number', '0' ),
    'borderColor'       => array( 'Image Border Color', 'color', '#000000' ),
    'imageWindowWidth'  => array( 'Image Popup Window Width', 'number', '800' ),
    'imageWindowHeight' => array( 'Image Popup Window Height', 'number', '600' ),
	'max_upload_images'	=> array( 'Max Images per Listing', 'number', '6' ),
	'max_image_width'	=> array( 'Max Allowed Image Width', 'number', '700' ),
	'max_image_height'	=> array( 'Max Allowed Image Height', 'number', '500' ),

	'max_thumb_width'	=> array( 'Max Thumbnail Width', 'number', 150 ),
	'max_thumb_height'	=> array( 'Max Thumbnail Height', 'number', 150 ),

    'online_map_url'	=> array( 'Online Map URL', 'textarea', 'http://www.mapquest.com/maps/map.adp?country=[country]&address=[address]&city=[city]&state=[state]&zipcode=[zip]' ),

    'online_map'		=> array( 'Show Online Map Link', 'boolean', 'yes' ),
    'showMoreImagesLink'=> array( 'Show More Images Link', 'boolean', 'yes' ),
    'showPropertyImages'=> array( 'Show Propery Images', 'boolean', 'yes' ),
    'showPropertyId'	=> array( 'Show Property ID', 'boolean', 'yes' ),
	'allowTitles'		=> array( 'Allow Image Titles', 'boolean', 'yes' ),
	'allowDescriptions'	=> array( 'Allow Image Descriptions', 'boolean', 'yes' ),
	'showContactLink'	=> array( 'Show Realtor Contact Link', 'boolean', 'yes' ),
);

// add-in the extra settings from realty_getSettings

$defaultSettings = array_merge( $defaultSettings, realty_getOptions() );

if ( $formIsSubmitted ) {
	// update the settings in the $table table

	$db->query( "delete from $table where site_key = '$site' and module_key = 'realty'" );

	foreach( $defaultSettings as $index => $row ) {
		$val = $_POST[$index];

		$db->query( "insert into $table (
			name,
			value,
			site_key,
			module_key
			) values (
			'$index',
			'$_POST[$index]',
			'$site',
			'realty'
			)" );
	}
}


$data = $db->getAll( "select * from $table where site_key = '$site' and module_key = 'realty'" );
$data2 = array();

foreach( $data as $index => $row ) {
	$data2[$row[name]] = $row;
}

if ( $gdInstalled = gdInstalled() ) {
    $isThumb = array( 'yes', 'no' );
} else {
    $isThumb = array( 'no' );
}
$t->assign( 'gdInstalled', $gdInstalled );
$t->assign( 'isThumb', $isThumb );

$t->assign( 'booleanCombo',
	array( '1' => 'yes', '0' => 'no' )
	);

$t->assign( 'spaceUnitCombo', array( 'sq. ft.', 'sq. meters' ) );

$priceFormatCombo = array( '0,.' => number_format( 100000, 0, '.', ',' ),
                           '0 .' => number_format( 100000, 0, '.', ' ' ),
                           '2,.' => number_format( 100000, 2, '.', ',' ),
                           '2 .' => number_format( 100000, 2, '.', ' ' )
                         );
$t->assign( 'priceFormatCombo', $priceFormatCombo );

$t->assign( 'leftRightCombo', array( 'left', 'right' ) );

$hasAccess = hasAdminAccess( 'cm_module' ) && hasAdminAccess( 'cm_'.$moduleKey ) && hasAdminAccess( 'cm_'.$moduleKey.'_edit_settings' );
$t->assign( 'hasAccess', $hasAccess );

$t->assign( 'data', $data2);

$t->assign( 'defaultSettings', $defaultSettings );

$t->assign( 'settingsTitle', 'Real Estate Settings' );

$session->updateLocation( 'realty_settings', 'Realty Settings' );

// shared 'settings' template
$t->assign( 'bodyTemplate', 'modules/realty/manage/settings.tpl' );


include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );



?>