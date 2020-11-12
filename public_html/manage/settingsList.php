<?php


function changeUsePageKey( $key, $old_value, $new_value ) {
    
    global $site, $db;

    $items = $db->getAll( 'select id, resource_type, resource_id from '. MENUITEMS_TABLE . " where site_key='$site'" );
    
    foreach( $items as $item ) {
        
        if ( $item[resource_type] == 'page' ) {
                
            if ( $old_value == 'yes' && $new_value == 'no' ) {
                
                // update menu-items to use page_id instead of page_key
        
                $page_key = $item[resource_id];
                
                if ( $page_key )
                    $page_id = $db->getOne( 'select id from ' . PAGES_TABLE . " where site_key='$site' and page_key='$page_key'" );
                    
                if ( $page_id ) {
                    $db->query( 'update ' . MENUITEMS_TABLE . " set resource_id='$page_id' where id='$item[id]'" );
                }
            }
            
            if ( $old_value == 'no' && $new_value == 'yes' ) {
                
                // update menu-items to use page_key instead of page_id
                
                $page_id = intval( $item[resource_id] );
                $page_key = $db->getOne( 'select page_key from ' . PAGES_TABLE . " where id='$page_id' and site_key='$site'" );
                
                if ( $page_key )
                    $db->query( 'update '. MENUITEMS_TABLE . " set resource_id='$page_key' where id='$item[id]'" );
            }
        }

    
        if ( $item[resource_type] == 'form' ) {
                
            if ( $old_value == 'yes' && $new_value == 'no' ) {
                
                // update menu-items to use page_id instead of page_key
        
                $form_key = $item[resource_id];
                
                if ( $form_key )
                    $form_id = $db->getOne( 'select resource_id from ' . SETTINGS_TABLE . " where site_key='$site' and resource_type='form' and property='form_key' and value='$form_key'" );
                    
                if ( $form_id ) {
                    $db->query( 'update ' . MENUITEMS_TABLE . " set resource_id='$form_id' where id='$item[id]'" );
                }
            }
            
            if ( $old_value == 'no' && $new_value == 'yes' ) {
                
                // update menu-items to use page_key instead of page_id
                
                $form_id = intval( $item[resource_id] );
                $form_key = $db->getOne( 'select value from ' . SETTINGS_TABLE . " where resource_type='form' and resource_id='$form_id' and property='form_key' and site_key='$site'" );
                
                if ( $form_key )
                    $db->query( 'update '. MENUITEMS_TABLE . " set resource_id='$form_key' where id='$item[id]'" );
            }
        }
    
    
    }
    
}


function changeDefaultResource( $key, $old_value, $new_value ) {
	
	global $db, $site, $system;
	global $formID; // from editSettings.php
	
    if ( $old_value == 'no' && $new_value == 'yes' ) {
        // determine if the default page has been set yet
        
        $db->query( 'update '. SETTINGS_TABLE." set value='no' where (resource_type='form' or resource_type='page') and property='$key' and resource_id!='$formID'" );

        $db->query( 'update ' . SITES_TABLE . " set
			default_resource_type 	= 'form',
			default_resource_id 	= '$formID'
			where site_key 			= '$site'
			" );
    }
    
    if ( $old_value == 'yes' && $new_value == 'no' ) {
        // clear default if this page or form was previously the default
        //$defaults = $db->getRow( 'select * from ' . SITES_TABLE . " where site_key = '$site'" );

        if ( $system->siteData[default_resource_type] == 'form' && $system->siteData[default_resource_id] == $formID ) {
            $db->query( 'update ' . SITES_TABLE . " set
				default_resource_type 	= '',
				default_resource_id 	= ''
				where site_key 			= '$site'
				" );
        }
    }
}


function changeLoginForm( $key, $old_value, $new_value ) {
	
	global $db, $site, $system;
	global $formID; // from editSettings.php
	
    // this is the form to use as the login form
    if ( $old_value == 'no' && $new_value == 'yes' ) {
    	
        $db->query( 'update '. SETTINGS_TABLE." set value='no' where (resource_type='form' or resource_type='page') and property='$key' and resource_id!='$formID'" );
        
        $db->query( 'update ' . SITES_TABLE . " set login_form_id = '$formID' where site_key = '$site'" );
    }
    
    if ( $old_value == 'yes' && $new_value == 'no' ) {
        // clear default if this page or form was previously the default
        // $siteData is an array set in init.php which contains this information about the site (defaults, etc)
        if ( $system->siteData[login_form_id] == $formID ) {
            $db->query( 'update ' . SITES_TABLE . " set login_form_id = '' where site_key = '$site'" );
        }
    }
}


function changeFormTitle( $key, $old_value, $new_value ) {
	global $formID;
	global $db, $site, $system;
	include_once( INCLUDE_DIR . 'internal/db_items/class.Form.php' );
	$form   = new Form();
	$sef_title = getSefTitle( $new_value, FORMS_TABLE, 'sef_title', $formID );
	$form->updateId( $formID, array( 'sef_title'=>$sef_title ) );	
}

// global site settings and menu settings
// this is kept in an external file for access
// from editSkins.php and editSettings.php

// default site properties
$siteSettings = array(
    'title'           => array( 'Website Title', 'text', 'My Website' ),
    'metaKeywords'    => array( 'Default Meta Keywords', 'text', 'EasySite, website, management, cms, layer, layer based, system, easysite website management' ),
    'metaDescription' => array( 'Default Meta Description', 'textarea', 'EasySite - Layer based website management system' ),
    'title'           => array( 'Website Title', 'text', 'My Website' ),
    'compress_output' => array( 'Compress output when possible?', 'boolean', 'no' ),
    //'strip_output' 	  => array( 'Strip spaces, tabs and newlines?', 'boolean', 'no' ),
    'admin_email'     => array( 'Webmaster E-Mail', 'text', 'admin@website.com' ),
    'admin_name'      => array( 'Webmaster Name', 'text', 'Site Administrator' ),
    'php_date'        => array( $phpDateFormats, 'text', 'm/d/y' ),
    'wysiwyg'         => array( 'Allow WYSIWYG Text Editor', 'boolean', 'yes' ),
    'nl2br'           => array( 'Replace new lines with &lt;br&gt; in pages and layers?', 'boolean', 'yes' ),
    
    //'use_page_key'  => array( 'Use page/form key instead of page/form id?', 'boolean', 'yes', changeUsePageKey ),

	'textarea_width'	=> array( 'Width of text area edit region (pixels)', 'number', 600 ),
	'textarea_height'	=> array( 'Height of text area edit region (pixels)', 'number', 100 ),

	'sef_urls'	        => array( 'Enable SEF urls ?', 'boolean', 'no' ),
	'use_md5'	        => array( 'Use MD5 user\'s password encription?', 'boolean', 'yes' ),
    );
    
$reportSettings = array(

    'paginate'     => array( 'Paginate Report', 'boolean', 'yes' ),
    'headerStyle'         => array( 'Header Style', 'style', 'subtitle' ),
    'headerColor'         => array( 'Header Background Color', 'color', '#FFFFFF' ),
    'rows_per_page'       => array( 'Rows per Page', 'number', 10 ),
    'page_links'          => array( 'Page links in navigation bar', 'number', 10 ),
    'location_navigation' => array( 'Location of navigation bar', 'location', 'top and bottom' ),
    'orientation'         => array( 'Orientation of navigation bar', 'align', 'left' ),
    'oddRowColor'         => array( 'Color of odd rows', 'color', '#EEEEFF' ),
    'evenRowColor'	      => array( 'Color of even rows', 'color', '#CCCCCC' ),
    'makeEditable'	      => array( 'Make editable', 'boolean', 'no' ),
    'editableBy'	      => array( 'Editable by', 'editableBy', '0' ),
    'imageWidth'	      => array( 'Image Width<br /><small>Set to 0 to use original or proportional to height</small>', 'number', '100' ),
    'imageHeight'	      => array( 'Image Height<br /><small>Set to 0 to use original or proportional to width</small>', 'number', '0' ),
    'imageBorderSize'     => array( 'Image Border Size', 'number', '1' ),
    'imageBorderColor'    => array( 'Image Border Color', 'color', '#000000' ),
    'editableBy'	      => array( 'Editable by', 'editableBy', '0' ),
    'noSubmissionsMessage'=> array( 'No Data Message', 'textarea', 'There are no submissions yet for this form, or the submissions have been cleared.' ),

    );
    
    
$formSettings = array(

	'title' => array( 'Form Title', 'text', '', changeFormTitle ),
	'title_style' => array( 'Form Title Style', 'style', 'title' ),
	'title_align' => array( 'Form Title Align', 'align', 'left' ),
	'description' => array( 'Form Description', 'textarea', '' ),
    'meta_keywords'    => array( 'Meta Keywords', 'text', '' ),
    'meta_desc' => array( 'Meta Description', 'textarea', '' ),
	'form_alignment' => array( 'Form alignment on the page', 'align', 'center' ),
	'submit_caption' => array( 'Caption of the submit button', 'text', 'Submit' ),
	'show_reset' => array( 'Show reset button?', 'boolean', 'yes' ),
	'reset_caption' => array( 'Caption of the reset button', 'text', 'Reset' ),
	'buttons_alignment' => array( 'Alignment of submit/reset buttons', 'align', 'center' ),
	'is_default' => array( 'Make this form default page for website?', 'boolean', 'no', changeDefaultResource ),
	'generate_report' => array( 'Generate submission reports for this form?', 'boolean', 'no' ),
	'login_form' => array( 'Make this the login form for the content management tools?', 'boolean', 'no', changeLoginForm ),
	'send_mail' => array( 'Send email with submission data?', 'boolean', 'yes' ),
	'mail_to_address' => array( 'Mail To', 'text', '' ),
	'copy_to' => array( 'Mail CC', 'text', '' ),
	'copy_bcc' => array( 'Mail BCC<br /><small>enter multiple addresses with comma delimiter</small>', 'text', '' ),
	'mail_subject' => array( 'Mail Subject', 'text', 'Feedback from Website' ),
	'strip_html' => array( 'Strip HTML in form submission mail?', 'boolean', 'yes' ),
	'form_key' => array( 'Form Key', 'text', '' ),
	'page_title' => array( 'Title of the page when form is dislayed', 'text', '' ),
	'skin_id' => array( 'Skin', 'skin', '0' ),
	'labels_alignment' => array( 'Alignment of labels', 'align', 'right' ),
	'wrap_labels' => array( 'Can field titles be wrapped?', 'boolean', 'yes' ),
	'labels_width' => array( 'Label column width, %<br /><small>leave blank for auto-detection</small>', 'number', '' ),
	'full_textarea' => array( 'Use full width for textareas', 'boolean', 'no' ),
	'email_confirmation' => array( 'Send e-mail confirmation?', 'boolean', 'no' ),
	'email_field' => array( 'Use this field as recipient e-mail address', 'fieldList', '' ),
	'email_subject' => array( 'E-Mail receipt subject', 'text', '\'{form_title}\' Form Submission Confirmation' ),
	'email_contents' => array( 'E-Mail receipt contents', 'textarea', 'You have successfully submited form \'{form_title}\' on the {site} site. Thank you!' ),
	
	);

$menuSettings = array(
    'restrict_to'   => array( 'Show this menu on', 'restrict_to', array( '0', 'all' ), $all ),
    'type'          => array( 'Menu Type<br /><small>(settings differ for each menu type)</small>', 'type', $standard, $all ),
    'override'      => array( 'Override Global Level Settings', 'boolean', 'no', $standard ),
    'flyouttype'    => array( 'Flyout Type', 'flyouttype', 'Vertical', $standard ),
    'menu_arr_w'    => array( 'Menu Arrow Width (pixels)', 'number', 5, $standard ),
    'menu_arr_h'    => array( 'Menu Arrow Height (pixels)', 'number', 10, $standard ),
    'menu_font'     => array( 'Menu Text Font', 'font', 'Arial', $all ),
    'menu_rollover' => array( 'Menu Text Rollover Color', 'color', '#FFFF00', $all ),
    'menu_rollout'  => array( 'Menu Text Rollout Color', 'color', '#FFFFFF', $all ),
    'menu_border'   => array( 'Menu Border Color', 'color', '#0000FF', $all ),
    'menu_shadow'   => array( 'Menu Shadow Color', 'color', '#000000', $all ),
    'bk_rollover'   => array( 'Background Rollover Color', 'color', '#0000FF', $all ),
    'bk_rollout'    => array( 'Background Rollout Color', 'color', '#000099', $all ),
    //'fs_rollover'   => array( 'Font Style Rollover', 'style', 'normal', 'all' ),
    //'fs_rollout'    => array( 'Font Style Rollout', 'style', 'normal', 'all' ),
    'fw_rollover'   => array( 'Font Weight Rollover', 'weight', 'normal', $all ),
    'fw_rollout'    => array( 'Font Weight Rollout', 'weight', 'normal', $all ),
    'fz_rollover'   => array( 'Font Size Rollover', 'size', '9', $all ),
    'fz_rollout'    => array( 'Font Size Rollout', 'size', '9', $all ),
    'align_rollover'=> array( 'Rollover Horizontal Alignment', 'align', 'left', $standard ),
    'align_rollout' => array( 'Rollout Horizontal Alignment', 'align', 'left', $standard ),
    'menu_transparent'=> array( 'Make Menu Transparent?<br /><small>if set, no color will be used used for background, border, or shadow</small>', 'boolean', 'no', $standard ),
    'border_size'   => array( 'Menu Border Size (pixels)', 'number', 1, $standard ),
    'shadow_size'   => array( 'Menu Shadow Size (pixels)', 'number', 1, $standard ),
    'menu_x'        => array( 'Menu X Position (pixels)', 'number', 4, $all ),
    'menu_y'        => array( 'Menu Y Position (pixels)', 'number', 110, $all ),
    'menu_w'        => array( 'Menu Item Width (pixels)', 'number', 138, $standard ),
    'menu_h'        => array( 'Menu Item Height (pixels)', 'number', 21, $standard ),
    'menu_pt'       => array( 'Menu Item Padding Top (pixels)', 'number', 0, $standard ),
    'menu_pl'       => array( 'Menu Item Padding Left (pixels)', 'number', 2, $standard ),
    'menu_pb'       => array( 'Menu Item Padding Bottom (pixels)', 'number', 0, $standard ),
    'menu_pr'       => array( 'Menu Item Padding Right (pixels)', 'number', 0, $standard ),
    'sub_menu_x'    => array( 'Sub-Menu X Offset (pixels)', 'number', 2, $standard ),
    'sub_menu_y'    => array( 'Sub-Menu Y Offset (pixels)', 'number', 0, $standard ),
    //'sub_menu_w'    => array( 'Sub-Menu Item Width (pixels)', 'number', 120, $standard ),
    //'sub_menu_h'    => array( 'Sub-Menu Item Height (pixels)', 'number', 21, $standard ),
    'item_padding'  => array( 'Item Padding', 'number', 0, $tree ),
    'item_spacing'  => array( 'Item Spacing', 'number', 1, $tree ),
    'selected_node_class' => array( 'CSS Class for Selected Node', 'style2', 'normal', $tree ),
    'one_branch'    => array( 'One branch can be opened at same time', 'boolean', 'yes', $tree ),
    'show_images'   => array( 'Show +/- Images<br /><small>(if Explorer option is not enabled)</small>', 'boolean', 'yes', $tree ),
    'eimage'        => array( 'Expanded (+) Image<br /><small>(if Explorer option is not enabled)</small>', 'eimage', 'arrowE_white.gif', $standard.$tree ),
    'cimage'        => array( 'Collapsed (-) Image<br /><small>(if Explorer option is not enabled)</small>', 'cimage', 'arrowC_white.gif', $standard.$tree ),
    'im_width'      => array( 'Image Width', 'number', '14', $tree ),
    'im_height'     => array( 'Image Height', 'number', '14', $tree ),
    'node_indent'   => array( 'Item Indent w/o image', 'number', '0', $tree ),
    'expand_time'   => array( 'Expand Time (miliseconds)', 'number', 200, $standard ),


    //'node_opens_branch' 		=> array( 'Clicking a node opens a branch', 'boolean', 'yes', $tree ),
    'tree_bg_color'				=> array( 'Tree Background Color<br /><small>(leave blank if none)</small>', 'color', '', $tree ),
    'selected_bg_color'			=> array( 'Background Color of Selected Node<br /><small>(leave blank if none)</small>', 'color', '', $tree ),
    //'non_selected_bg_color'		=> array( 'Background Color of Non-Selected Node<br /><small>(leave blank if none)</small>', 'color', '', $tree ),
    //'use_bg_color'				=> array( 'Use Select/Un-Select Background Colors', 'boolean', 'yes', $tree ),

	'explorer_tree'	=> array( 'Use Windows <b>Explorer</b>-like Tree<br /><small>(indentation will be ignored)</small>', 'boolean', 'yes', $tree ),

    'show_folder_image' 		=> array( 'Show folder image', 'boolean', 'yes', $tree ),

    'comment1'			=> array( 'NOTE', 'comment', 'The following image options apply only when the <b>Explorer</b> option is enabled, or when the <b>Show folder image</b> option is enabled (the previous two options). ', $tree ),

    'cfolder'		=> array( 'Closed Folder Image', 'cfolder', 'folder.gif', $tree ),
    'ofolder'		=> array( 'Opened Folder Image', 'ofolder', 'folderopen.gif', $tree ),
    'docimage'		=> array( 'Document Page Image', 'docimage', 'docimage.gif', $tree ),
    'join'			=> array( 'Join Image', 'join', 'join.gif', $tree ),
    'join_bottom'	=> array( 'Join Bottom Image', 'join_bottom', 'joinbottom.gif', $tree ),
    'line'			=> array( 'Vert. Line Image', 'line', 'line.gif', $tree ),
    'minus'			=> array( 'Minus Image', 'minus', 'minus_2.gif', $tree ),
    'plus'			=> array( 'Plus Image', 'plus', 'plus_2.gif', $tree ),
    'minusbottom'	=> array( 'Minus Bottom Image', 'minusbottom', 'minusbottom_2.gif', $tree ),
    'plusbottom'	=> array( 'Plus Bottom Image', 'plusbottom', 'plusbottom_2.gif', $tree ),

     //outlook menu options (not currently used in EasySite 1.4... probably will be added in 1.5)

    //"heightpanel":25, "imageheight":32, "arrowheight":17,"heightcell":76,"coloroutlook":"#666666","arrange_text":"bottom", "rollback":false, "img_arrows_up":"img/arup.gif","img_arrows_dn":"img/ardn.gif"},
    'heightpanel'   => array('Panel height', 'number', '25',$outlook),
    'imageheight'   => array('Image height', 'number', '32', $outlook),
    'arrowheight'   => array('Arrow height', 'number', '17', $outlook),
    'heightcell'    => array('Height cell', 'number', '76', $outlook),
    //'coloroutlook'  => array('Color Outlook', 'coloroutlook', '#666666', $outlook),
    //'arrangetext'   => array('Arrange Text', 'number', 'bottom', $outlook),
    'rollback'      => array('Rollback', 'boolean', 'false', $outlook),
    'img_arrows_up' => array('Image arrows up', 'arupCombo', 'arup.gif', $outlook),
    'img_arrows_dn' => array('Image arrows down', 'ardnCombo', 'ardown.gif', $outlook)
    );
    
    
$commonAreaSettings = array(
	'bgcolor' => array( 'Background Color', 'color', '' ),
    'bgimage'=> array( 'Background Image', 'image', '' ),
    'bgimage_vertical_align'=> array( 'BG Image Vertical Align', 'valign', 'top' ),
    'bgimage_horiz_align'=> array( 'BG Image Horizontal Align', 'align', 'left' ),
    'bgimage_repeat_y'=> array( 'BG Image Vertical Repeat', 'boolean', 'yes' ),
    'bgimage_repeat_x'=> array( 'BG Image Horizontal Repeat', 'boolean', 'yes' ),
    'zindex'   => array( 'Z-index', 'number', 0 ),
);

$areaSettings['footer'] = array(
    'content' => array( 'Content', 'insertable_textarea', '' ),
	'align'   => array( 'Content Alignment', 'align', 'center' ),
    'style'   => array( 'Content Style', 'style', 'small' ),
    'padding'    => array( 'Padding (pixels)', 'number', 2 ),
    'parent'    => array( 'Stretch to', 'footer_area', 'main' ),
    'margin_top'    => array( 'Margin Top', 'number', '0' ),
    'margin_left'    => array( 'Margin Left<br /><small>may be used for left color bar</small>', 'number', '0' ),
    'margin_bottom'    => array( 'Margin Bottom<br /><small>may be used for bottom color bar</small>', 'number', '0' ),
    'margin_right'    => array( 'Margin Right<br /><small>may be used for right color bar</small>', 'number', '0' ),
);

$areaSettings['main'] = array(
    'left'       => array( 'Horizontal (x) offset of main body', 'number', 192 ),
    'top'        => array( 'Vertical (y) offset of main body', 'number', 120 ),
    'padding'    => array( 'Padding (pixels) around main content area', 'number', 2 ),
    'margin_right'=> array( 'Margin Right<br /><small>(may be usefull when widht=100%)</small>', 'number', 0 ),
);

$areaSettings['corner'] = array(
	'width' => array( 'Width<br /><small>(pixels or %)</small>', 'number', '100' ),
	'height' => array( 'Height<br /><small>(pixels or %)</small>', 'number', '100' ),
);

$areaSettings['visible'] = array(
	'width' => array( 'Width<br /><small>Can be pixels or %. Leave blank for auto-detection or use 100% for full screen width.</small>', 'number', '100%' ),
	'height' => array( 'Height', 'visible_height', 'screen' ),
	'auto_center' => array( 'Auto-Center Content?', 'boolean', 'no' ),
	'image_pad_left'	=> array( 'Embedded Image Padding - Left', 'number', 5 ),
	'image_pad_right'	=> array( 'Embedded Image Padding - Right', 'number', 10 ),
	'image_pad_top'		=> array( 'Embedded Image Padding - Top', 'number', 2 ),
	'image_pad_bottom'	=> array( 'Embedded Image Padding - Bottom', 'number', 2 ),
	'bullet_horiz_offset'	=> array( 'Horizontal Bullet Offset', 'number', 5 ),
	'bullet_vert_offset'	=> array( 'Vertical Bullet Offset', 'number', 8 ),
	'bullet_vert_align'		=> array( 'Vertical Bullet Alignment', 'valign', 'top' ),
	'bullet_indent'			=> array( 'Bullet Indentation<br /><small>(pixels or %)</small>', 'number', '5%' ),
    'cellspacing'   => array( 'Space (pixels) between body sections', 'number', 10 ),
    'cellpadding'   => array( 'Padding (pixels) surrounding body sections', 'number', 2 ),
);

$areaSettings['vstrip'] = array(
	'width' => array( 'Width<br /><small>(pixels or %)</small>', 'number', '100' ),
);

$areaSettings['hstrip'] = array(
	'height' => array( 'Height<br /><small>(pixels or %)</small>', 'number', '100' ),
);

?>
