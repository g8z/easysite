<?php

function _insertValue( $matches ) {
    
	global $system, $siteLastUpdate, $adminPath, $generationTime, $numQueries, $t;
	
	if ( $matches[1] == '_type' ) {
	   $resource_type = $matches[2];
	   $resource_id = $matches[4];
	}
	else {
	   $resource_type = $matches[4];
	   $resource_id = $matches[2];
	}
	
	switch ( $resource_type ) {
	    
	    case 'page':
	    case 'form':
	    case 'report':
	    case 'file':
	       $output = $system->getURL( $resource_type, $resource_id );
	       break;
	       
	    case 'variable':
	    
	       switch( $resource_id ) {
	           case 'numvisits':
	               $output = $system->siteData['counter'];
	               break;
	               
	           case 'lastupdate':
	               $output = $siteLastUpdate;
	               break;
	               
	           case 'generation_time':
	               $output = $generationTime;
	               break;
	       }
	       break;
	       
	    case 'user_info':
	       $output = ( $_SESSION['es_auth'][$resource_id] ) ? ( $_SESSION['es_auth'][$resource_id] ) : 'Unknown';
	       break;
	       
	    case 'link':
	       if ( $resource_id = 'admin' )
	           $output = $adminPath;
	       break;
	       
	    case 'timestamp':
	    	$format = ( $resource_id ? $resource_id : $system->settings['php_date'] );
	    	$output = date( $format );
	    	break;
	       
	    default:
	    	$output = $matches[0];
	    	break;
	}

    return $output;

}
    
class System 
{
    
    var $_db;
    var $site;
    var $siteData;
    var $skin_id;
    
    var $page_id;
    var $form_id;
    
    var $userOS;
    
    var $settings;
    var $settingsLastChange;
    var $styles;
    var $stylesLastChange;
    
    var $layerData;
    
    var $crlf;
    
    var $_location;
    
    function System( $db ) {
        
        $this->_db = $db;
        $this->_site = '';
        
    }
    
    // ----------------------
    // determine where are we
    // ----------------------
    function getLocation() {
        
        $dir = dirname( $_SERVER[PHP_SELF] );
        $file = basename( $_SERVER[PHP_SELF] );
        
        //$page_id = $_REQUEST['page_id'];
        //$form_id = $_REQUEST['form_id'];
        
        $page_id = $this->page_id;
        $form_id = $this->form_id;
        $report_id = $this->report_id;
        //$id = $_REQUEST['id'];
        
        if ( ereg( 'manage', $dir ) ) {
        	$this->_location = array( 'resource'=>'cm_tools', 'id'=>0 );
        }
        else if ( ereg( 'modules', $dir ) ) {
        	// change later on what module exactly
        	$this->_location = array( 'resource'=>'module', 'id'=>0 );
        }
        else if ( ereg( 'index.php', $file ) && $page_id ) {
        	$this->_location = array( 'resource'=>'page', 'id'=>$page_id );
        }
        else if ( ereg( 'getForm.php', $file ) && $form_id ) {
        	$this->_location = array( 'resource'=>'form', 'id'=>$form_id );
        }
        else if ( ereg( 'viewReport.php', $file ) && $report_id ) {
        	$this->_location = array( 'resource'=>'report', 'id'=>$report_id );
        }
        else {
        	
        	// we are in the dafult site resource
        	$this->_location = array( 'resource'=>$this->siteData['default_resource_type'], 'id'=>$this->siteData['default_resource_id'] );
        }
        //print_r( $this->_location );
        return $this->_location;
    }
    
    
    /**
     * Check if the resource is visible depending on $this->_location
     */
    function isVisible( $restrict_to, $location='' ) {
    	
    	$r = unserialize( $restrict_to );
    	
    	if ( !$location ) 
    		$location = $this->_location;
    	
    	// is visibility is not set (possile new resource)
    	// then visible anywhere
    	
    	if ( !$r )
    		return 1;
    	
    	$resource = $location['resource'];
    	$id = $location['id'];
    	
    	if ( $resource == 'cm_tools' || $resource == 'module' ) {
    		$rez = $r[$resource];
    	}
    	else if ( $r[$resource] == 'all' ) {
    		// all resources of this type
    		$rez = 1;
    	}
    	else if ( $r[$resource] == 'none' ) {
    		// no display on resource of this type
    		$rez = 0;
    	}
    	else {
    		$rez = $r[$resource][$id];
    	}
    	
    	return $rez;
    }
    
    
    /**
     * Load site data of the $site. 
     * If $site is no provided it is readed from $_SESSION or default site used
     */
    function getSiteData( $site = '' ) {
        
        if ( !$site ) {
            $site = $_SESSION['site'];
        }
        
        if ( $site )
            $where = " site_key = '" . $site . "' limit 1";
        else {
            // load data from default site
            $where = ' is_default = 1 limit 1';
        }
        
		$this->siteData = $this->_db->getRow( 'select * from ' . SITES_TABLE . " where $where" );
		
		if ( !$this->siteData ) {
			
			// check if the site should be created
			
			$siteToCreate = $this->_db->getRow( 'select * from '. TEMPSITES_TABLE ." where user_site_key='$site'" );
			if ( is_array( $siteToCreate ) && count( $siteToCreate ) ) {
				
		        $currentSite = $site;
        
				include_once( INCLUDE_DIR . 'internal/class.site.php' );
				$siteObj = new ES_Site( $this->_db, $this->site );
	        	
    			$parentData = unserialize( $siteToCreate['parent_data'] );
    			$parentSite = $siteToCreate['site_key'];
				$siteObj->create( $siteToCreate['user_site_key'], $siteToCreate['owner'], $parentData, $parentSite );
    			
    			unset( $siteObj );
    			
    			$this->_db->query( 'delete from '. TEMPSITES_TABLE." where id='$siteToCreate[id]'" );

    			$site = $currentSite;
    			
				$this->siteData = $this->_db->getRow( 'select * from ' . SITES_TABLE . " where site_key='$site'" );
    			
			}
		}
		
		$this->site = $this->siteData['site_key'];
		$rez = $this->siteData;
		
		if ( !$this->siteData[id] ) {
			
			$title = '<h2>Site Access Error</h2>';
			$msg = "The site named <b>" . $site . "</b> does not exist. The site may have been removed by the system administrator, or you may have entered the site name incorrectly (site names are case-sensitive).<br /><br /><a href=" . DOC_ROOT . "?site=" . DEFAULT_SITE . ">Click here to view the default site</a>";
			
			$rez = new Error( $title, $msg );
		}
		else {
			$status = $this->_db->getOne( 'select status from '. USERS_TABLE ." where id='{$this->siteData[admin_id]}'" );
			
			if ( $status != 'active' && $status != '' ) {
				$title = '<h2>Site Access Error</h2>';
				$msg = "The site named <b>" . $site . "</b> ia not active. Please try again later.";
				
				$rez = new Error( $title, $msg );
			}
		}
		
        return $rez;
    }
    
    
    /**
     * Checks if there is present the specific template for the site
     * If no, then return the default one
     */
    function getTemplateName() {
        
        global $t;

        if ( $t->template_exists( $this->site.'.tpl' ) )
        	$templateName = $this->site.'.tpl';
        else
            $templateName = 'default.tpl';
            
        return $templateName;
    }
    
    
    /**
     * Checks if the page_id is page_key and finds the id for further use
     */
    function getIdFromKey( $resource_type, $resource_key='' ) {
        
        switch ( $resource_type ) {
        	
        	case 'page':
		    	if ( $resource_key ) {
		            if ( $id = $this->_db->getOne( 'select id from ' . PAGES_TABLE . " where site_key='$this->site' and page_key='$resource_key'" ) )
		                $resource_id = $id;
		            else 
		            	$resource_id = intval( $resource_key );
		            
		            $_GET['page_id'] = $resource_id;
		            $_POST['page_id'] = $resource_id;
		            //$_REQUEST['page_id'] = $resource_id;
		        }
        		break;
        		
        	case 'form':
		    	if ( $resource_key ) {
		            if ( $id = $this->_db->getOne( 'select resource_id from ' . SETTINGS_TABLE . " where site_key='$this->site' and resource_type='form' and property='form_key' and value='$resource_key'" ) )
		                $resource_id = $id;
		            else 
		            	$resource_id = intval( $resource_key );
		            
		            $_GET['form_id'] = $resource_id;
		            $_POST['form_id'] = $resource_id;
		            //$_REQUEST['form_id'] = $resource_id;
		        }
        		break;
        		
        }
        
        return $resource_id;
        
    }
    
    function getIdFromSefTitle( $resource_type, $sef_title ) {
        switch ( $resource_type ) {
        	
        	case 'page':
		    	$id = $this->_db->getOne( 'select id from ' . PAGES_TABLE . " where site_key='$this->site' and sef_title='$sef_title'" );
	            $_GET['page_id'] = $id;
	            $_POST['page_id'] = $id;
        		break;
        		
        	case 'form':
		    	$id = $this->_db->getOne( 'select id from ' . FORMS_TABLE . " where site_key='$this->site' and sef_title='$sef_title'" );
	            $_GET['form_id'] = $id;
	            $_POST['form_id'] = $id;
        		break;
        		
        	case 'report':
		    	$id = $this->_db->getOne( 'select id from ' . REPORTS_TABLE . " where site_key='$this->site' and sef_title='$sef_title'" );
	            $_GET['id'] = $id;
	            $_POST['id'] = $id;
        		break;
        }
        
        return $id;
    }
    
    
    
    /**
     * Determines the skin_id used on the current viewing resource 
     */ 
    function getSkinId( $page_id, $form_id ) {
        
        $skin_id = 0;
        
		if ( !ereg( "manage", dirname( $_SERVER['PHP_SELF'] ) )  && !ereg( 'logout.php', $_SERVER['PHP_SELF'] )  && !ereg( 'modules', dirname( $_SERVER['PHP_SELF'] ) ) ) {
		
			if ( $page_id ) {
	            $skin_id = $this->_db->getOne( 'select skin_id from '. PAGES_TABLE ." where id='$page_id'" );
	        }
	        else if ( $form_id ) {
	            $skin_id = $this->_db->getOne( 'select value from '. SETTINGS_TABLE ." where resource_type='form' and resource_id='$form_id' and property='skin_id'" );
	        }
	
	
	        if ( !$skin_id ) 
	            $skin_id = intval( $this->siteData['skin_id'] );
	            
		}
		
        $this->skin_id = $skin_id;
        
        return $this->skin_id;
    }
    
    
    /**
     * Get global site settings applying skin if present
     */ 
    function getSettings() {
        
        global $t;
        
    	$arr = array();
    	
    	$where = "(site_key = '$this->site' and resource_type='site' and resource_id='$this->site' ) or ( resource_type='skin' and resource_id='$this->skin_id' and param=1 and $this->skin_id > 0 )";
    
    	$settings = $this->_db->getAll( 'select id, property, value, resource_type, resource_id, param from ' . SETTINGS_TABLE . " where $where" );
    	$slc = $this->_db->getOne( 'select max(UNIX_TIMESTAMP(last_change)) from '.SETTINGS_TABLE." where $where" );
    
    	// create an associative array from the settings
    	foreach( $settings as $num=>$row ) {
    	    
    	    // overvride settings with skin values on the fly
    	    
    	    if ( !array_key_exists( $row[property], $arr ) || ( $row['resource_type']=='skin' && $row['param']==1 ) ) {
        		$arr[$row['property']] = $row['value'];
        
        		// check for a few special cases (color bars, background image)
        
        		switch( $row['property'] ) {
        			case 'vt_image':
        				$t->assign( 'vtImageId', $row['id'] );
        				break;
        
        			case 'hz_image':
        				$t->assign( 'hzImageId', $row['id'] );
        				break;
        
        			case 'bg_image':
        				$t->assign( 'bgImageId', $row['id'] );
        				break;

        			case 'footer_bgimage':
        				$t->assign( 'fbgImageId', $row['id'] );
        				break;
    		    }
    	    }
    		
    	}
    	
    	
    	// load site layout
    	
		$where = "((site_key = '$this->site' and skin_id=0) or (skin_id='{$this->skin_id}' and active=1 and $this->skin_id > 0)) and resource_type='layout_item'";
    	$layout = $this->_db->getAll( 'select id, skin_id, property, if (property=\'bgimage\',id,value) as value, resource_type, resource_id, param from ' . SETTINGS_TABLE . " where $where" );
    	$llc = $this->_db->getOne( 'select max(UNIX_TIMESTAMP(last_change)) from '.SETTINGS_TABLE." where $where" );
    	$this->settingsLastChange = max( $slc, $llc );
    	
    	//print_r( $layout );
    	foreach( $layout as $idx=>$item ) {
    	    if ( !isset( $arr[$item['resource_id']][$item['property']] ) || ( $item['skin_id'] ) )
	    		$arr[$item['resource_id']][$item['property']] = $item['value'];

    	}
    	
    	$this->settings = $arr;
    	
    	return $this->settings;

    }
    
    
    /**
     * Get site styles applying skin if present
     */ 
    function getStyles() {
    
    	$shared = getSQLShares( 'style' );
    	$where = "( (site_key = '$this->site' or id in ($shared)) and skin_id=0 ) or ( skin_id = '$this->skin_id' and active=1 and $this->skin_id > 0 )";
    	
    	$styles = $this->_db->getAll( 'select * from ' . STYLES_TABLE . " where $where order by name" );
    	$this->stylesLastChange = $this->_db->getOne( 'select max(UNIX_TIMESTAMP(last_change)) from '.STYLES_TABLE." where $where" );
    	
    	$arr = array();
    	
    	// override styles with skin
    	
    	foreach( $styles as $num=>$style ) {
    	    
    	    if ( !array_key_exists( $style[name], $arr ) || $style['skin_id'] ) {
    	        $arr[$style[name]] = $style;
    	    }
    	}
    	
    	$this->styles = $arr;
    	
    	return $this->styles;
    }
    


    function getOS() {
 
    	global $_SERVER;
    
    	if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        		$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    	}
    	elseif (getenv("HTTP_USER_AGENT")) {
    		$HTTP_USER_AGENT = getenv("HTTP_USER_AGENT");
    	}
    
    	if (eregi("Win", $HTTP_USER_AGENT)) {
    	    $userOS = "WIN";
    	}
    	elseif (eregi("Mac", $HTTP_USER_AGENT)) {
    		$userOS = "MAC";
    	}
    	else {
    		$userOS = "OTHER";
    	}
    	
        $this->userOS = $userOS;
        
    	return $userOS;
    }
    
    
    /**
     * Get end of line depending on system
     */
    function getCrlf() {
        
        $this->crlf = ($this->userOS == 'WIN') ? "\r\n" : (($this->userOS == 'MAC') ? "\r" : "\n");
        
        return $this->crlf;
    }
    
    
    /**
     * Get layers on the current location
     */
    function getAvailableLayers( $page_id, $form_id ) {
    	
        $shared = getSQLShares( 'layer' );
        $allLayers = $this->_db->getAll( 'select *, if(id in ('.$shared.'), concat(title, \'[shared]\'), title) as title from ' . LAYERS_TABLE . " where (site_key = '$this->site' or id in ($shared)) and ( restrict_to = '0' or restrict_to = '' or restrict_to like '%$currentPageOrForm%' or restrict_to='$resource' ) order by id desc" );
        
        if ( is_array( $allLayers ) && count( $allLayers ) ) {
	        foreach ( $allLayers as $idx=>$item ) {
	        	
	        	if ( $this->isVisible( $item['restrict_to'] ) )
	        		$visibleLayers[] = $item;
	        
	        }
        }
        
        $layerData = $this->applyFormat( $visibleLayers );
        
        $this->layerData = $layerData;
        
        return $layerData;

    }
    
    
    
    /**
     * Formats content, inserts internal variables or links
     */ 
    function applyFormat( $data ) {
    	
    	if ( !$data )
    		return;
    
    	// layer settings with indexes = $row[id] values
    
    	// might be faster to simply make an extra DB call in the
    	// case of overriding settings?
    
    	$shared = getSQLShares( 'layer' );
    	$allLayers = $this->_db->getAll( 'select * from '. LAYERS_TABLE." where (site_key='$this->site' or id in ($shared))" );
    	
    	$overrideLayerSettings = array();
    
    	foreach( $allLayers as $index => $layer ) {
    		$overrideLayerSettings[ $layer[id] ] = $layer;
    	}
    
    	foreach( $data as $index => $row ) {
    
    		// if the settings_override option is set, then copy over the settings
    		// from that layer to this one (the one we are overriding)
    
    		if ( $row[settings_override] > 0 ) {
    
    			$settings = $overrideLayerSettings[ $row[settings_override] ];
    
    			$row[_left]		= $settings[_left];
    			$row[top]		= $settings[top];
    			$row[width]		= $settings[width];
    			$row[height]	= $settings[height];
    			$row[align] 	= $settings[align];
    			$row[valign]	= $settings[valign];
    			$row[zorder]	= $settings[zorder];
    			$row[bgcolor]	= $settings[bgcolor];
    			$row[padding]	= $settings[padding];
    			$row[style]		= $settings[style];
    			$row[format]	= $settings[format];
    			$row[anchor] 	= $settings[anchor];
    		}
    
    		if ( $row[format] != '' ) {
    
    			switch( $row[format] ) {
    				case 'bullet':
    					$bullet = '<img src=' . DOC_ROOT . 'images/bullet.gif style="margin-top:4px">';
    					$useCounter = false;
    					break;
    
    				// mini-bullets
    				case 'mbullet':
    					$bullet = '<img src=' . DOC_ROOT . 'images/minibullet.gif style="margin-top:6px">';
    					$useCounter = false;
    					break;
    
    				case 'asterisk':
    					$bullet = '*';
    					$useCounter = false;
    					break;
    
    				case 'arrow':
    					$bullet = '<img src=' . DOC_ROOT . 'images/arrow.gif style="margin-top:4px">';
    					$useCounter = false;
    					break;
    
    				case 'circle':
    					$bullet = '<img src=' . DOC_ROOT . 'images/circle.gif style="margin-top:4px">';
    					$useCounter = false;
    					break;
    
    				case 'dash':
    					$bullet = ' - ';
    					$useCounter = false;
    					break;
    
    				case 'number_1':
    					$bullet = '.';
    					$useCounter = true;
    					break;
    
    				case 'number_2':
    					$bullet = ')';
    					$useCounter = true;
    					break;
    
    				case 'number_3':
    					$bullet = '';
    					$useCounter = true;
    					break;
    			}
    
    			// split based on \n symbol
    
    			$arr = explode( "\n", $row[content] );
    
    			$newContent = '<table width=100% border=0 cellpadding=0 cellspacing=0>';
    
    			if ( $useCounter )
    				$count = 1;
    
    			foreach( $arr as $item ) {
    
    				$item = trim( $item );
    
    				$newContent .= '<tr><td valign="' . $this->settings['visible'][bullet_vert_align] . '" align=right width="' . $this->settings['visible'][bullet_indent] . '"><span class="' . $row[style] . '">';
    
    				if ( $item ) {
    					if ( $useCounter )
    						$newContent .= $count;
    
    					if ( $bullet )
    						$newContent .= $bullet;
    				}
    
    				$newContent .= '</span></td>';
    
    				$newContent .= '<td><img src=' . DOC_ROOT . 'images/spacer.gif width="' . $this->settings['visible'][bullet_horiz_offset] . '" height=1></td>';
    
    				$newContent .= '<td valign=top width=100%><span class="' . $row[style] . '">' . $item . '</span></td></tr>';
    
    				// this row controls the space between bullets
    				$newContent .= '<tr><td colspan=2><img src=' . DOC_ROOT . 'images/spacer.gif width=1 height="' . $this->settings['visible'][bullet_vert_offset] . '"></td></tr>';
    
    				if ( $useCounter && $item )
    					$count++;
    			}
    
    			$newContent .= '</table>';
    			     
    			$row[content] = $newContent;
    			
    		}
    		
    		if ( ($row[nl2br] == 1 ) || ($row[nl2br] != 2 && $this->settings[nl2br]=='yes') ) 
    		      $row[content] = nl2br( $row[content] );
    
  			$row[content] = $this->replaceInternalVariables( $row[content] );
    			
    		$data[$index] = $row;
    	}
    	
    
    	return $data;
    }
    
    function replaceInternalVariables( $data ) {
    	
    	global $siteLastUpdate, $adminPath;
    	
    	// old vesrions compatibility
    	$search = array( "<!--numvisitors-->", "<!--lastupdate-->", "<!--admin-->" );
    	$replace = array( $this->siteData['counter'], $siteLastUpdate, $adminPath );
    	
    	$data = str_replace( $search, $replace, $data );
    	
        $data = preg_replace_callback( "/{internal\s+resource(_type|_id)='([^}]*)'\s+resource(_type|_id)='([^}]*)'}/", '_insertValue', $data );
        
        return $data;
    }
    
    /**
     * Builds URL for $resourceType with $resourceId including sef_urls option
     */
    function getURL( $resourceType, $resourceId, $inTemplate='no' ) {
        
        $sef_urls = $this->settings['sef_urls'] == 'yes';
    
        switch( $resourceType ) {
    
            case 'modcat':
                list( $module_id, $cat_id, $overwrite ) = split( '_', stripslashes( $resourceId ) );
    
                $module = $this->_db->getOne( 'select module_key from '. MODULES_TABLE ." where id='$module_id' " );
    
                if ( $cat_id )
                    if ( $sef_urls ) {
                        //$path = "index_{$cat_id}.html";
                        $sef_title = $this->_db->getOne( 'select sef_title from '.MODULECATEGORIES_TABLE." where id='$cat_id'" );
                        $path = $sef_title.'.html';
                    }
                    else
                        $path = "index.php?category=$cat_id";
                else
                    if ( $sef_urls )
                        $path = 'index.html';
                    else
                        $path = 'index.php';
    
                    $pageUrl =  DOC_ROOT . "modules/$module/$path";
                break;
    
            case 'page':
            
                if ( $sef_urls ) {
                    $sef_title = $this->_db->getOne( 'select sef_title from '.PAGES_TABLE." where id='$resourceId'" );
                    $pageUrl =  DOC_ROOT . 'pages/'.$sef_title .'.html';
                }
                else {
                    $pageUrl =  DOC_ROOT . 'index.php?page_id='. stripslashes( $resourceId );
                }
                
                break;
    
            case 'form':
                if ( $sef_urls ) {
                    $sef_title = $this->_db->getOne( 'select sef_title from '.FORMS_TABLE." where id='$resourceId'" );
                    $pageUrl = DOC_ROOT . 'forms/'.$sef_title.'.html';
                }
                else {
                    $pageUrl = DOC_ROOT . 'getForm.php?form_id='. stripslashes( $resourceId );
                }
                break;
    
            case 'report':
                if ( $sef_urls ) {
                    $sef_title = $this->_db->getOne( 'select sef_title from '.REPORTS_TABLE." where id='$resourceId'" );
                    $pageUrl = DOC_ROOT . 'reports/'.$sef_title.'.html';
                } else {
                    $pageUrl = DOC_ROOT . 'viewReport.php?id='. stripslashes( $resourceId );
                }
                break;
    
    
            case 'file':
                $pageUrl = DOC_ROOT . 'getObject.php?mode=uploadedFile&id='. stripslashes( $resourceId );
                break;
    
            case 'url' :
            case 'frmurl' :
                $url = stripslashes( $resourceId );
    
                if ( validEmail( $url ) )
                    $url = 'mailto:' . $url;
    
                // append http:// if we have only www.
    
                else if ( !preg_match('/^http:\/\//', $url) && !preg_match('/^https:\/\//', $url) )
                    $url = 'http://' . $url;
    
                if ( $resourceType == 'url' ) {
                    if ( $inTemplate == 'yes' )
                        $pageUrl = DOC_ROOT . 'snoopy.php?url=' . $url;
                    else
                        $pageUrl = $url;
                }
                else if ( $resourceType == 'frmurl' )
                    $pageUrl = DOC_ROOT . 'iframe.php?url=' . $url;
                else
                    $pageUrl = $url;
        
                break;
    
            default:
                $pageUrl = '';
            }
            
        if ( !empty( $pageUrl ) && !ereg( '^http', $pageUrl ) ) {
            
            // add hostname to the URL        
            
            $pageUrl = 'http://' . $_SERVER['SERVER_NAME'] . $pageUrl;
        }
    
        return $pageUrl;
    }
    
    

    /**
     * Determines if the $resourceType with $resourceId is currently viewing
     */
    function currentResource( $resourceType, $resourceId ) {
        
        switch ( $resourceType ) {
            
            case 'page':
                $isActive = ( $_GET['page_id'] == $resourceId );
                break;
                
            case 'form':
                $isActive = ( $_GET['form_id'] == $resourceId );
                break;
    
            case 'report':
                $isActive = ereg( "viewReport", $_SERVER['PHP_SELF'] );
                $isActive = $isActive && ( $_GET['id'] == $resourceId );
                break;
    
            case 'modcat':
                
                list( $module_id, $cat_id, $overwrite ) = split( '_', $resourceId );
                $moduleKey = $this->_db->getOne( 'select module_key from ' . MODULES_TABLE . " where id='$module_id' " );
                
                $isActive = ereg( "$moduleKey", dirname( $_SERVER['PHP_SELF'] ) );
                $isActive = $isActive && $_GET['category'] == $cat_id;
                $isActive = $isActive && !ereg( "manage", dirname( $_SERVER['PHP_SELF'] ) );
    
                break;
                
            case 'emburl':
                $isActive = ereg( $resourceId, $_GET['url'] );
                break;
                
            default:
                $isActive = false;
                
        }
        
        return $isActive;
    }
    
    
    function getDate( $timestamp=0 ) {
    	
    	if ( $timestamp == 0 )
    		$timestamp = time();
    		
    	return date( $this->settings['php_date'], $timestamp );
    }
    
    
    function getStyleList( $permission='' ) {
    	
		$shared = getSQLShares( 'style', $permission ); 
		$allStyles = $this->_db->getAll( 'select name from ' . STYLES_TABLE  . " where (site_key = '{$this->site}' or id in ($shared)) and skin_id < 1 order by user_defined, name");
		
		$styleList = array();
		
		// pick out those styles which do not start with '.'
		
		foreach( $allStyles as $index => $row ) {
			$style = $row[name];
		
			if ( substr( $row[name], 0, 1 ) == '.' )
				$styleList [] = substr( $row[name], 1 );
		}
		
		return $styleList;

    }
    
    
    function generalError( $message, $title='' ) {
    	
    	global $t;
    	
    	if ( $title )
    		$t->assign( 'errorTitle', $title );
    		
    	$t->assign( 'errorMessage', $message );
    	
    	$t->assign( 'bodyTemplate', 'pages/generalError.tpl' );
    	
    	include_once( FULL_PATH . 'init_bottom.php' );

		$t->display( $templateName );
		
		exit();
    }
    
    
    // full clear of temp dir (cache delete)
    function clearTemp() {
		$dir = FULL_PATH . TEMP_DIR;
		
		$fd = @opendir($dir);
		$file_array = array();
	
		while ( ( $part = @readdir($fd)) == true ) {
			if ( $part != "." && $part != ".." ) { 
				@unlink( $dir . '/' . $part );
			}
		}
    }
    
    
/*    function getCurrentLocation( $useVars=0, $except=array() ) {
    	
    	global $t;
    	
    	if ( $useVars ) {
    		
    		$vars = array();
    		
    		foreach ( $_GET as $key=>$val ) {
    			if ( !@in_array( $key, $except ) )
    				$vars[] = $key.'='.$val;
    		}
    		
    		foreach ( $_POST as $key=>$val ) {
    			if ( !@in_array( $key, $except ) )
    				$vars[] = $key.'='.$val;
    		}
    		
    		$query = implode( '&', $vars );
    		if ( $query )
    			$query = '?'.$query;
    		
    	}
    	
   		return urlencode( 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . $query );
    }
    */


}
?>