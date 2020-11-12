<?php

if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );
	
include_once( INCLUDE_DIR . 'internal/emailBlastFunctions.php' );
include_once( INCLUDE_DIR . 'internal/class.File.php' );

if ( $mailFormSubmitted ) {
	// save current message in the session in case we have 
	// posted it to this page
	saveMessage();
}

if ( $attachFormSubmitted ) {
	
	if ( $attachmentRemove ) {
		removeAttachment( $attachmentRemove );
	}
	else {
		
		// make upload
	
		$file = new File( 'attachment' );
		
		if ( $file->isUploaded() ) {
			
			$id = md5 (uniqid (microtime ()));
			
		    $fileName = FULL_PATH . 'temp/mail_attachment_'. $id . '_'. $file->userName;
			$file->saveAs( $fileName );
			
			$_SESSION['email']['attachments'][$id] = array( 
				'name'=>$file->userName, 
				'size'=>sprintf( "%6.2f", $file->getSize() / 1024 ),
				'id'=>$id,
				'realPath'=>$fileName
			 );
			$file->delete();
		}
	
		unset( $file );
		
		// make redirects because of possible reuploading by reloading page (F5)
		
		if ( $submitButton == 'Upload' ) {
			header( 'Location: http://' . $_SERVER['SERVER_NAME'] . DOC_ROOT . 'manage/emailBlast.php' );
		}
		else {
			header( 'Location: http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] );
		}
	}
}


$t->assign( 'attachFormSubmitted', $attachFormSubmitted );

if ( !hasAdminAccess( 'cm_users' ) ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', 'manage/makeAttachment.tpl' );
}

$session->updateLocation( 'make_attachment', 'Make Attachment' );
include_once( '../init_bottom.php' );
$t->display( $templateName );


?>