<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( 'init.php' );
//

$settings = $db->getAll( 'select * from '. SETTINGS_TABLE ." where site_key='$site'" );

foreach( $settings as $setting ) {
	
	if ( $setting['menu_id'] ) {
		$resourceType = 'menu';
		$resourceId = $setting['menu_id'];
	}
	else if ( $setting['skin_id'] ) {
		$resourceType = 'skin';
		$resourceId = $setting['skin_id'];
	}
	else if ( $setting['report_id'] ) {
		$resourceType = 'report';
		$resourceId = $setting['report_id'];
	}
	else {
		$resourceType = 'site';
		$resourceId = $site;
	}
	
	$value = addslashes( $setting['value'] );
	
	$db->query( 'insert into '.SETTINGS_TABLE." ( resource_type, resource_id, site_key, property, value, param ) values ( '$resourceType', '$resourceId', '$site', '$setting[property]', '$value', '$setting[param]')" );
	
}

    
?>