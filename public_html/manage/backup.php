<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );
    
include_once( INCLUDE_DIR . 'internal/db_items/class.Backup.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Auto_Backup.php' );

function backupData( $id=0, $compression='' ) {
	
    global $db, $t, $site, $system, $session;

	$backup = new Backup();
    
    if ( !$id ) {
    	// make a full backup
		$databases[DB_NAME] = array(
			'mode' => 'full',
			'tables' => array(), // list tables here if mode != 'full'
		);
	
		list( $filename, $mime_type, $data ) = $backup->backupDatabases( $databases, $compression, ALLOW_FULL_BACKUP );     	
    }
    else {
    	
    	list( $filename, $mime_type, $data ) = $backup->backupId( $id );
    }
    
    unset( $backup );

	if ( !$mime_type ) {
	    $userBrowser = getUserBrowser();
	
	    if ( $system->getOS() == "MAC" ) {
	        $mime_type = "application/x-unknown\n";
	    }
	    elseif ( $userBrowser == "MSIE") {
	         $mime_type = "application/x-ms-download\n";
	    }
	    elseif ( $userBrowser == "OPERA") {
	         $mime_type="application/octetstream\n";
	    }
	    else {
	         $mime_type="application/octet-stream\n";
	    }
	    
	    $filename .= '.sql';
	}
	
	header("Content-Type:". $mime_type);
	header("Content-Disposition:attachment; filename=\"".$filename."\"\n" );
	echo $data;
	
	unset ($data); 

}

function restoreData() {
	
    global $db, $_FILES, $t, $site, $system, $session;
    
	// remove all cached info
    $system->clearTemp();

    $fileName = $_FILES['restoreFile']['tmp_name'];

    // Weither backup was successful
    $success = 1;

    if ( !$fd = @fopen ($fileName, "r") ) {
        $success = 2;
    }
    else {
        $data = @fread ( $fd, filesize ( $fileName ) );
        @fclose ($fd);
    }

    @set_time_limit(300);

    $tables = array();

    //$moduleTables = getModuleTables();
    $modTables = array();

    if ( !empty( $data ) ) {

        $queries = splitSql( $data );

        foreach ( $queries as $sql ) {
        	
            $sql = trim( $sql );

            preg_match( '/INSERT INTO `?([^`]*?)`? \(\s*(.*?)\s*\)\s+VALUES\s+\(\s*(.*?)\s*\)/i', $sql, $parts );
            	//continue;
            	
            $fields = explode( ', ', $parts[2] );
            if ( $isModule = in_array( 'module_key', $fields ) ) {
                $values = split( '\', \'', $parts[3] );
                $moduleKey = trim( $values[array_search( 'module_key', $fields )], "'" );
                
                if ( !is_array( $modTables[$moduleKey] ) ) 
                    $modTables[$moduleKey] = array();
                    
            }
            
            if ( ( !$isModule && !in_array( $parts[1], $tables ) ) || ( $isModule && !in_array( $parts[1], $modTables[$moduleKey] ) ) ) {

                if ( $isModule ) {

                    $modTables[$moduleKey][] = $parts[1];
                    $db->query( "delete from `$parts[1]` where site_key = '$site' and module_key='$moduleKey'" );
                
                } else {

                    $tables [] = $parts[1];
                    if ( in_array( 'site_key', $fields ) )
                    	$w = "site_key = '$site'";
                    else
                    	$w = '1';
                    $db->query( "delete from `$parts[1]` where $w" );

                }
            }

            if ( !empty( $sql ) and $sql[0] != "#" ) {
                $result = $db->query( $sql );

                if ( DB::isError( $result ) ) {
                	echo $sql;
                    $success = 2;
                }
            }
        }
    }

    // Immediately update
    $t->assign( "success", $success );
    $system->getSettings();
    //$t->assign( 'menus', showMenus(0) );

    showContent( 'restore' );
}



function showContent( $template, $id=0 ) {
	
    global $system, $site, $t, $db, $templateName, $session;
    
    switch( $template ) {
    	
    	case 'backup':
		    $sites = $db->getAll( "SELECT * FROM ". SITES_TABLE );
		
		    foreach ($sites as $index=>$row) {
		        $site_ids[] = $row['site_key'];
		        $site_names[] = $row['title'];
		    }
	    	$t->assign( 'site_ids', $site_ids);
		    $t->assign( 'site_id',$site);
		    $t->assign( 'site_names', $site_names);
		    
		    // get configured
		    
		    $backups = $db->getAll( 'select * from '. BACKUPS_TABLE . " where site_key='$site'" );
		    foreach ( $backups as $idx=>$backup ) {
		    	$backups[$idx]['resources'] = unserialize( $backup['resources'] );
		    }
		    
		    $t->assign( 'backups', $backups );
    		break;
    		
    	case 'configureBackup':
    	
    		$backup = new Backup();
    		$t->assign( 'backup', $backup->load( $id ) );
    		unset( $backup );
    		
		    $modules = $db->getAll( 'select * from ' .MODULES_TABLE ." where site_key='$site'" );
		    $t->assign( 'modules', $modules );
    		break;
    }


    if ( function_exists( 'gzcompress' ) ) {
        $t->assign( 'zip', true );
    }

    if ( function_exists( 'gzencode' ) ) {
        $t->assign( 'gz', true );
    }

    

    $session->updateLocation( 'backup_restore', 'System Backup & Restore' );
    include_once( '../init_bottom.php' );
    
    if ( !hasAdminAccess( 'cm_backup' ) ) {
        $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
    }
    else {
        $t->assign( 'bodyTemplate', 'manage/'.$template.'.tpl' );
    }


    $t->display( $templateName );
}


function saveConfiguration( $id ) {
	
	global $db, $site;
	
	$backup = new Backup();
	
	$fields = array( 
		'name' => $_POST['backup_name'],
		'compression' => $_POST['compression'],
		'resources' => $_POST['resources'],
		'site_key' => $site,
	);
	
	if ( !$id ) {
		$id = $backup->create( $fields, $databases );
	}
	else {
		$backup->updateId( $id, $fields, $databases );
	} 
	
	unset( $backup );
}

$action = $_REQUEST['action'];

$id = intval( $_REQUEST['id'] );

switch ( $action )
{
	
    case 'Save Configuration':
    	saveConfiguration( $id );
    	showContent( 'backup' );
    	break;
	
	case 'deleteBackup':
		$backup = new Backup();
		$backup->delete( $id );
		unset( $backup );
		showContent( 'backup' );
		break;
		
    case 'configureScreen':
    	showContent( 'configureBackup', $id  );
    	break;
    	
	case 'restoreScreen':
    	showContent( 'restore' );
    	break;
    	
	case "Restore":
        restoreData();
        break;
        
    case "doBackup":
    case "Download Backup":
        backupData( $id, $compression );
        break;

    default:
        showContent( 'backup' );
        break;
}

?>