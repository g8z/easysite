<?php

if ( !defined( 'SMARTY_DIR' ) )
    include_once( 'init.php' );
    
$id = intval( $_GET['id'] );

if ( !$user->hasAccess( 'file', $id ) )
	loginError( ACCESS_DENIED );
	
switch ( $_GET['mode'] ) {
    
    case 'uploadedFile':
    
    	$result = $db->query( 'SELECT * FROM ' . FILES_TABLE . " WHERE id = '$id'" );
    
    	$data = $result->fetchRow();
    
    	$newCount = $data[counter] + 1;
    
    	$db->query( 'update ' . FILES_TABLE . " set counter = '$newCount' where id = '$id'" );
        
    	$canAccess = true;
    
        break;
        
    case 'formFile':
    
        $table = FORMSUBMISSIONS_TABLE;
        $field = 'blob_value';
    	$data = $db->getRow( "select $field as file_data, file_data_path from $table where id = '$id'" );
    	$data[download_name] = $data[file_data_path];
    	$canAccess = true;
    
        break;
        
    default:
        $canAccess = false;
        break;
}

if ( $canAccess ) {

    // get the document extension from the path string
    $ext = end( explode( ".", $data[file_data_path] ) );
    //echo $ext;
        
    $mimetypes['html']  = 'text/html';
    $mimetypes['htm']   = 'text/html';
    $mimetypes['asc']   = 'text/plain';
    $mimetypes['txt']   = 'text/plain';
    $mimetypes['jpeg']  = 'image/pjpeg';
    $mimetypes['jpg']   = 'image/pjpeg';
    $mimetypes['jpe']   = 'image/pjpeg';
    $mimetypes['gif']   = 'image/gif';
    $mimetypes['png']   = 'image/png';
    $mimetypes['js']    = 'application/x-javascript';
    $mimetypes['pdf']   = 'application/pdf';
    $mimetypes['ai']    = 'application/postscript';
    $mimetypes['eps']   = 'application/postscript';
    $mimetypes['ps']    = 'application/postscript';
    $mimetypes['doc']   = 'application/msword';
    $mimetypes['xls']   = 'appplication/x-msexcel';
    $mimetypes['hqx']   = 'application/mac-binhex40';
    $mimetypes['tar']   = 'application/octet-stream';
    $mimetypes['bin']   = 'application/octet-stream';
    $mimetypes['uu']    = 'application/octet-stream';
    $mimetypes['exe']   = 'application/octet-stream';
    $mimetypes['rtf']   = 'application/rtf';
    $mimetypes['rar']   = 'application/rar';
    $mimetypes['zip']   = 'application/zip';
    $mimetypes['wav']   = 'audio/x-wav';
    $mimetypes['au']    = 'audio/basic';
    $mimetypes['snd']   = 'audio/basic';
    $mimetypes['mpeg']  = 'video/mpeg';
    $mimetypes['mpg']   = 'video/mpeg';
    $mimetypes['mp3']   = 'video/mpeg';
    $mimetypes['qt']    = 'video/quicktime';
    $mimetypes['mov']   = 'video/quicktime';
    $mimetypes['avi']   = 'video/x-msvideo';
    $mimetypes['wmv']   = 'video/x-msvideo';
    
    // check for errors
    if ( trim( $data[file_data] ) == "" ) {
        $t->assign( 'errorMessage', 'This file has been removed from the system, or is corrupt. Please contact the system administrator regarding this error.' );
    
        $t->assign( 'bodyTemplate', 'pages/generalError.tpl' );
    
   		$t->display( $templateName );
    
        exit;
    }
    
    if ( !$mimetypes[$ext] )
        $mimetypes[$ext] = 'application/octet-stream';  // default mime-type
    
    if ( !$data[download_name] )
        $data[download_name] = 'file';
    
    // replace spaces with underscores in download name
    $data[download_name] = str_replace( ' ', '_', $data[download_name] );
    
    
    if ( $system->getOS() == 'MAC' ) {
        header("Content-Type: application/x-unknown\n");
        header("Content-Disposition: attachment; filename=\"".$data[download_name]."\"\n");
    }
    elseif (getUserBrowser() == 'MSIE') {
        $disposition = (!eregi("\.zip$", $data[download_name]) && $action != 'zip') ? 'attachment' : 'inline';
        header("Content-Disposition: $disposition; filename=\"".$data[download_name]."\"\n");
        header("Content-Type: application/x-ms-download\n");
    }
    elseif (getUserBrowser() == 'OPERA') {
        header("Content-Disposition: attachment; filename=\"".$data[download_name]."\"\n");
        header("Content-Type: application/octetstream\n");
    }
    else {
        header("Content-Disposition: attachment; filename=\"".$data[download_name]."\"\n");
        header("Content-Type: application/octet-stream\n");
    }
    header("Content-Length: ".strlen( $data[file_data] )."\n\n");
    
    /*
    header( 'Content-disposition: filename=' . $data[download_name] . ".$ext" );
    header( 'Content-type: ' . $mimetypes[$ext] );
    header( 'Pragma: no-cache' );
    header( 'Expires: 0' );
    */
    
    $db->disconnect();
    
    print $data[file_data];

}
else {
    
    $t->assign( 'errorMessage', 'This file has been removed from the system, or is corrupt. Please contact the system administrator regarding this error.' );

    $t->assign( 'bodyTemplate', 'pages/generalError.tpl' );

    include_once( 'init_bottom.php' );
    $t->display( $templateName );

    exit;
}

?>