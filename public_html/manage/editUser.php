<?php
if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );


$userID = $_POST['userID'];

if ( !hasAdminAccess( 'cm_users' ) || ( !$userID && !hasAdminAccess( 'cm_users_add' ))) {
    
    include_once( '../init_bottom.php' );

    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
	$t->display( $templateName );
    exit;
}

$hasAccess = true;


$loginExists = false;

$userID = intval( $_REQUEST['userID'] );



if ( $_POST['formIsSubmitted'] ) {

	// add a new user record

	$today = date('Y-m-d');

	if ( $userID == 'NEW' ) {
	    
	    if ( $hasAccess = hasAdminAccess( 'cm_users_add' ) ) {

		if ( $user->exists( $_POST['login_id'] ) ) {
			$loginExists = true;
		}
		else {

			// do not add a user site because the site key is already in use
			//if ( $siteKeyExistsError )
			//	$userSiteKey = $currentKey;//do not update/create new user site

			$lp = ($system->settings['use_md5']=='yes' ? md5($_POST['login_pass']) : $_POST['login_pass'] );
			$db->query( 'insert into ' . USERS_TABLE . " (
				login_id,
				login_pass,
				first_name,
				last_name,
				email,
				url,
				phone,
				address_1,
				address_2,
				group_id,
				comments,
				date_created,
				date_expires,
				site_key,
				user_site_key,
				status,
				company,
				fax,
				city,
				state,
				zip,
				country,
				member_id
				) values (
				'" . $_POST['login_id'] . "',
				'" . $lp . "',
				'" . $_POST['first_name'] . "',
				'" . $_POST['last_name'] . "',
				'" . $_POST['email'] . "',
				'" . $_POST['url'] . "',
				'" . $_POST['phone'] . "',
				'" . $_POST['address_1'] . "',
				'" . $_POST['address_2'] . "',
				'" . $_POST['group_id'] . "',
				'" . $_POST['comments'] . "',
				'" . $today . "',
				'" . $_POST['Date_Year'].'-'.$_POST['Date_Month'].'-'.$_POST['Date_Day'] . "',
				'" . $site . "',
				'" . $userSiteKey . "',
				'" . $_POST['status'] . "',
				'" . $_POST['company'] . "',
				'" . $_POST['fax'] . "',
				'" . $_POST['city'] . "',
				'" . $_POST['state'] . "',
				'" . $_POST['zip'] . "',
				'" . $_POST['country'] . "',
				'" . $_POST['member_id'] . "'
				)" );

			$userID = $db->getOne( 'select max(id) from ' . USERS_TABLE );

			$add = true;

			// if a user_site_key has been specified, then add a new site for this user
		}
		
	    }
	}
	else {
	    
	    if ( $hasAccess = hasAdminAccess( 'cm_users_edit' ) ) {

		if ( $currentLoginName != $_POST['login_id'] && $user->exists( $_POST['login_id'] ) ) {
			$loginExists = true;
		}
		else {

			// do not add a user site because the site key is already in use
			//if ( $siteKeyExistsError )
			//	$userSiteKey = $currentKey;// do not update/create new user site

			// update an existing user record

			// create the expiration date from month/day/year select lists

			$expirationDate =
				$_POST['Date_Year'] . '-' .
				$_POST['Date_Month'] . '-' .
				$_POST['Date_Day'];
				
			if ( $_POST['login_pass'] ) {
				$lp = ( $system->settings['use_md5']=='yes' ? md5($_POST['login_pass']) : $_POST['login_pass'] );
				$login_pass = "login_pass		= '" . $lp . "',";
			}

			$db->query( 'update ' . USERS_TABLE . " set
				login_id 		= '" . $_POST['login_id'] . "',
				$login_pass
				first_name		= '" . $_POST['first_name'] . "',
				last_name		= '" . $_POST['last_name'] . "',
				email			= '" . $_POST['email'] . "',
				url				= '" . $_POST['url'] . "',
				phone			= '" . $_POST['phone'] . "',
				address_1		= '" . $_POST['address_1'] . "',
				address_2		= '" . $_POST['address_2'] . "',
				group_id		= '" . $_POST['group_id'] . "',
				comments		= '" . $_POST['comments'] . "',
				date_expires 	= '" . $expirationDate . "',
				use_expiration	= '" . intval($_POST['use_expiration']) . "',
				site_key		= '" . $site . "',
				status			= '" . $_POST['status'] . "',
				company			= '" . $_POST['company'] . "',
				fax				= '" . $_POST['fax'] . "',
				city			= '" . $_POST['city'] . "',
				zip				= '" . $_POST['zip'] . "',
				country			= '" . $_POST['country'] . "',
				member_id		= '" . $_POST['member_id'] . "'
					where id = '$userID' and site_key = '$site'" );
		}
		
	    }
	}

}
else if ( $_POST['deleteUser'] ) {
    
    if ( $hasAccess = hasAdminAccess( 'cm_users_delete' ) ) {

    	// get the current user site key
    
    	$currentKey = $db->getOne( 'select user_site_key from ' . USERS_TABLE . " where id = '$userID' and site_key = '$site' limit 1" );
    
    	$db->query( 'delete from ' . USERS_TABLE . " where id = '$userID' and site_key = '$site'" );
    	
    	$db->query( 'delete from ' . TEMPSITES_TABLE . " where owner = '$userID'" );
    
    	if ( trim( $currentKey ) ) {
			include_once( INCLUDE_DIR . 'internal/class.site.php' );
    		$siteObj = new ES_Site( $db, DEFAULT_SITE );
    		$siteObj->delete( $currentKey );
    	}
    		
    }
}

$data = $db->getRow( "select * from " . USERS_TABLE . " where site_key = '$site' and id = '$userID'" );


list( $_year, $_month, $_day ) = explode( '-', $data[date_expires] );

if ( sizeof( $data ) > 0 ) {
	if ( $data[url] && !stristr( $data[url], 'http://' ) )
		$data['view_url'] = 'http://' . $data[url];
	else
		$data['view_url'] = $data[url];
}

$t->assign( 'time', (@mktime( 0, 0, 0, $_month, $_day, $_year ) ? @mktime( 0, 0, 0, $_month, $_day, $_year ) : time()) );

if ( sizeof( $data ) == 0 )
	$t->assign( 'type', 'Add' );
else
	$t->assign( 'type', 'Edit' );

// get a list of the available groups by name

$groups = $db->getAll( "select id, name from " . GROUPS_TABLE . " where site_key = '$site' order by name" );

$groupList = array( '' => '- Choose Group -' );

foreach( $groups as $group ) {
	$groupList[$group[id]] = $group[name];
}


$t->assign( 'addUserLink', '[ <a href=editUser.php>Add a User</a> ]' );
$t->assign( 'userReturnLink', '[ <a href=usersAndGroups.php>Edit a Different User</a> ]' );
$t->assign( 'userWebsiteLink', '[ <a href=editUserSite.php?userID='.$userID.'>Give this user a website</a> ]' );
$t->assign( 'addGroupLink', "[ <a href='editGroup.php?groupID=$data[group_id]'>Add/Edit Groups</a> ]" );

$t->assign( 'data', $data );
$t->assign( 'groups', $groupList );
$t->assign( 'loginExistsError', $loginExists );

$t->assign( 'siteTitle', $db->getOne( "select title from " . SITES_TABLE . " where site_key = '$site' limit 1" ) );

$t->assign( 'statusOptions', array(
	'active' 		=> 'Active',
	'pending' 		=> 'Pending',
	'suspended' 	=> 'Suspended',
	'terminated' 	=> 'Terminated'
	) );


$permissions = array(
    'add'    => hasAdminAccess( 'cm_users_add' ),
    'edit'   => hasAdminAccess( 'cm_users_edit' ),
    'delete' => hasAdminAccess( 'cm_users_delete' )
);

$t->assign( 'permissions', $permissions );

if ( !$hasAccess ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', 'manage/editUser.tpl' );
}


if ( $userID )
	$title = "Edit User ({$data['login_id']})";
else
	$title = 'Add User';
	
$session->updateLocation( 'user_manager', $title, array( 'userID' ) );

include_once( '../init_bottom.php' );

$t->display( $templateName );

?>