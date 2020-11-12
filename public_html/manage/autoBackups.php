<?php

if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );
    
$query = 'select * from '.AUTOBACKUPS_TABLE." where site_key='$site' order by name";
$backups = $db->getAll( $query );

foreach ( $backups as $idx=>$backup ) {
	$backups[$idx]['secret_cron'] = 'http://'.$_SERVER['SERVER_NAME'] . DOC_ROOT . 'cron.php?id='.md5( $backup['id'] . $backup['secret_id'] );
}

$t->assign( 'backups', $backups );

$session->updateLocation( 'backup_restore', 'System Backup & Restore' );
include_once( '../init_bottom.php' );

if ( !hasAdminAccess( 'cm_backup' ) ) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
    $t->assign( 'bodyTemplate', 'manage/autoBackups.tpl' );
}

$t->display( $templateName );

?>