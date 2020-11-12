<?php
/**
 * Class for managing sessions via DB. Some versions of PHP do not properly store
 * $_SESSION data (for example, 4.1.2 for Windows & RedHat Linux in 4.2.2), so this
 * class is a work-around for those versions.
 */


session_start();

class ES_Session {

	var $db;
	var $sid;
	var $table;

	var $prevLocation;
	var $locationMap; // tree structure of the site locations

	function ES_Session( &$db, $table = SESSIONS_TABLE ) {

		$this->db = $db;
		$this->table = $table;

        $this->prevLocation = $_SESSION['history'][@count($_SESSION['history'])-1];

        // determines the 'level' of all locations of admin panel

        $this->locationMap = array(
        	'cm_index' => array(
        		'menu_manager' => array('menu_settings'=>0,'advanced_menu_manager'=>0),
        		'page_manager'=>0,
        		'form_manager'=> array('form_settings'=>0,'edit_overrides'=>0,'edit_redirects'=>0),
				'file_manager'=>0,
				'layer_manager'=>0,
				'backup_restore'=>0,
				'shares_manager'=>0,
				'skin_manager'=>0,
				'style_manager'=>0,
				'edit_report'=>array(
					'report_settings'=>0,
					'add_report_group'=>0,
					'edit_report_fields'=>0,
					'edit_report_layout'=>0,
					'edit_embedded_reports'=>0,
					'edit_report_record'=>0
				),
				'my_lists'=>array('edit_list'),
				'global_settings'=>array(
					'layout_manager'=>array('layout_item_settings'=>0),
					'site_settings'=>0
				),
				'users_and_groups' => array(
					'group_manager'=>0,
					'user_manager'=>array('give_user_site'=>0),
					'mail_composing'=>array(
						'mailing_list_manager'=>0,
						'users_select'=>0,
						'make_attachment'=>0
					),
					'import_users'=>0,
					'guest_manager'=>0,
				),

				'manage_gallery_categories'=>0,
				'gallery_add_item'=>0,
				'search_items'=>array('gallery_search_results'=>0),
				'gallery_settings'=>0,
				'ecomm_settings_index'=>array(
					'product_attributes'=>0,
					'shipping_options'=>0,
					'order_list'=>array('order_contents'),
				),
				'display_options'=>array('checkout_settings'=>0),

				'manage_realty_index'=>array(
					'realty_categories'=>0,
					'realty_listings'=>0,
					'realty_settings'=>0,
				),
			)
        );
	}

	/**
	 * Creates a new DB session entry if one does not currently exist.
	 */
	function setId( $sid ) {

		if ( !trim( $sid ) )
			return;

		$this->sid = $sid;

		// clean up expired sessions
		$this->db->query( "delete from $this->table where now() - expire_date > " . MAX_SESSION_LENGTH );
	}

	/**
	 * Terminate the session identified by $sid
	 * If we are in a user-defined website, then only remove the user session, but not the site_key param
	 */
	function end( $sid ) {

		// update the saved session data

		$_SESSION['es_auth'] = '';
		$_SESSION['cm_auth'] = '';
		$_SESSION['shares'] = '';
		$_SESSION['history'] = '';

		$this->set( $_SESSION );

	}

	/**
	 * Sets the session data to $data, identified by $this->sid
	 */
	function set( $data ) {
		$data = addslashes( serialize( $data ) );
		$id = $this->db->query( "update $this->table set session_data = '$data' where session_id = '$this->sid'" );
	}

	/**
	 * Retrieves the session data identified by $this->sid
	 */
	function get() {
		$sdata = $this->db->getOne( "select session_data from $this->table where session_id = '$this->sid'" );

		if ( !$sdata ) {

			$tomorrow = date( 'Y-m-d H:i:s', mktime() + MAX_SESSION_LENGTH );

			$this->db->query( 'insert into ' . $this->table .
				" (
					session_id,
					expire_date
				  ) values (
				  	'$this->sid',
				  	'$tomorrow'
				  )" );
		}

		return unserialize( $sdata );
	}


    /**
     * Stores the current URL & $vars in GET in session for 'back link'
     */
    function getLocation( $vars=array() ) {

    	if ( is_array( $vars ) && count( $vars ) ) {

    		$v = array();

    		foreach ( $_GET as $key=>$val ) {
    			if ( in_array( $key, $vars ) )
    				$v[] = $key.'='.$val;
    		}

    		foreach ( $_POST as $key=>$val ) {
    			if ( in_array( $key, $vars ) )
    				$v[] = $key.'='.$val;
    		}

    		$query = implode( '&', $v );
    		if ( $query )
    			$query = '?'.$query;

    	}

   		return 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . $query;
    }


    function updateLocation( $zone, $title, $vars=array(), $url='' ) {

    	global $t;

    	$this->_locationUpdated = true;

    	if ( $url )
    		$currentLocation = $url;
    	else
    		$currentLocation = $this->getLocation( $vars );

    	$newHistory = array();

    	if ( is_array( $_SESSION['history'] ) && count( $_SESSION['history'] ) ) {
	    	foreach( $_SESSION['history'] as $idx=>$location ) {
	    		if ( $location['zone'] != $zone ) {
	    			$newHistory[] = $location;
	    		}
	    		else {
			    	$this->prevLocation = $_SESSION['history'][$idx-1];
	    			break;
	    		}
	    	}

    	}


    	$t->assign( 'currentZone', $zone );

    	$newHistory[] = array( 'zone'=>$zone, 'title'=>$title, 'url'=>$currentLocation );

    	$_SESSION['history'] = $newHistory;

    }


    function getPath() {

    	$out = array();

    	if ( is_array($_SESSION['history']) && @count($_SESSION['history']) ) {
    	$zone = $_SESSION['history'][count($_SESSION['history'])-1]['zone'];
    	$temp = $this->_getPath( $this->locationMap, $zone );
    	$temp[] = $zone;

    	foreach ( $temp as $i=>$zone ) {
    		$found = 0;
    		if ( is_array($_SESSION['history']) && @count( $_SESSION['history'] ) )
	    	foreach ( $_SESSION['history'] as $idx=>$item ) {
	    		if ( $item['zone'] == $zone ) {
	    			$t = $item;
	    			$found = 1;
	    		}
	    	}

	    	if ( !$found && $zone == 'cm_index' ) {
	    		$t = array( 'title'=>'Admin Index', 'url'=>'http://' . $_SERVER['SERVER_NAME'] . DOC_ROOT . 'manage/index.php' );
	    		$found = 1;
	    	}

	    	if ( $found )
	    		$out[] = array( 'url'=>$t['url'], 'title'=>$t['title'] );
    	}
    	}

    	return $out;
    }

    function _getPath( $map, $zone, $parent='' ) {

    	$out = array();

    	foreach ( $map as $parent=>$child ) {

	    	$found = 0;

    		if ( @is_array( $child ) && @in_array( $zone, array_keys($child) ) ) {
    			$out[] = $parent;
    		}
    		elseif ( !is_array( $child ) && $parent == $zone )
    			$out[] = $parent;
    		elseif ( $o = @$this->_getPath( $child, $zone ) ) {
    			$out[] = $parent;
    			$out = array_merge( $out, $o );
    		}
    	}

    	return $out;
    }
}
?>