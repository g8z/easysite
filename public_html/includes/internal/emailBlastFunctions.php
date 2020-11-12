<?php


function getMailingWhere( $toArr ) {
	
	// get count of selected users
	
	$groups = "'" . @implode( "', '", $toArr['groups'] ) . "'";
	
	// there is admin with empty status in older vesions
	// that makes some problems 
	
	if ( $toArr['status'] ) {
		$statuses = "'" . $toArr['status'] . "'";
	
		if ( $toArr['status'] == 'active' ) 
			$statuses .= ",''";
	}
	else {
		$statuses = '0';
	}
	
	$users = "'" . @implode( "', '", $toArr['users'] ) . "'";
	
	return "((group_id in ($groups) and status in ($statuses)) or id in ($users))";
	
}

function getSelectedCount() {

	global $db, $site;
	
	$where = getMailingWhere( $_SESSION['email'] );
	
	$numSelected = $db->getOne( 'select count(*) from '.USERS_TABLE. " where $where and site_key='$site'" );
	
	return $numSelected;
}


function getMailingUsers( $toArr ) {
	global $db, $site;
	
	$where = getMailingWhere( $toArr );
	$users = $db->getAll( 'select * from '.USERS_TABLE. " where $where and site_key='$site'" );
	
	return $users;
}

// stores temp message in the session
function saveMessage() {
	$_SESSION['email']['from_email'] = stripslashes($_POST['from_email']);
	$_SESSION['email']['from_name'] = stripslashes($_POST['from_name']);
	$_SESSION['email']['to'] = stripslashes($_POST['to']);
	$_SESSION['email']['list_id'] = stripslashes($_POST['list_id']);
	$_SESSION['email']['subject'] = stripslashes($_POST['subject']);
	$_SESSION['email']['body'] = stripslashes($_POST['body']);
	$_SESSION['email']['priority'] = stripslashes($_POST['priority']);
}


function getMailingListArray( $id ) {
	global $db;
	
	$ml = $db->getAll( 'select * from '. MAILINGTO_TABLE ." where mailing_id='$id'" );
	
	$groups = array();
	$users = array();
	$statuses = array();
	
	if ( $ml )
	foreach ( $ml as $idx=>$item ) {
		
		if ( $item['group_id'] )
			$groups[] = $item['group_id'];
			
		if ( $item['user_id'] )
			$users[] = $item['user_id'];
			
		if ( $item['status'] )
			$status = $item['status'];
	}
	
	$toArr = array(
		'groups' => $groups,
		'users' => $users,
		'status' => $status
	);
	
	return $toArr;

}

function loadMailingListUsers( $id ) {
	
	$toArr = getMailingListArray( $id );
	
	$users = getMailingUsers( $toArr );
	
	return $users;
		
}


function removeAttachment( $id ) {
	
	@unlink( $_SESSION['email']['attachments'][$id]['realPath'] );
	
	$a = array();
	foreach ( $_SESSION['email']['attachments'] as $aid=>$att )
		if ( $aid != $id )
			$a[$aid] = $att;
		
	$_SESSION['email']['attachments'] = $a;
}


function removeAllAttachments() {
	if ( $_SESSION['email']['attachments'] )
	foreach( $_SESSION['email']['attachments'] as $aid=>$att )
		removeAttachment( $aid );
}


function saveMailingList( $name, $source ) {
	
	global $db, $site;
	
	$id = $db->getOne( 'select id from '. MAILINGLISTS_TABLE ." where site_key='$site' and name='$name'" );
	
	if ( $id ) {
		
		$db->query( 'delete from '. MAILINGTO_TABLE ." where mailing_id='$id'" );
		
	}
	else {
		$db->query( 'insert into '. MAILINGLISTS_TABLE ." (name, site_key) values ('$name', '$site')" );
		$id = $db->getOne( 'select max(id) from '. MAILINGLISTS_TABLE );
	}
	
	// generate mailingto content for mailing list
	// and store in DB
	
	$arrays = array( 
		'user_id'  => $source['users'],
		'group_id' => $source['groups'],
	);
	
	$db->query( 'insert into '. MAILINGTO_TABLE . " (mailing_id, status, site_key) values ('$id', '$source[status]', '$site')" );
	
	foreach ( $arrays as $key=>$arr ) {
		if ( is_array( $arr ) && count( $arr ) ) {
			foreach( $arr as $idx=>$val )
				$db->query( 'insert into '. MAILINGTO_TABLE . " (mailing_id, $key, site_key) values ('$id', '$val', '$site')" );
		}
	}
}

?>