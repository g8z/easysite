<?php

class ES_Skin {

	var $db;
	var $site;
	var $parentSite;// used for user-specific sites

	function ES_Skin( &$db, $site ) {
		$this->db = $db;
		$this->site = $site;
		$this->parentSite = $site;
	}

	/**
	 * Yields an associative array of all skins in the form -
	 * [id] = [skin name]
	 * 'shareType' = 'owner' or 'all' - if owner, then only get those skins which are shared
	 * by the currently logged-in user. If all, then get all shared skins, including skins
	 * shared by the currently logged-in user, and other users, too.
	 */
	function getAll( $permission='' ) {

		$currentUserId 		= $_SESSION['es_auth']['id'];
		$currentUserGroup 	= $_SESSION['es_auth']['group_id'];

		// we are either (a) the owner of the skin, or (b) we are using a shared skin in
		// a site that was created for us, or (c) we are using a shared skin in the main site

		$shared = getSQLShares( 'skin', $permission );
		$skinList = $this->db->getAll( 'select *, if(id in ('.$shared.'), concat(name, \'[shared]\'), name) as name from ' . SKINS_TABLE . " where (site_key = '{$this->site}' or id in ($shared)) order by name" );

		//print_r( $skinList );

		//echo 'current user group: ' . $currentUserGroup;

/*		$result = array();

		foreach( $skinList as $index => $row ) {

			// if this is a shared skin, then check to ensure that we are in the share group

			if ( $row[shared] == 1 ) {

				// make sure that we belong to a group that has access to this skin

				
				 //Has access if any of the following are true:
				 //1) we are the owner of this skin
				 //2) this skin is shared to everyone (share_groups = 0)
				 //3) we belong to a group that this skin is shared with

				if ( $row[owner] == $currentUserId || $row[share_groups] == 0 || in_array( $currentUserGroup, explode( ',', $row[share_groups] ) ) ) {

					// if I am the owner of this skin, then use '[shared by me]'
					// otherwise, use '[shared by $user]

					//echo $shareType;

					if ( $row[owner] == $currentUserId ) {
						$shareText = '[shared]';
					}
					else if ( $shareType == 'all' ) {

						$ownerInfo = $this->db->getRow( 'select * from ' . USERS_TABLE . " where id = '$row[owner]'" );
						$ownerName = trim( $ownerInfo[first_name] . ' ' . $ownerInfo[last_name] );

						// if there is no first or last name defined for this user, then use the
						// user's login name instead

						if ( !$ownerName )
							$ownerName = $ownerInfo[login_id];

						$shareText = "[shared by $ownerName]";
					}
					else
						continue;// we are not the owner of this skin, and only owners are allowed

					//echo $row[id] . ' is a shared skin';

					$result[ "$row[id]" ] = "$shareText " . $row[name];
				}
			}
			else
				$result[ "$row[id]" ] = $row[name];
		}*/

		$result = array();

		foreach( $skinList as $index => $row ) {
				$result[ "$row[id]" ] = $row[name];
		}


		return $result;
	}

	/**
	 * Similar to the load function, but loads both settings & styles, using the global template object.
	 */
	function loadAll( $skin_id ) {

		global $t;

		$this->load( $skin_id, $t->get_template_vars( 'settings' ), $t, SETTINGS_TABLE );
		$this->load( $skin_id, $t->get_template_vars( 'cssStyles' ), $t, STYLES_TABLE );
	}

	/**
	 * Load & apply the specified skin.
	 */
	function load( $skin_id, $vars, $template = '', $table = SETTINGS_TABLE ) {

		global $c;

		// remove any saved skin color bars from image cache
		$c->_skin = $skin_id;
		//$c->clear( $this->site . '_', '_' . $skin_id . '.jpg' );// prefix,suffix

		// remove the default skin (in case we are making this skin the default)
		// 0 = default skin id always

		//$c->_skin = '0';
		//$c->clear( $this->site . '_', '_0.jpg' );

		/*
		$skinData = $this->db->getAll( "select * from $table where skin_id = '$skin_id' and site_key = '$this->site' and active = '1'" );
		*/

		// do not add site_key restriction because we don't know if this skin was created by
		// us, or shared to us by an administrator

		if ( $table == SETTINGS_TABLE ) {

			$skinData = $this->db->getAll( "select * from $table where resource_type='skin' and resource_id='$skin_id' and param = '1'" );
			
			// apply these settings to the global site settings

			foreach( $skinData as $index => $data ) {
				$vars[ $data[property] ] = $data[value];
			}

			if ( $template == '' ) {
				return $vars;
			}
			else {
				// reassign the settings to this template with new variables
				$template->assign( 'settings', $vars );
                if ( $vars[auto_center] == 'yes' ) {
                    assignCenterVariables( $vars[body_x], $vars[body_w], $template->get_template_vars( 'layerData' ) );
                }
			}
		}
		else {

			$skinData = $this->db->getAll( "select * from $table where skin_id = '$skin_id' and active = '1'" );
			
			if ( $skinData ) {
			
				// apply these settings to the global site settings
				// re-format the $vars array so that the indices correspond to the primary key values (id field)
	
				$vars2 = array();
	
				foreach( $vars as $index => $data ) {
					$vars2["$data[name]"] = $data;
				}
	
				// now override some of these settings with skin data
				foreach( $skinData as $index => $data ) {
					$vars2["$data[name]"] = $data;
				}
	
	
				if ( $template == '' ) {
					return $vars2;
				}
				else {
	
					// reassign the css styles to this template with new variables
					$template->assign( 'cssStyles', $vars2 );
				}
			}
		}
	}

	/**
	 * Permanently remove the specified skin.
	 */
	function remove( $skin_id ) {

/*		// delete items where skin_id = $skin_id in these tables
		$tables = array( SETTINGS_TABLE, STYLES_TABLE );

		foreach( $tables as $table ) {
			$this->db->query( "delete from $table where skin_id = '$skin_id' and site_key = '$this->site'" );
		}*/
		
		$this->db->query( "delete from ".STYLES_TABLE." where skin_id = '$skin_id'" );
		$this->db->query( "delete from ".SETTINGS_TABLE." where skin_id = '$skin_id'" );

		// set references to $skin_id to '0' in these tables
		$tables = array( PAGES_TABLE, MODULES_TABLE );

		foreach( $tables as $table ) {
			//note: default skin = '' or '0'
			$this->db->query( "update $table set skin_id = '' where skin_id = '$skin_id' and site_key = '$this->site'" );
		}
		
		$this->db->query( "update ". SETTINGS_TABLE." set value='' where resource_type='form' and property='skin_id' and value='$skin_id'" );

		// finally, delete the skin reference in the skins table

		$this->db->query( 'delete from ' . SKINS_TABLE . " where id = '$skin_id'" );

		// remove any cached images for this skin from TEMP_DIR

		global $c;

		//echo 'now clearing skin: ' . $skin_id;
		//echo 'now clearing site: ' . $this->site;

		$c->clear( $this->site . '_', '_' . $skin_id . '.jpg' );// prefix,suffix
	}

	/**
	 * Used when we are going to load a skin for a specific user's site (possibly not needed?)
	 */
	function setSite( $site ) {
		$this->site = $site;
	}

	/**
	 * Used when we are going to load a skin for a specific user's site
	 */
	function setParentSite( $site ) {
		$this->parentSite = $site;
	}

	/**
	 *
	 */
	function makeDefault( $skin_id, $curTable = false, $targetSite='' ) {

		global $t, $system, $c;
		
		if ( !$targetSite )
			$targetSite = $this->site;

		// we may choose to load all skin properties, or only settings or styles
		if ( $curTable )
			$tables = array( $curTable );
		else
			$tables = array( SETTINGS_TABLE, STYLES_TABLE );

		if ( !$this->parentSite )
			$this->parentSite = $this->site;

		foreach( $tables as $table ) {

			// get all of the active skins

			// if we are in a user-specific site, then we should copy over
			// the parent site settings into the user's site

			// NOTE: the extra site_key clause might not be necessary, since
			// skin_id is unique

			$data = $this->db->getAll( "select * from $table where skin_id = '$skin_id' and active = '1'" );

			//print sizeof( $data );

			if ( $table == SETTINGS_TABLE )
				$field = 'property';
			else if ( $table == STYLES_TABLE )
				$field = 'name';

			foreach( $data as $index => $row ) {

				$settings = array();
				$value = $row[$field];

				foreach( $row as $a => $b ) {

					if ( $a == 'skin_id' )
						$b = '';

					if ( $a == 'site_key' && $b != $this->site )
						$b = $this->site;

					if ( $a != 'id' && $a != 'resource_type' && $a != 'resource_id' )
						$settings[$a] = addslashes( $b );
				}
				
				if ( $table == SETTINGS_TABLE ) {
					$settings['resource_type'] = 'layout_item';
					//$settings['resource_id'] = $targetSite;
				}
				
				$settings['site_key'] = $targetSite;

				//$settingsList = implode( ',', $settings );
				
				// determine if current skin item exists
				
				$exists = $this->db->getOne( "select id from $table where $field = '$value' and site_key = '$targetSite' and skin_id < 1" );
					
				$settingsList = array();
				
				if ( $exists ) {
					
					// update existing item
					
					foreach( $settings as $key=>$val ) {
						$settingsList[] = $key . "='" . $val . "'";
					}
					
					$settingsList = implode( ',', $settingsList );
					
					if ( $table == SKINS_TABLE )
						$query = "update $table set $settingsList where $field = '$value' and site_key = '$targetSite'";
					elseif( $table == SETTINGS_TABLE )
						$query = "update $table set $settingsList where $field = '$value' and resource_type='layout' and resource_id='$settings[resource_id]' and site_key = '$targetSite'";
						
					$query .= " and skin_id < 1";
						
					$this->db->query( $query );
					
				}
				else {
					
					// add item
					$names = '(' . implode( ', ', array_keys( $settings ) ) . ')';
					$values = "('" . implode( "', '", array_values( $settings ) ) . "')";
					
					$this->db->query( "insert into $table $names values $values" );
					
				}
				
    			if ( $table == SETTINGS_TABLE ) {
    				
                    $id = $this->db->getOne( 'select id from ' . SETTINGS_TABLE . " where property = '$value' and site_key = '$targetSite' and skin_id=0 limit 1" );
                    
    			    // remove cached images
    				$c->_table = SETTINGS_TABLE;
        			$c->_id = $id;
        			$c->_field = 'value';
        			$c->remove();
    			}
			}

			// load this new default skin into memory so that we can immediately see the result
			// default skin has skin_id = 0 or skin_id = ''
			
			if ( $table == SETTINGS_TABLE ) {
				//$this->load( $skin_id, $t->get_template_vars( 'settings' ), $t, SETTINGS_TABLE );
    			$system->getSettings();

			}
			else if ( $table == STYLES_TABLE ) {
				//$this->load( $skin_id, $t->get_template_vars( 'cssStyles' ), $t, STYLES_TABLE );
				$system->getStyles();
			}
		}
	}
}

?>