<?php

$configFile = dirname(__FILE__) . $docroot . 'config.php';
require_once( $configFile );

$dsn = DB_TYPE . '://' . DB_USER . ':' . DB_PASS . '@' . DB_HOST . '/' . DB_NAME;
$db = @DB::connect( $dsn );

// ----------------
// UPGRADE ROUTINES
// ----------------

$succ = upgradeWithFile( SQL_FILE );
$succ &= upgradeWithFile( 'sql/modules/gallery.sql' );
//$succ &= upgradeWithFile( 'sql/modules/realty.sql' );
$succ &= upgradeWithFile( 'sql/modules/poll.sql' );

updateSettingsTable();
updateForms();

updateUsePageKey();

updateLayerVisibility();

// 3.1.6 upgrade
updateGuestPermissions();
updateSettingsTable316();
updateGallery316();
updateUsers316();
	

$t->display( 'pages/upgrade.tpl' );



// -----------------------
// 3.1.6 upgrade functions
// -----------------------

// 3.1.6 gallery update
function updateUsers316() {
	global $db;
	
	$db->query( 'update '.DB_PREFIX."_users set login_pass=MD5(login_pass)" );
	
}


// creates sections from 3.1.5 set of settings
function createSections( $settings, $site, $skin_id ) {
	
	global $db;
	
	// convert 3 old bg images into 3 sections
	$sections['a1']['bgimage'] = $settings['bg_image'];
	$sections['a1']['width'] = '100%';
	$sections['a1']['height'] = '100%';
	$sections['a1']['bgimage_repeat_y'] = 'no';
	$sections['a1']['bgimage_repeat_x'] = 'no';
	$sections['a1']['zindex'] = '1';
	
	$sections['a8']['bgimage'] = $settings['vt_image'];
	$sections['a8']['width'] = '100%';
	$sections['a8']['bgimage_repeat_y'] = 'yes';
	$sections['a8']['bgimage_repeat_x'] = 'no';
	
	
	$sections['a5']['bgimage'] = $settings['hz_image'];
	$sections['a5']['height'] = '100%';
	$sections['a5']['bgimage_repeat_y'] = 'no';
	$sections['a5']['bgimage_repeat_x'] = 'yes';
	
	$sections['main']['left'] = $settings['body_x'];
	$sections['main']['top'] = $settings['body_y'];
	$sections['main']['width'] = $settings['body_w'];
	$sections['main']['height'] = $settings['body_h'];
	$sections['main']['bgcolor'] = $settings['main_color'];
	$sections['main']['padding'] = $settings['padding'];
	$sections['main']['zindex'] = 10;
	
	$sections['screen']['bgcolor'] = $settings['body_color'];
	
	$sections['visible']['cellspacing'] = $settings['cellspacing'];
	$sections['visible']['cellpadding'] = $settings['cellpadding'];
	$sections['visible']['auto_center'] = $settings['auto_center'];
	$sections['visible']['image_pad_left'] = $settings['image_pad_left'];
	$sections['visible']['image_pad_right'] = $settings['image_pad_right'];
	$sections['visible']['image_pad_top'] = $settings['image_pad_top'];
	$sections['visible']['image_pad_bottom'] = $settings['image_pad_bottom'];
	$sections['visible']['bullet_horiz_offset'] = $settings['bullet_horiz_offset'];
	$sections['visible']['bullet_vert_offset'] = $settings['bullet_vert_offset'];
	$sections['visible']['bullet_vert_align'] = $settings['bullet_vert_align'];
	$sections['visible']['bullet_indent'] = $settings['bullet_indent'];
	$sections['visible']['width'] = '100%';
	$sections['visible']['height'] = 'screen';
	
	$sections['footer']['content'] = $settings['footer'];
	$sections['footer']['align'] = $settings['footer_align'];
	$sections['footer']['style'] = $settings['footer_style'];
	$sections['footer']['parent'] = 'main';
	$sections['footer']['bgcolor'] = $settings['footer_bgcolor'];
	$sections['footer']['bgimage'] = $settings['footer_bgimage'];
	$sections['footer']['bgimage_vertical_align'] = $settings['bgimage_vertical_align'];
	$sections['footer']['bgimage_horiz_align'] = $settings['bgimage_horiz_align'];
	$sections['footer']['bgimage_repeat_y'] = $settings['bgimage_repeat_y'];
	$sections['footer']['bgimage_repeat_x'] = $settings['bgimage_repeat_x'];
	
	
	$fields = array();
	$fields['resource_type'] = 'layout_item';
	
	foreach ( $sections as $section=>$set ) {
		$fields['resource_id'] = $section;
		$fields['site_key'] = $site;
		
		if ( $skin_id ) {
			$fields['skin_id'] = $skin_id;
			$fields['active'] = 1;
		}
		
		foreach ( $set as $property=>$value ) {
			$fields['property'] = $property;
			$fields['value'] = addslashes( $value );
			
			$insertFields = implode( ', ', array_keys( $fields ) );
			$insertValues = "'" . implode( "', '", array_values( $fields ) ) . "'" ;
			
			$db->query( 'insert into '. DB_PREFIX."_settings ($insertFields) values ($insertValues)" );
		}
	}
}


// 3.1.6 layout & skins update
function updateSettingsTable316() {
	global $db;
	
    $siteSettigns = array(
		'title',
	    'metaKeywords',
	    'metaDescription',
	    'title',
	    'compress_output',
	    'admin_email',
	    'admin_name',
	    'php_date',
	    'wysiwyg',
	    'nl2br',
		'textarea_width',
		'textarea_height',
		'sef_urls',
	);
	$siteSettigns = "'" . implode( "', '", $siteSettigns ) . "'";
	
	$db->query( 'alter '. DB_PREFIX."_settings add skin_id int(8)" );
	
	// this needed because we add zindex for main body (10)
	$db->query( 'update '. DB_PREFIX."_layers set zorder=zorder+10" );
	
	// update sites
	$sites = $db->getAll( 'select site_key from '.DB_PREFIX."_sites" );
	
	foreach ( $sites as $idx=>$site ) {
		
		$site = $site['site_key'];
		
		$settings = array();
		$s = $db->getAll( 'select * from '.DB_PREFIX."_settings where resource_type='site' and resource_id='$site'" );
		foreach ( $s as $idx=>$set ) {
			$settings[$set['property']] = $set['value'];
		}
		$db->query( 'delete from '. DB_PREFIX."_settings where resource_type='site' and resource_id='$site' and property not in ($siteSettigns)" );
		
		$sections = createSections( $settings, $site, 0 );
		
		
	}
	
	
	// update skins
	$skins = $db->getAll( 'select * from '. DB_PREFIX."_skins" );
	
	$allSections = array( 'visible', 'main', 'a1', 'a2', 'a3', 'a4', 'a5', 'a6', 'a7', 'a8', 'footer', 'screen' );
	$allSections = serialize( $allSections );
	$db->query( 'update '. DB_PREFIX."_skins set sections='$allSections'" );
	
	foreach ( $skins as $idx=>$skin ) {
		$site = $skin['site_key'];
		$skin_id = $skin['id'];
		
		$s = $db->getAll( 'select * from '.DB_PREFIX."_settings where resource_type='skin' and resource_id='$skin_id'" );
		foreach ( $s as $idx=>$set ) {
			$settings[$set['property']] = $set['value'];
		}
		$db->query( 'delete from '. DB_PREFIX."_settings where resource_type='skin' and resource_id='$skin_id' and property not in ($siteSettigns)" );
		
		$sections = createSections( $settings, $site, $skin_id );
	}
}


// 3.1.6 gallery update
function updateGallery316() {
	global $db;
	
	$items = $db->getAll( 'select id, _order, cat_id, site_key from '.DB_PREFIX."_gallery_items" );
	foreach( $items as $idx=>$item ) {
		$db->query( 'insert into '.DB_PREFIX."_gallery_item_cat (img_id,cat_id,_order,site_key) values ('$item[id]','$item[cat_id]','$item[_order]','$item[site_key]')" );
	}


	// insert checkout fields
	$db->query( 'INSERT INTO `'. DB_PREFIX."_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES ('', 'checkoutFields', '".'a:13:{s:10:"first_name";a:3:{s:5:"title";s:10:"First Name";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:9:"last_name";a:3:{s:5:"title";s:9:"Last Name";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:5:"email";a:3:{s:5:"title";s:6:"E-Mail";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:5:"phone";a:3:{s:5:"title";s:5:"Phone";s:7:"visible";s:1:"1";s:8:"required";s:1:"0";}s:14:"payment_method";a:2:{s:5:"title";s:14:"Payment Method";s:7:"payment";s:12:"allow_choose";}s:16:"require_shipping";a:1:{s:7:"require";s:1:"1";}s:9:"address_1";a:3:{s:5:"title";s:15:"Address, Line 1";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:9:"address_2";a:3:{s:5:"title";s:15:"Address, Line 2";s:7:"visible";s:1:"1";s:8:"required";s:1:"0";}s:4:"city";a:3:{s:5:"title";s:4:"City";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:6:"states";a:3:{s:5:"title";s:5:"State";s:7:"visible";s:1:"1";s:8:"required";s:1:"0";}s:9:"countries";a:3:{s:5:"title";s:7:"Country";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:3:"zip";a:3:{s:5:"title";s:3:"Zip";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:15:"shipping_method";a:2:{s:5:"title";s:15:"Shipping Method";s:8:"shipping";s:12:"allow_choose";}}".', 0, 'default', 'gallery');
}


// 3.1.6 upgrade functions
function updateGuestPermissions() {
	// add all permissions for quest users
	
	global $db;
	
	define( 'GUEST_GROUP', -100 );
	
	$resources = array( 'form', 'page', 'file' );
	
	$sites = $db->getAll( 'select site_key from '.DB_PREFIX."_sites" );
	
	foreach ( $sites as $idx=>$site ) {
		
		$site = $site['site_key'];
		
		$set = $db->getOne( 'select 1 from '. DB_PREFIX."_permissions where group_id='".GUEST_GROUP."' and site_key='$site'" );
		
		if ( !$set ) {
			
			foreach ( $resources as $idx=>$resource ) {
				$sql = "INSERT INTO `". DB_PREFIX."_permissions` ( resource_id, resource_type, group_id, user_id, site_key ) VALUES (0, '$resource', ".GUEST_GROUP.", 0, '$site');";
				$db->query( $sql );
			}
		}
	}
}

// update visibility for 3.x version
function updateLayerVisibility() {
	global $db;
	
	$layers = $db->getAll( 'select id, restrict_to from '. DB_PREFIX.'_layers' );
	
	foreach ( $layers as $idx=>$layer ) {
		
		$newRestrict = array( 'module'=>1, 'cm_tools'=>1 );
		$newRestrict['page'] = array();
		$newRestrict['form'] = array();
		$newRestrict['report'] = array();
			
		if ( $layer['restrict_to'] ) {
		
			$oldRestrict = explode( ',', $layer['restrict_to'] );
			
			foreach( $oldRestrict as $ridx=>$r ) {
				
				if ( $r == 'all' ) { // except content managament
					$newRestrict['cm_tools'] = 0;
					$newRestrict['page'] = 'all';
					$newRestrict['form'] = 'all';
					$newRestrict['report'] = 'all';
				}
				elseif( $r == 'cm_tools' ) { // only on content managament tools
					$newRestrict['page'] = 'none';
					$newRestrict['form'] = 'none';
					$newRestrict['report'] = 'none';
					$newRestrict['module'] = 0;
					$newRestrict['cm_tools'] = 1;
				}
				elseif( preg_match( "/^p-(.*)$/", $r, $matches ) ) {
					$newRestrict['page'][$matches[1]] = 1;
					$newRestrict['module'] = 0;
					$newRestrict['cm_tools'] = 0;
				}
				elseif( preg_match( "/^f-(.*)$/", $r, $matches ) ) {
					$newRestrict['form'][$matches[1]] = 1;
					$newRestrict['module'] = 0;
					$newRestrict['cm_tools'] = 0;
				}
			}
		
			if  ( !count( $newRestrict['page'] ) )
				$newRestrict['page'] = 'none';
				
			if  ( !count( $newRestrict['form'] ) )
				$newRestrict['form'] = 'none';
				
			if  ( !count( $newRestrict['report'] ) )
				$newRestrict['report'] = 'none';
			
		}
		else {
			$newRestrict['page'] = 'all';
			$newRestrict['form'] = 'all';
			$newRestrict['report'] = 'all';
		}
		
		// update layer
		
		$restrict_to = serialize( $newRestrict );
		
		$db->query( 'update ' . DB_PREFIX . "_layers set restrict_to='$restrict_to' where id='$layer[id]'" );
	}
}

function updateUsePageKey() {
    
    global $db;

    $items = $db->getAll( 'select id, resource_type, resource_id from '. DB_PREFIX.'_menu_items' );
    
    foreach( $items as $item ) {
        
        if ( $item[resource_type] == 'page' ) {
                
            // update menu-items to use page_id instead of page_key
    
            $page_key = $item[resource_id];
            
            if ( $page_key )
                $page_id = $db->getOne( 'select id from ' . DB_PREFIX."_pages" . " where page_key='$page_key'" );
                
            if ( $page_id ) {
                $db->query( 'update ' . DB_PREFIX.'_menu_items' . " set resource_id='$page_id' where id='$item[id]'" );
            }
        }

    
        if ( $item[resource_type] == 'form' ) {
                
            // update menu-items to use page_id instead of page_key
    
            $form_key = $item[resource_id];
            
            if ( $form_key )
                $form_id = $db->getOne( 'select resource_id from ' . DB_PREFIX."_settings" . " where resource_type='form' and property='form_key' and value='$form_key'" );
                
            if ( $form_id ) {
                $db->query( 'update ' . DB_PREFIX.'_menu_items' . " set resource_id='$form_id' where id='$item[id]'" );
            }
        }
    
    
    }
    
}


// upgrade to 2.1.x structure
function updateSettingsTable() {
	
	global $db;

	$db->setFetchMode( DB_FETCHMODE_ASSOC );
	
	$settings = $db->getAll( 'select * from '. DB_PREFIX."_settings" );
	
	foreach( $settings as $setting ) {
		
		if ( $setting['menu_id'] ) {
			$resourceType = 'menu';
			$resourceId = $setting['menu_id'];
			$param = $setting['param'];
		}
		else if ( $setting['skin_id'] ) {
			$resourceType = 'skin';
			$resourceId = $setting['skin_id'];
			$param = $setting['active'];
		}
		else if ( $setting['report_id'] ) {
			$resourceType = 'report';
			$resourceId = $setting['report_id'];
			$param = $setting['param'];
		}
		else {
			$resourceType = 'site';
			$resourceId = $setting[site_key];
			$param = $setting['param'];
		}
		
		$value = addslashes( $setting['value'] );
		
		$exists = $db->getOne( 'select id from '.DB_PREFIX."_settings where resource_type='$resourceType' and resource_id='$resourceId' and site_key='$setting[site_key]'" );
		
		if ( !$exists )
			$db->query( 'insert into '. DB_PREFIX."_settings ( resource_type, resource_id, site_key, property, value, param ) values ( '$resourceType', '$resourceId', '$setting[site_key]', '$setting[property]', '$value', '$param')" );
		
	}
	
	$db->query( 'delete from '.DB_PREFIX.'_settings where resource_type=\'\'' );
    
}


// upgrade to 2.1.x structure
function updateForms() {
	
	global $db;
	
	$db->setFetchMode( DB_FETCHMODE_ASSOC );
	
	include_once( DOC_ROOT . 'manage/settingsList.php' );
	
	$forms = $db->getAll( 'select * from '. DB_PREFIX.'_forms' );
	
	$replaceValues = array(
		'mail_to_address' => 'form_to',
		'copy_to' => 'form_cc',
		'mail_subject' => 'form_subject',
		'login_form' => 'login_form',
		'generate_report' => 'generate_report',
		'is_default' => 'is_default',
		'skin_id' => 'skin_id',
		'title' => 'form_title',
		'description' => 'form_desc',
	);
	
	foreach( $forms as $num=>$form ) {
		
		$site_data = $db->getRow( 'select default_resource_type, default_resource_id, login_form_id from '. DB_PREFIX."_sites where site_key='$form[site_key]'" );
		
		if ( $site_data['default_resource_type'] == 'form' && $site_data['default_resource_id'] == $form['id'] )
			$form['is_default'] = 'yes';
		else
			$form['is_default'] = 'no';
			
		if ( $site_data['login_form_id'] == $form['id'] )
			$form['login_form'] = 'yes';
		else
			$form['login_form'] = 'no';
			
		$form['generate_report'] = $form['generate_report'] == 1 ? 'yes' : 'no';
		
		
        foreach( $formSettings as $property=>$setting ) {
            
			if ( array_key_exists( $property, $replaceValues ) )
				$value = $form[$replaceValues[$property]];
			else
				$value = $setting[2];
			
            $exists = $db->getOne( 'select resource_id from '.DB_PREFIX."_settings where resource_type='form' and resource_id='{$form['id']}' and property='$property'" );
            
            if ( !$exists )
				$db->query( 'insert into '. DB_PREFIX."_settings ( site_key, resource_type, resource_id, property, value ) values ( '$form[site_key]', 'form', '$form[id]', '$property', '$value' )" );
        }
	}
}

?>