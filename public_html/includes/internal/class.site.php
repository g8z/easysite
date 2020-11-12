<?php

// this is used to create a site
define ( 'DEFAULT_USERNAME', 'admin' );
define ( 'DEFAULT_PASSWORD', 'pass' );

/**
 * Class for managing the user-defined sites in EasySite.
 * These are sites created with the User admin tool. This file
 * is references in /manage/editUser.php
 */
class ES_Site {

    var $db;
    var $default;

    function ES_Site( &$db, $default = 'default' ) {

        $this->db = $db;
        $this->default = $default;
    }

    /**
     * Check to see if a site with this key exists. If $user is specified, then
     * checks for a site of this user. Returns true or false
     */
    function exists( $key, $user ) {

        global $site;

        $id = $db->getOne( 'select id from ' . USERS_TABLE . " where user_site_key = '$key' and id <> '$user' and site_key = '$site'" );

        return $id > 0 ? true : false;
    }

    /**
     * Update the content-management tool permissions for this site (this is user id-specific,
     * as well as site_key specific
     */
    function setPermissions( $key, $user, $permissions ) {

        $table = PERMISSIONS_TABLE;

        $this->db->query( "delete from $table where user_id = '$user' and site_key = '$key'" );

        // re-add the permissions for this user/site

        $arr = explode( ',', $permissions );

        foreach( $arr as $p ) {

            if ( trim( $p ) ) {

                $this->db->query( 'insert into ' . PERMISSIONS_TABLE . "
                (
                    resource_type,
                    site_key,
                    user_id
                )
                values
                (
                    '$p',
                    '$key',
                    '$user'
                )
                " );
            }
        }
    }

    /**
     * Content-management permissions for this user's site
     */
    function getPermissions( $key, $user ) {

        $data = $this->db->getAll( 'select resource_type from ' . PERMISSIONS_TABLE . " where user_id = '$user'" );

        $arr = array();

        foreach( $data as $index => $row ) {
            $arr[$row[resource_type]] = 1;
        }

        return $arr;
    }

    function setLastUpdate( $site ) {

        $this->db->query( 'update ' . SITES_TABLE . " set last_updated = now() where site_key = '$site'" );
    }

    /**
     * Creates a new EasySite - based website with this key. $adminUser is the integer
     * user id of the administrator of this site.
     */
    function create( $key, $adminUser, $parentData=array(), $parentSite='', $skin_id=0 ) {
        
        global $site;
        
        if ( $parentSite )
        	$site = $parentSite;
        
        // --------------------------
        // define some functions here
        // --------------------------
        
        $maxIds = array();
        
        function getUpdatedId( $table, $currentId ) {
            global $maxIds, $db;
            
            if ( !intval( $currentId ) )
                return $currentId;

            if ( !$maxIds[$table] )
                $maxIds[$table] = $db->getOne( 'select max(id) from ' . $table );
                
            $offset = $db->getOne( 'select count(id) from '. $table .' where id <= '. $currentId );
            
            return $maxIds[$table] + $offset;
        }
        
        
        // update site default resource id
        // it is called only once
        function parseDefaultId( $row ) {
            
            global $db;
            
            // default for new page
            $defaultId = $db->getOne( 'select max(id) from ' . PAGES_TABLE ) + 1;
            
            switch ( $row[default_resource_type] ) {
                case 'page':
                    if ( $parentData[c_pages] ) 
                        $value = getUpdatedId( PAGES_TABLE, $row[default_resource_id] );
                    else 
                        $value = $defaultId;
                    break;

                case 'form':
                    if ( $parentData[c_forms] ) 
                        $value = getUpdatedId( FORMS_TABLE, $row[default_resource_id] );
                    else
                        $value = $defaultId;
                    break;

                case 'module':
                    $value = getUpdatedId( MODULES_TABLE, $row[default_resource_id] );
                    break;
                    
                default:
                    $value = $row[default_resource_id];
                    break;
            }
            
            return $value;
        }
        
        // update site default resource type
        // it is called only once
        function parseDefaultType( $row ) {
            
            // default for new page
            $defaultType = 'page';
            
            switch ( $row[default_resource_type] ) {
                case 'form':
                    if ( $parentData[c_forms] ) 
                        $value = 'form';
                    else
                        $value = $defaultType;
                    break;

                case 'module':
                    $value = 'module';
                    break;
                    
                default:
                    $value = $row[default_resource_type];
                    break;
            }
            
            return $value;
        }

        $row = $this->db->getRow( 'select * from ' . SITES_TABLE . " where site_key='$site'" );
        $defaultId = parseDefaultId( $row );
        $defaultType = parseDefaultType( $row );
        $loginFormId = getUpdatedId( FORMS_TABLE, $row[login_form_id] );


        // create new site record
        
        $this->db->query( 'insert into ' . SITES_TABLE . "
        (
            site_key,
            title,
            admin_id,
            last_updated,
            default_resource_type,
            default_resource_id,
        	login_form_id
        )
        values
        (
            '$key',
            'My Website',
            '$adminUser',
            now(),
            '$defaultType',
            '$defaultId',
            '$loginFormId'
        )" );
        
        
        if ( !$parentData['c_settings'] || !$parentData['c_styles'] || !$parentData['c_menus'] || !$parentData['c_forms'] ) {
            
            // get sample_data.sql queries
            
            @set_time_limit( 0 );
              
            $fileName = FULL_PATH . 'sql/sample_data.sql';
        
            $fd = @fopen ($fileName, 'rb');
            $data = @fread ($fd, filesize ($fileName));
            @fclose ($fd);
              
            $queries = splitSql($data);
            
        }

            
        if ( !$parentData[c_pages] ) {

            // add some pages data for this new site
    
            $this->db->query( 'insert into ' . PAGES_TABLE . "
            (
                site_key,
                title
            )
            values
            (
                '$key',
                'Home Page'
            )" );
    

    
            $newPageID = $this->db->getOne( 'select max(id) from ' . PAGES_TABLE . " where site_key = '$key'" );
    
            // add a few seconds to this sample page, allowing the user to login
    
            if ( !DEMO_MODE ) {
    
                $pageSections = array(
                    '1' => array( 'title', 'Welcome!' ),
    
                    '2' => array( 'normal', 'Your website has been created. All you have to do now is add content to it with the content management tools.' ),
    
                    '3' => array( 'subtitle', 'Getting Started' ),
    
                    '4' => array( 'normal', 'First, you must authenticate yourself to the system.<br /><br /><a href=' . DOC_ROOT . ADMIN_DIR . '>Click here</a> to login and start editing your website. The username and password to access the administrative area of your website may have been e-mailed to you, or provided to you by the system administrator. You can always access the content management tools for your website by going to www.your_domain.com/' . ADMIN_DIR . '/index.php?site=' . $key ),
    
                    '5' => array( 'subtitle', 'Common Questions' ),
    
                    '6' => array( 'normal', '<b>How do I change my login information?</b><br />You must contact the system administrator to do this. Your website is a member site, which means that not all administrative tools are available to you.' ),
    
                    '7' => array( 'normal', '<b>How do I change my website design & style?</b><br />Depending on the permissions that have been established for your website, you may or may not be able to change your site\'s settings and styles. You can check this by logging into the content management tools, using the above link. The reason for this is some administrators may wish to enforce a consistent look across all member pages.' ),
    
                    '8' => array( 'normal', '<b>How do I change the "My Website" title?</b><br />This is a layer which can be changed using the layer editing tool. If your system administrator has not provided access to this tool, then you may need to contact him or her to change this text.' ),
    
                    '9' => array( 'normal', '<b>How do I add/edit/remove menus?</b><br />Your site\'s menus can be edited with the Menu Editor after logging in. If your system administrator has not provided access to this tool, then you may need to contact him or her to edit the menus.' ),
                );
            }
            else {
    
                $pageSections = array(
                    '1' => array( 'title', 'Welcome to EasySite!' ),
    
                    '2' => array( 'normal', 'This is a demo website created with EasySite. To demo the content management tools which come with EasySite, please <a href=' . DOC_ROOT . ADMIN_DIR . '>login</a> using username = <b>admin</b>, and password = <b>pass</b>' ),
    
                    '4' => array( 'normal', '<a href=' . DOC_ROOT . ADMIN_DIR . '>Click here</a> to login and start editing your website, using the content management tools that are built-into EasySite (or, click on the "Admin Access" link at the bottom of the page).' ),
    
                    '5' => array( 'subtitle', 'Common Questions' ),
    
                    '6' => array( 'normal', '<b>How do I change my login information?</b><br />After logging into the content management tools, click on the Users and Groups option. You will be able to add groups of users, and specify the permissions that these groups have. In the registered version of EasySite, each user can be given his or her own website, too, using these same content-management tools. This option has been disabled in this demo, however.' ),
    
                    '7' => array( 'normal', '<img align=left src=images/help/site_settings.gif style="margin-right:6px"><b>How do I change my site design?</b><br />You can change almost any aspect of your website design with the Styles and Settings tools. Styles define the font properties for all parts of your website, and the Settings tool allows you to change global options, like the page body width and height, and the background color.<br /><br />The idea of EasySite is to allow even novice users full control over the website look and feel, using a set of intuitive content management tools, including the background design. Since EasySite is a layer-based content management system, the body and menu text floats over the background design, so you do not have to be concerned with HTML at all (remember the days of slicing images to fit the HTML?).' ),
    
                    '8' => array( 'normal', '<b>How do I change the "ACME Widgets" title?</b><br />This is a layer which can be changed using the layer editing tool. Layers can be positioned absolutely at any coordinates on your screen, and can overlap other screen elements, including other layers (this is controlled by the z-order of the layer). You can also specify which layers should appear on which page. Besides text, layers can contain images, which also can be positioned anywhere on the screen.' ),
    
                    '9' => array( 'normal', '<img align=left src=images/guide/menu_editor.gif style="margin-right:6px"><b>How do I add/edit/remove menus?</b><br />Your site\'s menus can be edited with the Menu Editor after logging in. In addition to modifying the structure and organization of the menu, you can change the type of menu from standard to tree, and specify special settings for individual menu items.<br /><br />There is no limit to the number of menus that may appear on your website, and there is no limit to the number of items or levels that may exist in a menu. Menu items can link to an existing page or form, or link to an external URL.' ),
    
                    '10' => array( 'normal', '<img align=left src=images/help/image_key.gif style="margin-right:6px"><b>How do I change the blue bars at the top and left?</b><br />These are known as "Color Bars", and they are a very common design paradigm in many websites. Of course, these are totally optional, and can be removed in the Settings content management tool.<br /><br />Each bar is a repeating horizontal or veritcal image, 1 pixels width or high (the vertical color bar is 1 pixel high and about 200 pixels wide, and the horizontal color bar is 1 pixel wide, and about 200 pixels high). The background "corner" image merges the vertical and horizontal color bars together so that they flow more naturally, and is a good place to put your organization\'s logo.' ),
    
                    '11' => array( 'normal', 'Thanks for trying the demo! EasySite includes all of the content-management tools that you see in this demo, and is only $5. It is built using the Smarty template engine (<a target=_blank href=http://smarty.php.net>http://smarty.php.net</a>) and the Pear libraries for database abstraction (<a target=_blank href=http://pear.php.net>http://pear.php.net</a>). These are two of the best technologies for PHP, so you can be assured that the best programming practices were used when developing EasySite. Remember: upgrades are free from TUFaT.com!' ),
                );
    
                // add a couple of layers
    
                $this->db->query( "INSERT INTO " . LAYERS_TABLE . " (_left, top, width, height, align, valign, zorder, bgcolor, padding, style, format, content, restrict_to, title, site_key, img_thumb, img_thumb_path, img_large, img_large_path, img_anchor, img_link) VALUES (10, 6, 400, 50, 'left', 'bottom', 0, '', 0, 'title', '', '<font color=white><b>ACME Widgets</b></font>', '', '', '$key', '', '', '', '', '', '');" );
    
                $this->db->query( "INSERT INTO " . LAYERS_TABLE . " (_left, top, width, height, align, valign, zorder, bgcolor, padding, style, format, content, restrict_to, title, site_key, img_thumb, img_thumb_path, img_large, img_large_path, img_anchor, img_link) VALUES (10, 58, 400, 50, 'left', 'top', 0, '', 0, 'small', '', '<font face=Verdana color=#FFFF99>Defining the future of widgets since 1984!</font>', '', '', '$key', '', '', '', '', '', '');" );
            }
    
            foreach( $pageSections as $order => $info ) {
    
                $style      = $info[0];
                $content    = addslashes( $info[1] );
    
                $this->db->query( 'insert into ' . SECTIONS_TABLE . "
                    (
                        site_key,
                        page_id,
                        _order,
                        style,
                        content
                    )
                    values
                    (
                        '$key',
                        '$newPageID',
                        '$order',
                        '$style',
                        '$content'
                    )" );
            }
        }
          
        if ( !$parentData[c_menus] ) {
              
            // add some menus
    
            $this->db->query( 'insert into ' . MENUS_TABLE . " ( site_key, title ) values ( '$key', 'Default Menu' )" );
    
            $newMenuId = $this->db->getOne( 'select max(id) from ' . MENUS_TABLE . " where site_key = '$key' limit 1" );
    

            // add a few sample menu items, link one to the home page
    
            $order = 0;
    
            $menuItems = array(
                    array( 'page', $newPageID, 'My Home Page' ),
                    array( 'url', 'http://www.cnn.com', 'News & Events' ),
                );
    
            foreach( $menuItems as $arr ) {
                $this->db->query( 'insert into ' . MENUITEMS_TABLE . "
                (
                    site_key,
                    menu_id,
                    _order,
                    resource_type,
                    resource_id,
                    title
                )
                values
                (
                    '$key',
                    '$newMenuId',
                    '$order',
                    '$arr[0]',
                    '$arr[1]',
                    '$arr[2]'
                )
                ");
                $order++;
            }
            
        }
          
          
        if ( !$parentData['c_styles'] ) {
              
              // insert default styles from sample_data.sql
              
              foreach ( $queries as $sql ) {
                  
                  $fields = array();
                  $values = array();
                  
                  if ( !parseInsertSql( $sql, DB_PREFIX.'_styles', $fields, $values ) )
                      continue;
                 
                  $idPos = array_search( 'id', $fields );
                  $siteKeyPos = array_search( 'site_key', $fields );
                  
                  $values[$idPos] = '';
                  $values[$siteKeyPos] = $key;
                  
                  $insertFields = implode( ', ', $fields );
                  $insertValues = "'" . implode( '\', \'', $values ) . "'";

                  $query = "insert into ".DB_PREFIX."_styles ($insertFields) values ($insertValues)";
                  
                  $this->db->query( $query );
  
              }
        }
        
        
        if ( !$parentData['c_forms'] ) {
              
              // insert default styles from sample_data.sql
              
              foreach ( $queries as $sql ) {
                  
                  $fields = array();
                  $values = array();
                  
                  $table = '';
                  
                  if ( parseInsertSql( $sql, DB_PREFIX.'_forms', $fields, $values ) ) {
                  	  $table = DB_PREFIX.'_forms';
                  	  
	                  $idPos = array_search( 'id', $fields );
		              $values[$idPos] = getUpdatedId( FORMS_TABLE, $values[$idPos] );
                  }
                  else if ( parseInsertSql( $sql, DB_PREFIX.'_form_sections', $fields, $values ) ) {
                  	  $table = DB_PREFIX.'_form_sections';
                  	  
	                  $idPos = array_search( 'id', $fields );
                  	  $formIdPos = array_search( 'form_id', $fields );
		              $values[$idPos] = '';
	                  $values[$formIdPos] = getUpdatedId( FORMS_TABLE, $values[$formIdPos] );
                  }
                  else
                      continue;
                 
                  $siteKeyPos = array_search( 'site_key', $fields );
                  
                  $values[$siteKeyPos] = $key;
                  
                  $insertFields = implode( ', ', $fields );
                  $insertValues = "'" . implode( '\', \'', $values ) . "'";

                  $query = "insert into $table ($insertFields) values ($insertValues)";
                  
                  $this->db->query( $query );
  
              }
        }

        
        if ( !$parentData['c_settings'] || !$parentData['c_menus'] || !$parentData['c_forms'] ) {
            
            // insert default settings from sample_data.sql
            // do not insert menu settings ( menu_id != 0 )
            
              foreach ( $queries as $sql ) {
                  
                  $fields = array();
                  $values = array();
                  
                  if ( !parseInsertSql( $sql, DB_PREFIX.'_settings', $fields, $values ) )
                      continue;
                  
                  $idPos = array_search( 'id', $fields );
                  $resourceTypePos = array_search( 'resource_type', $fields );
                  $resourceIdPos = array_search( 'resource_id', $fields );
                  $siteKeyPos = array_search( 'site_key', $fields );
                  
                  switch ( $values[$resourceTypePos] ) {
                  	  case 'site':
	                      $values[$resourceIdPos] = $key;	
                  	  	  break;
                  	  	  
                  	  case 'menu':
	                      if ( !$parentData['c_menus'] )
	                      	  $values[$resourceIdPos] = $newMenuId;	
	                      else
	                      	  continue;
                  	  	  break;
                  	  	  
                  	  case 'form':
	                      if ( !$parentData['c_forms'] )
	                      	  $values[$resourceIdPos] = getUpdatedId( FORMS_TABLE, $values[$resourceIdPos] );	
	                      else
	                      	  continue;
                  	  	  break;
                  	  	  
                  	  default:
                  	  	  continue;
                  	  	  break;
                  }

                  $values[$idPos] = '';
                  $values[$siteKeyPos] = $key;
                  
                  $insertFields = implode( ', ', $fields );
                  $insertValues = "'" . implode( '\', \'', $values ) . "'";

                  $query = "insert into ".DB_PREFIX."_settings ($insertFields) values ($insertValues)";
                  
                  $this->db->query( $query );
  
              }
        }

        // ------------------------
        // add the parent site data
        // ------------------------
        
        // create field dependendance structure
        // for each table specify fields (ids) 
        // that needs to be updated and set the table name to add values to id from
        // or the user defined function that will parse value and correct it
        
        $optionTables = array( 
            
        'c_pages' => array(
            
            PAGES_TABLE          => array( 'id'          => PAGES_TABLE,
                                           'skin_id'     => parseSkinId,
                                           'counter'     => emptyInt ),

            SECTIONS_TABLE       => array( 'id'          => SECTIONS_TABLE,
                                           'page_id'     => PAGES_TABLE ),
            ),
          
        'c_forms' => array(
            FILTEROVERRIDES_TABLE=> array( 'id'          => FILTEROVERRIDES_TABLE,
                                           'form_id'     => FORMS_TABLE,
                                           'section_id'  => FORMSECTIONS_TABLE,
                                           'report_field_id'  => FORMSECTIONS_TABLE ),
 
            FORMSUBMISSIONS_TABLE=> array( 'id'          => FORMSUBMISSIONS_TABLE,
                                           'form_id'     => FORMS_TABLE,
                                           'user_id'     => 'setOwner',
                                           'field_id'    => FORMSECTIONS_TABLE,
                                           'redirect_id' => FORMREDIRECTS_TABLE ),
 
            FORMREDIRECTS_TABLE  => array( 'id'          => FORMREDIRECTS_TABLE,
                                           'form_id'     => FORMS_TABLE,
                                           'section_id'  => FORMSECTIONS_TABLE,
                                           'redirect_id' => parseRedirectId ),
 
            FORMSECTIONS_TABLE   => array( 'id'          => FORMSECTIONS_TABLE,
                                           'form_id'     => FORMS_TABLE ),

            FORMGROUPS_TABLE     => array( 'id'          => FORMGROUPS_TABLE,
                                           '_group'      => FORMSECTIONS_TABLE ),

            FORMS_TABLE          => array( 'id'          => FORMS_TABLE,
                                           'redirect_id' => parseRedirectId,
                                           'other_redirect_id' => parseRedirectId,
                                           'search_report_id'  => REPORTS_TABLE,
                                           //'skin_id'     => parseSkinId,
                                           'counter'     => emptyInt,
                                           'counter_submit'=> emptyInt ),

            ),
        
        'c_reports' => array(
            
            REPORTS_TABLE        => array( 'id'          => REPORTS_TABLE,
                                           'resource'    => FORMS_TABLE ),

            REPORTCONDITIONS_TABLE => array( 'id'        => REPORTCONDITIONS_TABLE,
                                           'report_id'   => REPORTS_TABLE,
                                           'resource'    => FORMS_TABLE,
                                           'section_id'  => FORMSECTIONS_TABLE ),
                                           
            REPORTFIELDS_TABLE     => array( 'id'        => REPORTFIELDS_TABLE,
                                           'report_id'   => REPORTS_TABLE,
                                           'field_id'    => FORMSECTIONS_TABLE ),
                                           
            REPORTGROUPS_TABLE     => array( 'id'        => REPORTGROUPS_TABLE,
                                           'report_id'   => REPORTS_TABLE,
                                           'sum_field_id'=> FORMSECTIONS_TABLE,
                                           'field_id'    => FORMSECTIONS_TABLE ),
                                           
            EMBEDDEDREPORTS_TABLE  => array( 'id'        => EMBEDDEDREPORTS_TABLE,
                                           'into_id'     => REPORTS_TABLE,
                                           'source_id'   => REPORTS_TABLE ),
            ),
          
        'c_layers' => array(
            LAYERS_TABLE         => array( 'id'          => LAYERS_TABLE,
                                           'restrict_to' => parseRestrictTo ),
            ),
            
        'c_menus' => array(
            MENUITEMS_TABLE      => array( 'id'          => MENUITEMS_TABLE, 
                                           'menu_id'     => MENUS_TABLE,
                                           'parent'      => MENUITEMS_TABLE,
                                           'resource_id' => parseResourceId, 
                                           'restrict_to' => parseRestrictTo ),

            MENUS_TABLE          => array( 'id'          => MENUS_TABLE, 
            							   'restrict_to' => parseRestrictTo ),
            
            ),
        'c_styles' => array(
            STYLES_TABLE         => array( 'id'          => STYLES_TABLE,
                                           'skin_id'     => SKINS_TABLE ),
            ),
        
        'c_skins' => array(
            SKINS_TABLE         => array(  'id'          => SKINS_TABLE,
                                           'owner'       => 'setOwner',
                                           'shared'      => emptyInt,
                                           'share_groups'=> emptyText ),
            ),
        
        'c_files' => array(
            FILES_TABLE          => array( 'id'          => FILES_TABLE,
                                           'counter'     => emptyInt ),

            ),
        'c_lists' => array(
            LISTS_TABLE          => array( 'id'          => LISTS_TABLE ),
            LISTITEMS_TABLE		 => array(),
            
            ),
            
        'c_module_categories' => array(
            MODULECATEGORIES_TABLE=>array( 'id'          => MODULECATEGORIES_TABLE,
                                           'parent'      => MODULECATEGORIES_TABLE,
                                           'item_id'     => parseItemId ),

            ),
        
//        'c_module_settings' => array(
//            ),
        
        'c_module_items' => array(
            MODULEOBJECTS_TABLE  => array( 'id'          => MODULEOBJECTS_TABLE,
                                           'item_id'     => parseItemId ),
            ),
            
        );
        
        
        // add module specific tables
        
        include_once( INCLUDE_DIR . 'internal/class.moduleManager.php' );
        
        $modMan = new Module_Manager();
        
        $modules = $modMan->getModules();
        
        $modDep = array();
        
        foreach ( $modules as $module ) {
            $depItem = $modMan->callFunction( $module[module_key], 'getFieldDependance' );
            
            if ( count( $depItem ) )
                $modDep = array_merge( $modDep, $depItem );
        }
        
        $optionTables['c_module_items'] = $modDep;
        
        
        // these tables are copied in any case
        
        $necessaryTables = array( 
        
            SETTINGS_TABLE       => array( 'id'          => SETTINGS_TABLE, 
                                           'resource_id' => parseResourceId,
                                           //'menu_id'     => MENUS_TABLE,
                                           //'report_id'   => REPORTS_TABLE,
                                           // set here SKINS_TABLE, not parseSkinId
                                           // because we will not add settings if not c_skins
                                           'skin_id'     => SKINS_TABLE ),

            MODULES_TABLE        => array( 'id'          => MODULES_TABLE,
                                           'skin_id'     => SKINS_TABLE ),

            MODULESETTINGS_TABLE => array( 'id'          => MODULESETTINGS_TABLE ),
            
        );
        
        // --------------------
        // now define functions
        // --------------------
        
        // all functions will receive a fetched row as parameter
        
        // update menu items linkage ids
        function parseResourceId( $row ) {
            
            global $db, $site;
            
            switch( $row[resource_type] ) {
                case 'page':
                    //if ( !($id = $db->getOne( 'select id from '.PAGES_TABLE." where page_key='$row[resource_id]' and site_key='$site'") ) )
                    //    $id = $row[resource_id];
                    $value = getUpdatedId( PAGES_TABLE,  $row[resource_id] );
                    break;
                    
                case 'form':
                    $value = getUpdatedId( FORMS_TABLE, $row[resource_id] );
                    break;
                    
                case 'menu':
                    $value = getUpdatedId( MENUS_TABLE, $row[resource_id] );
                    break;
                    
                case 'skin':
                    $value = getUpdatedId( SKINS_TABLE, $row[resource_id] );
                    break;
                    
                case 'report':
                    $value = getUpdatedId( REPORTS_TABLE, $row[resource_id] );
                    break;
                    
                case 'modcat':
                    list( $module_id, $cat_id, $overwrite ) = split( '_', $row[resource_id] );
                    $mod_id = getUpdatedId( MODULES_TABLE, $module_id );
                    $cat_id = getUpdatedId( MODULECATEGORIES_TABLE, $cat_id );
                    
                    $value = $mod_id.'_'.$cat_id.'_'.$overwrite;
                    break;
                    
                default:
                    $value = $row[resource_id];
                    break;
            }
            
            return $value;
        }
        
        // update layer restriction ids
        // there can be page ids, form ids or report ids
        function parseRestrictTo( $row ) {
            
            if ( !$row['restrict_to'] )
            	return '';
            	
            $r = unserialize( $row['restrict_to'] );
            
            $rez = array();
            
            $rez['module'] = $r['module'];
            $rez['cm_tools'] = $r['cm_tools'];
            
            $resources = array( 'page', 'form', 'report' );
            
            foreach( $resources as $idx=>$item ) {
            	
            	if ( is_array( $r[$item] ) && count( $r[$item] ) ) {
            		foreach ( $r[$item] as $key=>$val ) {
			            switch ( $item ) {
			                case 'page':
			                    $value = getUpdatedId( PAGES_TABLE, $key );
			                    break;
			                    
			                case 'form':
			                    $value = getUpdatedId( FORMS_TABLE, $key );
			                    break;
			                    
			                case 'report':
			                    $value = getUpdatedId( REPORTS_TABLE, $key );
			                    break;
			                    
			                default:
			                    $value = $key;
			            }
			            
			            $rez[$item][$value] = $val;
            		}
            	}
            	else {
            		$rez[$item] = $r[$item];
            	}
            }
            
            return serialize( $rez );
        }
        
        
        // update form redirect ids (pages or forms)
        function parseRedirectId( $row ) {

            switch( $row[redirect_type] ) {
                case 'page':
                    $value = getUpdatedId( PAGES_TABLE, $row[redirect_id] );
                    break;
                    
                case 'form':
                    $value = getUpdatedId( FORMS_TABLE, $row[redirect_id] );
                    break;
                    
                default:
                    $value = $row[redirect_id];
                    break;
                
            }
            
            return $value;                        
        }
        
        

/*        function setOwner( $owner ) {
            global $adminUser, $db;
            
            $userID = $db->getOne( 'select id from ' . USERS_TABLE . " where login_id='$adminUser' " );
            
            return $userID;
        }
*/        

        function parseSkinId( $row, $parentData ) {
            
            if ( $parentData['c_skins'] )
                $value = getUpdatedId( SKINS_TABLE, $row[skin_id] );
            else 
                // assign emptyField() except for '' 
                // because it can return different 
                // values depends on type in future ?
                $value = emptyInt( $row );
                
            return $value;
        }

        
        
        function emptyText( $row ) {
            return '';
        }
        
        function emptyInt( $row ) {
            return 0;
        }

        
        // -------------------------------------
        // determine which tables we should copy
        // -------------------------------------
        
        $tables = $necessaryTables;
        foreach ( $parentData as $option => $value ) {
            if ( $value && @count($optionTables[$option]) ) 
                $tables = array_merge( $tables, $optionTables[$option] );
        }
        
        // ----------------------------------
        // we are ready to start copy process
        // ----------------------------------
        
        foreach ( $tables as $table => $dependentFields ) {
            
        	$rows = $this->db->getAll( 'select * from ' . $table . " where site_key='$site'" );
            
            for ( $i=0, $n=count( $rows ); $i<$n; $i++ ) {
            
                foreach ( $dependentFields as $field => $value ) {
                    
                    if ( function_exists( $value ) ) {
                    
                    	if ( $value == 'parseSkinId' )
                        	$rows[$i][$field] = $value( $rows[$i], $parentData );
                        else 
                        	$rows[$i][$field] = $value( $rows[$i] );
                    }
                    else if ( $value == 'setOwner' )
                    	$rows[$i][$field] = $adminUser;
                    else {
                        $rows[$i][$field] = getUpdatedId( $value, $rows[$i][$field] );
                    }
                }
                
                $rows[$i][site_key] = $key;
                
                if ( $table == SETTINGS_TABLE && $rows[$i]['resource_type'] == 'site' )
                	$rows[$i]['resource_id'] = $key;
                
                // skip skins if not copied
                if ( ( $table == SETTINGS_TABLE || $table == STYLES_TABLE ) && !$parentData['c_skins'] && $rows[$i][skin_id] ) 
                    continue;
                    
                // skip global settings if not copied
                if ( !$parentData['c_settings'] && $table == SETTINGS_TABLE && !$rows[$i][menu_id] && !$rows[$i][skin_id] )
                    continue;
                
                // skip menu settings if not copied
                if ( !$parentData['c_menus'] && $table == SETTINGS_TABLE && $rows[$i][menu_id] )
                    continue;

                // create insert query
                
                $insertFields = implode( ', ', array_keys( $rows[$i] ) );
                $insertValues = "'" . implode( "','", array_map( 'addslashes', array_values( $rows[$i] ) ) ) . "'";
                
                $query = 'insert into ' . $table . " ($insertFields) values ($insertValues)";
                
                $this->db->query( $query );
            
            }
        }
        
        // check if we have copied default resource
        // if not, then specify any one
        
        $updateDefault = false;
        $dres = $this->db->getOne( 'select default_resource_type from '. SITES_TABLE." where site_key='$key'" );
        
        if ( $dres == 'page' && !$parentData['c_pages'] ) {
        	$dres = 'page';
        	$did = $newPageID;
        	$updateDefault = true;
        }

        if ( $dres == 'form' && !$parentData['c_forms'] ) {
        	$dres = 'page';
        	$did = $this->db->getOne( 'select min(id) from '.PAGES_TABLE." where site_key='$key'" );
        	$updateDefault = true;
        }
        
        if ( $updateDefault ) {
        	$this->db->query( 'update '. SITES_TABLE." set default_resource_type='$dres', default_resource_id='$did' where site_key='$key'" );
        }
        
        // check if we need to apply any skin to newely created website
        
        if ( $skin_id ) {
        	$skin = new ES_Skin( $this->db, $site );
        	$skin->makeDefault( $skin_id, false, $key );
        }
        
        
        
 
    }

    /**
     * Updates the site key for an existing user site
     */
    function update( $oldSiteKey, $newSiteKey ) {

        // users cannot delete the site defined as the default
        if ( $newSiteKey == $this->default || $oldSiteKey == $this->default )
            return;

        $allConstants = get_defined_constants();

        // update module specific tables
        include_once( INCLUDE_DIR . 'internal/class.moduleManager.php' );
        
        $modMan = new Module_Manager();
        
        $modules = $modMan->getModules();
        
        foreach ( $modules as $module ) {
            
            $modTables = $modMan->callFunction( $module[module_key], 'getTableList' );
            
            foreach ( $modTables as $num => $table ) {
                $this->db->query( 'update ' . $table . " set site_key = '$newSiteKey' where site_key = '$oldSiteKey'" );
            }
        }

        foreach( $allConstants as $key => $val ) {

            if ( strstr( strval( $key ), '_TABLE' )
                && ( strpos( $key, '_TABLE' ) == strlen( $key ) - 6 ) ) {

                $this->db->query( 'update ' . $val. " set site_key = '$newSiteKey' where site_key = '$oldSiteKey'" );
            }
        }
    }

    /**
     * Recursively remove a directory & all contents. This function is deprecated.
     * It has been replaced by $c->clear( $key ) (see Cacher class).
     */
    /*
    function deldir($dir) {
        $current_dir = opendir($dir);
        while($entryname = readdir($current_dir)){
            if(is_dir("$dir/$entryname") and ($entryname != "." and $entryname!="..")){
                deldir("${dir}/${entryname}");
            }
            elseif($entryname != "." and $entryname!=".."){
                unlink("${dir}/${entryname}");
            }
        }
        closedir($current_dir);
        rmdir(${dir});
    }
    */

    /**
     * Remove the site with site_key = $key
     */
    function delete( $key ) {

        // users cannot delete the site defined as the default
        if ( $key == $this->default )
            return;

        $allConstants = get_defined_constants();
        
        // update module specific tables
        include_once( INCLUDE_DIR . 'internal/class.moduleManager.php' );
        
        $modMan = new Module_Manager();
        
        $modules = $modMan->getModules();
        
        foreach ( $modules as $module ) {
            
            $modTables = $modMan->callFunction( $module[module_key], 'getTableList' );
            
            foreach ( $modTables as $num => $table ) {
                $this->db->query( 'delete from ' . $table . " where site_key = '$key'" );
            }
        }

        foreach( $allConstants as $table => $val ) {

            if ( strstr( strval( $table ), '_TABLE' ) && ( strpos( $table, '_TABLE' ) == strlen( $table ) - 6 ) ) {
                $this->db->query( 'delete from ' . $allConstants[$table] . " where site_key = '$key'" );
            }
        }
        
        $this->db->query( 'update ' . USERS_TABLE . " set user_site_key='' where user_site_key='$key'" );

        // remove any cached images for this site

        global $c;
        $c->clear( $key );
    }
}
?>