<?php

define( 'ALL_LEVELS', -1 );

class Menu
{
    
    var $_db;
    var $settings;
    
    var $_defaultSettings;
    
    function Menu() {
        
        global $db;
        
        $this->_db = $db;
        
        $this->_defaultSettings = array(
           'override' => 'no',
           'menu_font' => 'Arial',
           'font_size' => 10,
           'menu_rollover' => '',
           'menu_rollout' => '',
           'menu_border' => '#CCCCCC',
           'menu_shadow' => '#CCCCCC',
           'bk_rollout' => '#CCCCCC',
           'bk_rollover' => '#CCCCCC',
           'border_size' => 1,
           'shadow_size' => 2,
           'menu_w' => 130,
           'menu_h' => 21,
           'type' => 'Standard',
           'menu_x' => 3,
           'menu_y' => 130,
           'menu_pt' => 0,
           'menu_pl' => 0,
           'menu_arr_w' => 10,
           'menu_arr_h' => 5,
           'flyouttype' => 'Vertical',
           'sub_menu_x' => 0,
           'sub_menu_y' => 0,
           'sub_menu_w' => 170,
           'sub_menu_h' => 21,
           'expand_time' => 0,
           'fw_rollover' => 'normal',
           'fw_rollout' => 'normal',
           'fs_rollover' => 'normal',
           'fs_rollout' => 'normal',
           'fz_rollover' => '10',
           'fz_rollout' => '10',
           'item_padding' => 0,
           'item_spacing' => 1,
           'one_branch' => 'yes',
           'show_images' => 'yes',
           'im_width' => 10,
           'im_height' => 5,
           'node_indent' => 0,
           'align_rollover' => 'left',
           'align_rollout' => 'left',
           'eimage' => 'blank.gif',
           'cimage' => 'blank.gif',
           'bimage' => 'blank.gif',
           'cfolder' => 'folder.gif' ,
           'ofolder' => 'folderopen.gif' ,
           'docimage' => 'docimage.gif' ,
           'node_opens_branch' => 'yes' ,
           'explorer_tree' => 'yes' ,
           'use_bg_color' => 'no' ,
           'selected_bg_color' => '' ,
           'show_folder_image' => '' ,
           'join' => 'join.gif' ,
           'join_bottom' => 'joinbottom.gif' ,
           'line' => 'line.gif' ,
           'plus' => 'plus_2.gif' ,
           'minus' => 'minus_2.gif' ,
           'plusbottom' => 'plusbottom_2.gif' ,
           'minusbottom' => 'minusbottom_2.gif' ,
           'tree_bg_color' => '' ,
           'selected_node_class' => '' ,
        );
    }
    
    function getDHTMLMenuItemScript( $menuElement, $k='' ) {
        
        $script = $key = $element = '';
        $needKeyTitle = 1;
    
        $ld = '{';
        $rd = '}';
        
        if ( is_numeric( $menuElement ) || $k == 'sub' )
            $ld = $rd = '';
        elseif ( is_string( $menuElement ) )
            $ld = $rd = '"';
            
        if ( is_array( $menuElement ) ) {
            
            // deternime if we need [] delimiters
            // we need them only if array have it's keys as integers
            // we will chek for first key
            
            if ( array_key_exists( '0', $menuElement ) ) {
                $ld = '[';
                $rd = ']';
                $needKeyTitle = 0;
            }
            
        }
        
        if ( !is_array( $menuElement ) ) {
            $script .= $menuElement.", ";
        } else 
        
            foreach( $menuElement as $key => $element ) {
                
                if ( $needKeyTitle ) 
                    $title = "\"$key\":";
                else
                    $title = '';
                
                $content = strlen( $element ) ? $this->getDHTMLMenuItemScript( $element, $key ) : '""';
                
                if ( strlen( $content ) )
                    $script .= $title.$content.', ';
            }
            
            
        $script = trim( $script, ', ' );
        
        if ( strlen( $script ) )
            $script = $ld.$script.$rd;
    
        return $script;
    }



    /**
     * Builds Tree menu nodes script from $menuArray function and links with $linkFunction
     * $menuArray - array of associative arrays that should have 'title' and 'child' keys
     * Example of usage - popupCat/index.php
     */
    function getTreeNodes( &$menuArray, $linkFunction, $child=0 ) {
        
        $script = '';
        
        if ( !count( $menuArray ) )
            return;
        
        if ( $child && count( $menuArray ) > 0 )
            $script = ',';
 
        foreach( $menuArray as $num=>$menuNode ) {
        	if ( $menuNode['resource_type'] != 'timestamp' ) {
        		$title = htmlentities( $menuNode[title] );
        	} else {
        		$title = htmlentities( $menuNode[title] ) . ' in format:<input id=rid type=text style=\'font-size:10px; height: 16px;\' onblur=\'javascript:writeFormat();\'>';
        	}
            $script .= '["'. $title .'", "'. $linkFunction( $menuNode ).'", null';
            $script .= $this->getTreeNodes( $menuNode[child], $linkFunction, 1 );
            $script .= '], ';
        }
    
        $script = rtrim( $script, ", " );
    
        return $script;
    }
    


    /**
     * Get menu settings assinged with menu_id
     */
    function getMenuSettings( $menuId ) {
        
        $query = 'select property, value, param from ' . SETTINGS_TABLE ." where resource_type='menu' and resource_id='$menuId'";
        $settings = $this->_db->getAll( $query);
        
        $menuSettings[ALL_LEVELS] = $this->_defaultSettings;
        $paramSeen = array( ALL_LEVELS=>1 );
        
        foreach ( $settings as $num=>$setting ) {
            if ( $setting['param'] == '' ) 
                $setting['param'] = ALL_LEVELS;
                
            if ( !$paramSeen[intval($setting['param'])] ) {
            	// if this is first setting from particular level
            	// init settings with defaults
            	$menuSettings[intval($setting[param])] = $this->_defaultSettings;
            	
            	$paramSeen[intval($setting[param])] = 1;            	
            }
                
            $menuSettings[intval($setting[param])][$setting[property]] = $setting[value];
        }
        
        $this->settings = $menuSettings;
        
        return $menuSettings;
        
    }
    


    function _getValue ($value, $default)
    {
        return trim( $value ) !== '' ? $value : $default;
    }    

    function drawDHTMLMenu( $parent, $menuId, $level=0, $modcat=0, $module_id=0, $sticky_rollover=0 ) {
        
        global $system, $t;
        
		require_once $t->_get_plugin_filepath('function', 'imgsrc');        
		$smartyParams = array( 'table'=>MENUITEMS_TABLE );
    
        if ( $this->settings[$level-1][override] == 'yes' ) 
            $settingsLevel = $level-1;
        else 
            $settingsLevel = ALL_LEVELS;
            
        if ( !$this->settings[$settingsLevel] )
        	$this->settings[$settingsLevel] = $this->_defaultSettings;
            
        extract( $this->settings[$settingsLevel] );
        
        if ( $menu_transparent == 'yes' ) {
           $menu_border = '';
           $menu_shadow = '';
           $bk_rollout = '';
           $bk_rollover = '';
        }
    
        // note: temporarily use submenu width and height for overall menu w/h
    
        $sub_menu_w = $menu_w;
        $sub_menu_h = $menu_h;
    
    
        if ( !$modcat ) {
    
            $query = 'select * from '. MENUITEMS_TABLE." where parent = $parent and menu_id = '$menuId' order by _order asc;";
    
        } else {
            
            $module = $this->_db->getRow( 'select module_key, site_key from '. MODULES_TABLE ." where id='$module_id'" );
            $moduleKey = $module['module_key'];
    
            $query = "select id, parent, _order, title, 0 as hidden, 'modcat' as resource_type from ". MODULECATEGORIES_TABLE." where parent = $parent and module_key = '$moduleKey' and site_key='$module[site_key]' order by _order asc;";
            
        }
        
        $script = '';
        
        $links = array();
    
        $result = $this->_db->query( $query );
    
        while( $data = $result->fetchRow() ) {
            
            if ( !$system->isVisible( $data['restrict_to'] ) )
            	continue;
            	
        	if ( $modcat ) 
                $data[resource_id] = $module_id.'_'.$data[id].'_1';
    
            // array that contain all properties 
            // of the menu item
            // see http://javascript.cooldev.com/doc/menu/#s4_1 for details
            $menuItem = array();
    
            $menuItem[code] = $data[title];
            $menuItem[url] = 'javascript:;';
            $menuItem[target] = ( $data[target] ? $data[target] : '_self' );
            
            $data[in_template] = ( $data[in_template] ? $data[in_template] : 'no' );
            
            $id = $data[id];
    
            $url = $system->getURL( $data['resource_type'], $data['resource_id'], $data[in_template] );
    
            if ( $data['resource_type'] == 'url' ) {
                $menuItem[url] = $url;
                $menuItem[target] = ( $data[target] ? $data[target] : '_blank' );
            }
            elseif ( trim( $url ) )
                $menuItem[url] = $url;
                
            $links[] = $url;
    
            $data[item_width]  = ( $data[item_width]  > 0 ) ? $data[item_width]  : $sub_menu_w;
            $data[item_height] = ( $data[item_height] > 0 ) ? $data[item_height] : $sub_menu_h;
            
            $menuItem[format][size] = array( $data[item_height], $data[item_width] );
    
            // Left,Top,Right,Bottom borders
    
            if ( $data[borders] && ereg( "([0-9]{1,2}),([0-9]{1,2}),([0-9]{1,2}),([0-9]{1,2})", $data[borders] ) ) 
                $borders = explode( ',', $data[borders] ); 
            else
                $borders = array( $border_size, $border_size, $border_size, $border_size );//default
    
            // do not allow 0 w/h images
            $data[image_width] = ($data[image_width]) ? $data[image_width] : 15;
            $data[image_height] = ($data[image_height]) ? $data[image_height] : 15;
            
            $menuItem[format][imgsize] = array( $data[image_height], $data[image_width] );
    
            if ( $data[image_out] ) {
            	$smartyParams['field'] = 'image_out';
            	$smartyParams['id'] = $data[id];
                $menuItem[format][image] = smarty_function_imgsrc($smartyParams, $t);
            }
                    
            if ( $data[image_over] ) {
            	$smartyParams['field'] = 'image_over';
            	$smartyParams['id'] = $data[id];
                $menuItem[format][oimage] = smarty_function_imgsrc($smartyParams, $t);
            }
    
            if ( $level > 0 ) {
                
                $itemoff_x = 0 + $data[y_offset] + $yOffset + $sub_menu_h - $border_size;
                $itemoff_y = 0 + $data[x_offset];
                
            } else { 
                
                if ( $flyouttype == 'Horizontal' ) {
                
                    $itemoff_x = 0 + $data[y_offset];
                    $itemoff_y = 0 + $data[x_offset] + $xOffset + $menu_w - $border_size;
                
                } else {
    
                    $itemoff_x = 0 + $data[y_offset] + $yOffset + $menu_h - $border_size;
                    $itemoff_y = 0 + $data[x_offset];
                
                }
            }
            
            $menuItem[format][itemoff] = array( $itemoff_x, $itemoff_y );
            $menuItem[format][arrsize] = array( $menu_arr_h, $menu_arr_w );
            
            $menuItem[format][oarrow] = 'http://' . $_SERVER['SERVER_NAME'] . DOC_ROOT . 'images/menu/'. $eimage;
            $menuItem[format][arrow] = 'http://' . $_SERVER['SERVER_NAME'] . DOC_ROOT . 'images/menu/'. $cimage;
            
            $menuItem[format][style][border] = $border_size;
            $menuItem[format][style][borders] = $borders;
            $menuItem[format][style][shadow] = $shadow_size;
    
            $menuItem[format][style][color] = array();
            $menuItem[format][style][color][border] = $menu_border;
            $menuItem[format][style][color][shadow] = $menu_shadow;
            
            $menuItem[format][style][color][bgON] = $data[out_color] ? $data[out_color] : $bk_rollout;
                //$menuItem[format][style][color][imagebg] =
                    //$data[out_color] ? $data[out_color] : $bk_rollout;
            
            $menuItem[format][style][color][bgOVER] =  
                //$menuItem[format][style][color][oimagebg] =
                    $data[over_color] ? $data[over_color] : $bk_rollover;
            
            // when mouse is positioned over the item
            $menuItem[format][style][css] = array();
            
            $menuItem[format][style][css][OVER] = 
                ($data[over_style]) ? $data[over_style] : 'menu' . $menuId .$settingsLevel. 'Over';
    
            $menuItem[format][style][css][ON]   = 
                ($data[out_style]) ? $data[out_style] : 'menu' . $menuId .$settingsLevel. 'On';
    
            
            if ($flyouttype == 'Horizontal') {
                if ($level > 0) {
                    $leveloff_x = 0 + $data[y_offset] + $sub_menu_y;
                    $leveloff_y = 0 + $sub_menu_w - $border_size + $sub_menu_x + $data[x_offset];
                }
                else {
                    $leveloff_x = 0 + $data[y_offset] + $menu_h - $border_size + $sub_menu_y;
                    $leveloff_y = 0 + $sub_menu_x + $data[x_offset];
                }
            }
            else {
                if ($level > 0) {
                    $leveloff_x = 0 + $data[y_offset] + $sub_menu_y;
                    $leveloff_y = 0 + $sub_menu_w - $border_size + $sub_menu_x + $data[x_offset];
                }
                else {
                    $leveloff_x = 0 + $data[y_offset] + $sub_menu_y;
                    $leveloff_y = 0 + $menu_w - $border_size + $sub_menu_x + $data[x_offset];
                }
            }
            
            $menuItem[sub][0][delay] = $expand_time;
            $menuItem[sub][0][leveloff] = array( $leveloff_x, $leveloff_y );
    
            // offset the item below the current item if the current item has larger height
    
            if ( $data[item_width] > 0 )
                $xOffset = $data[item_width] - $menu_w;
            else
                $xOffset = 0;
    
            if ( $data[item_height] > 0 )
                $yOffset = $data[item_height] - $menu_h;
            else
                $yOffset = 0;
                
                
            $isActive = $system->currentResource( $data['resource_type'], $data['resource_id'] );
                
            if ( ( $data[sticky_rollover] || $sticky_rollover ) && $isActive ) {
                
                // --------------------------------------------
                // menu item should be always in 'active' state
                // rewrite 'out' properies with 'over' ones
                // --------------------------------------------
                
                if ( $menuItem[format][oimage] )
                	$menuItem[format][image] = $menuItem[format][oimage];
                $menuItem[format][arrow] = $menuItem[format][oarrow];
                $menuItem[format][style][color][bgON] = $menuItem[format][style][color][bgOVER];
                $menuItem[format][style][color][imagebg] = $menuItem[format][style][color][oimagebg];
                $menuItem[format][style][css][ON] = $menuItem[format][style][css][OVER];
            }
    
                
            if ( $data['resource_type'] != 'modcat' ) {
                list( $childScript, $childLinks ) = $this->drawDHTMLMenu( $id, $menuId, $level + 1 );
            }
            else {
                
                // if we are building menu from the module
                
                if ( !$modcat ) {
                    list( $module_id, $cat_id, $overwrite ) = split( '_', $data[resource_id] );
                    $data[id] =  $cat_id ? $cat_id : 0;
                    $sticky_rollover = $data[sticky_rollover];
                }
                if ( $modcat || $overwrite ) 
                    list( $childScript, $childLinks ) = $this->drawDHTMLMenu( $data[id], $menuId, $level + 1, 1, $module_id, $sticky_rollover ); 
            }
                
            if ( empty( $childScript ) ) 
                $menuItem[sub] = '';
            else 
                $menuItem[sub] = '['.$this->getDHTMLMenuItemScript($menuItem[sub][0]).','.$childScript.']';
                
            if ( $childLinks )
                $links = array_merge( $links, $childLinks );
                
            $script .= $this->getDHTMLMenuItemScript( $menuItem ) . ', ';
    
        }
        
        return array( trim( $script, ', ' ), $links );
    }



    function drawTreeMenu( $parent, $menuId, $level=0, $modcat = 0, $module_id=0 )
    {
        global $system, $t;
    
		require_once $t->_get_plugin_filepath('function', 'imgsrc');        
		$smartyParams = array( 'table'=>MENUITEMS_TABLE );
		
        $variables = array (
           eimage      => array ( 'eimage', 'blank.gif' ),
           cimage      => array ( 'cimage', 'blank.gif' ),
    
        );
    
        if ( !$modcat ) {
    
            $query = 
                "select 
                    id, 
                    parent, 
                    title, 
                    hidden, 
                    resource_type, 
                    resource_id,
                    image_height,
                    image_width,
                    image_out,
                    image_over 
                 from ".MENUITEMS_TABLE."
                 where 
                    parent = $parent and 
                    menu_id = '$menuId' 
                order by _order asc;";
    
        } else {
            $moduleKey = $this->_db->getOne( 'select module_key from '. MODULES_TABLE ." where id='$module_id' " );;
    
            $query = 
                "select
                    id, 
                    parent, 
                    _order, 
                    title, 
                    0 as hidden, 
                    'modcat' as resource_type 
                 from ". MODULECATEGORIES_TABLE." 
                 where 
                    parent = $parent and 
                    module_key = '$moduleKey'
                 order by _order asc;";
    
        }
        
        $script = '';
        $links = array();
        
        $result = $this->_db->query( $query );
    
        //if ( $parent != 0 && $result->numRows() )
        //    $script = ',';
    
        while( $data = $result->fetchRow() )
        {
            
            if ( $modcat ) 
                $data[resource_id] = $module_id.'_'.$data[id].'_1';
    
            $menuItem = array();
            
            $id = $data[id];
            
            $pageUrl = $system->getURL( $data['resource_type'], $data['resource_id'] );
            
            $links[] = $pageUrl;
    
            $script .= '["'. $data[title] .'", "' . $pageUrl . '", null';
    
            if ( $data['resource_type'] != 'modcat' ) 
                list( $childScript, $childLinks ) = $this->drawTreeMenu( $id, $menuId, $level + 1 );
            else {
                
                // if we are building menu from the module
                
                if ( !$modcat ) {
                    list( $module_id, $cat_id, $overwrite ) = split( '_', $data[resource_id] );
                    $data[id] = $cat_id ? $cat_id : 0;
                }
                if ( $modcat || $overwrite ) 
                    list( $childScript, $childLinks ) = $this->drawTreeMenu( $data[id], $menuId, $level + 1, 1, $module_id ); 
            }
    
            // -----------------------------------------
            // get images if this is not module category
            // -----------------------------------------
            
            $imageScript = '';
            
            if ( $data[image_out] || $data[image_over] ) {
    
                // do not allow 0 w/h images
                $data[image_width] = $data[image_width] ? $data[image_width] : 15;
                $data[image_height] = $data[image_height] ? $data[image_height] : 15;
    
                if ( !empty( $child ) ) {
                    
                    if ( $data[image_over] ) {
                    	$smartyParams['field'] = 'image_out';
                    	$smartyParams['id'] = $data[id];
                        $imageRollover = smarty_function_imgsrc($smartyParams, $t);
                            //DOC_ROOT . "image.php?field=image_out&table=".MENUITEMS_TABLE."&id=" . $data[id];
                    }
    
                    if ( $data[image_out] ) {
	                   	$smartyParams['field'] = 'image_over';
    	               	$smartyParams['id'] = $data[id];
                        $imageRollout = smarty_function_imgsrc($smartyParams, $t);
                            //DOC_ROOT . "image.php?field=image_over&table=".MENUITEMS_TABLE."&id=" . $data[id];
                    }
    
                    $imageScript=",{format:{buttons:[ '$imageRollover', '$imageRollout', '']}}";
                }
                else {
                    if ($data[image_out]) {
	                   	$smartyParams['field'] = 'image_out';
    	               	$smartyParams['id'] = $data[id];
                        $nodeImage = smarty_function_imgsrc($smartyParams, $t);
                            //DOC_ROOT . "image.php?field=image_out&table=".MENUITEMS_TABLE."&id=" . $data[id];
                    }
                    
                    $imageScript=",{format:{folders:[ '100','100','$nodeimage']}}";
                }
                
            }
        
            if ( !empty( $childScript ) )
                $script .= $imageScript . ', ' . $childScript . '], '; 
            else 
                 $script .= $imageScript . '], ';
                 
            if ( $childLinks )
                $links = array_merge( $links, $childLinks );
    
    
        } // while
    
        $script = rtrim( $script, ", " );
        
        return array( $script, $links );
    }


}

?>