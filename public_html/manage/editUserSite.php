<?php
if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );


$userID = intval( $_REQUEST['userID'] );

if ( !hasAdminAccess( 'cm_users' ) ) {

    include_once( '../init_bottom.php' );

    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
	$t->display( $templateName );
    exit;
}


include_once( INCLUDE_DIR . 'internal/class.site.php' );
require_once( INCLUDE_DIR . 'internal/editPermissions.php' );

$siteObj = new ES_Site( $db, DEFAULT_SITE );

$userSiteKey = $_POST['user_site_key'];
$currentKey = $_POST['currentKey'];
$currentLoginName = $_POST['currentLoginName'];

if ( $_POST[deleteSite] ) {

    if ( $hasAccess = hasAdminAccess( 'cm_users_edit' ) ) {

        // removing user site

        $userKey = $db->getOne( 'select user_site_key from ' . USERS_TABLE . " where id='$userID'" );

        $siteObj->delete( $userKey );

    }
}

if ( $_POST['formIsSubmitted'] ) {

    if ( hasAdminAccess( 'cm_users_edit' ) || hasAdminAccess( 'cm_users_add' ) ) {

    	$db->query( 'update '. USERS_TABLE." set user_site_key='$userSiteKey' where id='$userID'" );

    	// check to see if this site key already exists for another user

    	// the number of sites which have the desired site key

    	$numExistingSites = $db->getOne( 'select count(id) from ' . SITES_TABLE . " where site_key = '$userSiteKey'" );

    	if ( $numExistingSites > 0 ) {

    		// check to see if the site that has this site key is the same as the that we own

    		$siteAdminId = $db->getOne( 'select admin_id from ' . SITES_TABLE . " where site_key = '$userSiteKey'" );

    		if ( $siteAdminId != $userID ) {
    			$siteExistsError = true;
    			$userSiteKey = $currentKey;
    		}
    	}
    	else {
	    	if ( $userSiteKey == '' && $currentKey != '' ) {
	    		    $siteObj->delete( $currentKey );
	    	}
	    	else if ( trim( $userSiteKey ) ) {
	    		// check to see if this site key has already been taken

	    		if ( $currentKey != $userSiteKey && trim( $currentKey ) ) {
	    			// update the site key for this user
	    			$siteObj->update( $currentKey, $userSiteKey );
	    		}
	    		else if ( $currentKey != $userSiteKey ) {
	    			// create a new website for this user

	    			$parentData = array(
	        			'c_pages'  => $c_pages,
	                    'c_forms'  => $c_forms,
	                    'c_reports'=> $c_reports,
	                    'c_layers' => $c_layers,
	                    'c_menus'  => $c_menus,
	                    'c_settings' => $c_settings,
	                    'c_styles' => $c_styles,
	                    'c_skins'  => $c_skins,
	                    'c_files'  => $c_files,
	                    'c_lists'  => $c_lists,
	                    'c_module_categories' => $c_module_categories,
	                    'c_module_items'      => $c_module_items,
	    			);

	    			// -----------------------
	    			// save site configuration
	    			// -----------------------

				    // get the new permissions for this group

				    $permissions = array();
				    $all = fetchAll( 'cm_tools', array(), 0 );

				    foreach( $all as $idx => $value ) {

				        $key = 'cm_tools_'.$value['id'];
				        $permissions[$key] = $_POST[$key];
				    }

	    			$configuration = array(
	    				'name' => 'Last Created',
	    				'user_id' => $_SESSION['es_auth']['id'],
	    				'parent_data' => serialize( $parentData ),
	    				'skin_id' => $_POST['skin_id'],
	    				'permissions' => serialize( $permissions ),
	    				'site_key' => $site
	    			);

	    			$cfg = new DB_Item( 0, SITECONFIGURATIONS_TABLE );

	    			// update last created configuration

	    			$lu = $cfg->loadCond( array( 'id' ), array( 'name'=>'Last Created' ) );

	    			if ( $lu )
	    				$cfg->updateId( $lu[0]['id'], $configuration );
	    			else
	    				$cfg->create( $configuration );

	    			// now check if we need to save it with different name

	    			if ( $_POST['save_configuration'] && $_POST['configuration_name'] ) {

	    				$configuration['name'] = $_POST['configuration_name'];
		    			$cf = $cfg->loadCond( array( 'id' ), array( 'name'=>$configuration['name'] ) );
		    			if ( $cf )
		    				$cfg->updateId( $cf[0]['id'], $configuration );
		    			else
		    				$cfg->create( $configuration );
	    			}


	    			unset( $cfg );

	    			// create site

	    			$siteObj->create( $userSiteKey, $userID, $parentData, '', $_POST['skin_id'] );

	    		}

	    		// update content management tool permissions for this user-defined site

	            updatePermissions( USER, $userID, $userSiteKey );

	            // insert guest permissions

	            $db->query( 'insert into '. PERMISSIONS_TABLE." (resource_id,resource_type,group_id,site_key) values ('0','page','".GUEST_GROUP."','$userSiteKey')" );
	            $db->query( 'insert into '. PERMISSIONS_TABLE." (resource_id,resource_type,group_id,site_key) values ('0','form','".GUEST_GROUP."','$userSiteKey')" );
	            $db->query( 'insert into '. PERMISSIONS_TABLE." (resource_id,resource_type,group_id,site_key) values ('0','file','".GUEST_GROUP."','$userSiteKey')" );

	    	}
    	}

    	$t->assign( 'siteExistsError', $siteExistsError );

    }
}

$data = $db->getRow( 'select * from '. USERS_TABLE. " where id='$userID'" );

if ( trim( $data[user_site_key] ) != '' ) {
	$basePath = 'http://' . $_SERVER['SERVER_NAME'] . dirname( $_SERVER['PHP_SELF'] );
	$userSiteUrl = stri_replace( '/' . ADMIN_DIR, '/', $basePath );

	$userSiteUrl .= '?site=' . $data[user_site_key];

	$t->assign( 'userSiteUrl', $userSiteUrl );
}


$skin = new ES_Skin( $db, $site );
$skins = $skin->getAll();

$t->assign( 'skins', $skins );

// get list of available configurations

$cfs = $db->getAll( 'select * from '. SITECONFIGURATIONS_TABLE ." where user_id='{$_SESSION['es_auth']['id']}' and site_key='$site'" );

$cValues = array();
$cTitles = array();
$configuraions = array();

foreach( $cfs as $idx=>$cf ) {
	$cValues[] = 'cf' . $cf['id'];
	$cTitles[] = $cf['name'];

	$state = array_merge( unserialize( $cf['parent_data'] ), unserialize( $cf['permissions'] ) );
	$state = array_merge( $state, array( 'skin_id'=>$cf['skin_id'] ) );

	$configurations[$idx] = array(
		'id' => $cf['id'],
		'state' => $state
	);
}

$t->assign( 'cValues', $cValues );
$t->assign( 'cTitles', $cTitles );

$t->assign( 'configurations', $configurations );


$restrictedSections2 = getRestrictedSections( USER, $userID );

$permissions = array(
    'cm_tools'      => fetchAll( 'cm_tools', $restrictedSections2, $userID, USER ),
);

$t->assign( 'resources', $permissions );
$t->assign( 'data', $data );

$t->assign( 'bodyTemplate', 'manage/editUserSite.tpl' );

$session->updateLocation( 'give_user_site', 'Give Website', array( 'userID' ) );

include_once( '../init_bottom.php' );

$t->display( $templateName );

?>