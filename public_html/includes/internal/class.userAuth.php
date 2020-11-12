<?php

class UserAuth {

	var $db;
	
	var $shares;
	var $id;
	var $group_id;
	
	
	function UserAuth( &$db ) {
		$this->db = $db;
		$this->id = intval( $_SESSION['es_auth']['id'] );
		
		if ( !$_SESSION['es_auth']['group_id'] )
			$_SESSION['es_auth']['group_id'] = GUEST_GROUP;
		
		$this->group_id = intval( $_SESSION['es_auth']['group_id'] );
	}

	/**
	 * For situations where the login form = a user-defined form, this function updates
	 * the "form submission" count for the login form
	 */
	function updateLoginCount() {

		// first, check to see if a user-defined form is being used instead of the default login.php

		global $siteData, $site;

		if ( $siteData[login_form_id] == 0 )
			return;

		$loginFormID = $siteData[login_form_id];

		$submitCounter = $this->db->getOne( 'select counter_submit from ' . FORMS_TABLE . " where id = '$loginFormID' and site_key = '$site'" );

		$submitCounter++;

		$this->db->query( 'update ' . FORMS_TABLE . " set counter_submit = '$submitCounter' where id = '$loginFormID' and site_key = '$site'" );
	}

	/**
	 * Determines if access to a restricted section is being requested.
	 */
	function isRestricted( $vars ) {

		global $site, $db;

		// content management tools are always considered restricted

		if ( stristr( $_SERVER['PHP_SELF'], '/' . ADMIN_DIR . '/' ) ) {
			return true;
		}

		if ( $vars['page_id'] ) {
            $resource_type 	= 'page';
			$resource_id	= $vars['page_id'];
		}
		else if ( $vars['form_id'] ) {
			$resource_type	= 'form';
			$resource_id	= $vars['form_id'];
		}
		else if ( $vars['id'] )	{ // an uploaded object/file
			$resource_type	= 'file';
			$resource_id	= $vars['id'];
		}

		if ( $resource_type && $resource_id ) {

			return !$this->db->getOne( 'select group_id from ' . PERMISSIONS_TABLE . " where resource_id = '$resource_id' and resource_type = '$resource_type' and site_key = '$site'" );
		}
		else if ( $resource_type && $resource_id === '0' ) {
			return !$this->db->getOne( 'select group_id from ' . PERMISSIONS_TABLE . " where resource_id = '0' and resource_type = '$resource_type' and site_key = '$site'" );
		}
		else {
			return false;
		}
	}

	/**
	 * Determines if the user described by $vars has an expired login or not
	 */
	function isExpired() {
	    return $_SESSION['es_auth']['expired'];
	}

	/**
	 * Determine if this group number has access to the section specified by $vars
	 */
	function hasAccess( $resourceType, $resourceId=0 ) {

		global $site, $system;
		
		// check if user is site admin
		if ( $system->siteData['admin_id'] == $this->id )
		    return true;
		    
		// check if no resourceId provided (undefined resource)
		if ( !$resourceId )
			return true;
		    
		// if user is not admin and have no group - does not have access
		//if ( !$group )
		    //return false;

	    $resourceWhere = "resource_type = '$resourceType' and ( resource_id = '$resourceId' or resource_id='0' )";
			
		return $this->db->getOne( 'select 1 from ' . PERMISSIONS_TABLE . "
			where
			$resourceWhere and
			group_id 		= '{$this->group_id}' and
			site_key 		= '$site'
			" );
	}

	/**
	 * Check to see if this user name exists. Returns id value of the user, if exists, 0 if not.
	 */
	function exists( $user ) {

		global $site;	// make this a private variable later

		return $this->db->getOne( 'select id from ' . USERS_TABLE . " where login_id = '$user' and site_key = 'site' limit 1" );
	}

	/**
	 * Attempts to login this user
	 */
	function login( $vars ) {

		global $site, $system;

		$username = $vars['username'];
		$password = ($system->settings['use_md5']=='yes' ? md5($vars['password']) : $vars['password'] );

		$result = $this->db->query( 'select *, to_days( now() ) - to_days( date_expires ) as days_expired from ' . USERS_TABLE . " where login_id = '$username' and login_pass = '$password' and ( site_key = '$site' or user_site_key = '$site' ) limit 1" );
		$user = $result->fetchRow();
		if ( $user ) {

		    // check if the user is expired 
		
    		if ( $user[use_expiration] && $user[days_expired] )
    			$user['expired'] = 1;
    		else
    		    $user['expired'] = 0;
    
    		// determine which content-management tools this user should have access to, if any
    
    		$cmPermissions = $this->db->getAll( 'select * from ' . PERMISSIONS_TABLE . " where user_id = '" . $user[id] . "' and site_key = '$site'" );
    
    		// check for groups permissions, if no user permissions exist
    
    		if ( sizeof( $cmPermissions ) == 0 && $user[group_id] > 0 ) {
    			$cmPermissions = $this->db->getAll( 'select * from ' . PERMISSIONS_TABLE . " where group_id = '" . $user[group_id] . "' and site_key = '$site'" );
    		}
    
    		$arr = array();
    
    		foreach( $cmPermissions as $index => $row ) {
    			$arr[$row[resource_type]] = $site;
    		}
    		
			$_SESSION['es_auth'] = $user;
			$_SESSION['cm_auth'] = $arr;
	
			// if the login form is a user-defined form (rather than the default login.php), this
			// will update the form submission count, thus keeping track of how many times the user has logged in

			//print_r( $_SESSION );

			$this->updateLoginCount();


			// display any polls for this user

			displayPolls( $_SESSION['es_auth']['id'] );
			
			$this->id = $_SESSION['es_auth']['id'];
			$this->group_id = $_SESSION['es_auth']['group_id'];
			
			return 1;
		}
		else
			return 0;	// not found
	}

	function logout() {

	}
	
	
	function isShared( $resource, $id, $permission='view' ) {
		if ( is_array($_SESSION['shares']) && @count($_SESSION['shares']) )
		return $_SESSION['shares'][$resource][$id][$permission];
	}

	function getSharedSiteKey( $resource, $id ) {
		if ( is_array($_SESSION['shares']) && @count($_SESSION['shares']) )
		return $_SESSION['shares'][$resource][$id][site_key];
	}
	
}

?>