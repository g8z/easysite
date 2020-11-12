<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );
    
include_once( INCLUDE_DIR . 'internal/db_items/class.Backup.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Auto_Backup.php' );


$id = intval( $_REQUEST['id'] );

$abackup = new Auto_Backup( $db );

if ( $_REQUEST['action'] == 'delete' ) {
	$tb = $abackup->load( $id );
	$abackup->delete( $id );
	header( 'Location: autoBackups.php' );
	exit;
}

list( $backupIds, $backupTitles ) = getMenuArrays( BACKUPS_TABLE, 'id', 'name', "site_key='$site'", 'name' );
array_unshift( $backupIds, 0 );
array_unshift( $backupTitles, '- Select Backup -' );

list( $abackupIds, $abackupTitles ) = getMenuArrays( AUTOBACKUPS_TABLE, 'id', 'name', "id!='$id' and site_key='$site'", 'name' );
array_unshift( $abackupIds, 0 );
array_unshift( $abackupTitles, '- Select Auto-Backup -' );

// if adding the auto-backup and not configured selected - select it
if ( !$_REQUEST['backup_id'] && !$id ) {
	$t->assign( 'backupIds', $backupIds );
	$t->assign( 'backupTitles', $backupTitles );
    $t->assign( 'bodyTemplate', 'manage/autoChooseBackup.tpl' );
}
else
    $t->assign( 'bodyTemplate', 'manage/editauto.tpl' );

if ( $_POST['backupFormSubmitted'] ) {
	
	$fields['name'] = $_POST['name'];
	$fields['backup_id'] = intval( $_POST['backup_id'] );
	$fields['site_key'] = $site;
	
	if ( $_POST['override_id'] ) {
		$abackup->load( $_POST['override_id'] );
		$fields['email'] = $abackup->fields['email'];
		$fields['subject'] = $abackup->fields['subject'];
		$fields['message'] = $abackup->fields['message'];
	}
	else {
		$fields['email'] = $_POST['email'];
		$fields['subject'] = $_POST['subject'];
		$fields['message'] = $_POST['message'];
	}
	
	// save backup
	
	if ( !$id ) {
		$fields['secret_id'] = md5( uniqid( microtime() ) ); 
		$abackup->create( $fields );
		$id = $abackup->fields['id'];
	}
	else {
		$abackup->updateId( $id, $fields );
	}
	
	include_once( FULL_PATH . 'manage/autoBackups.php' );
	exit();
	
}

if ( $id ) {
	$tb = $abackup->load( $id );
}
else {
	$tb['backup_id'] = intval( $_REQUEST['backup_id'] );
}

if ( !hasAdminAccess( 'cm_backup' ) ) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}

$t->assign( 'backup', $tb );
$t->assign( 'backupIds', $backupIds );
$t->assign( 'backupTitles', $backupTitles );
$t->assign( 'abackupIds', $abackupIds );
$t->assign( 'abackupTitles', $abackupTitles );

$session->updateLocation( 'backup_restore', 'System Backup & Restore' );
include_once( '../init_bottom.php' );

$t->display( $templateName );

?>