<?php

if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );
	
include_once( INCLUDE_DIR . 'internal/emailBlastFunctions.php' );

// check if there was attachements left in temp dir
// from previous not finished e-mail cretion

if ( !$_SESSION['email'] ) {
	
	$dir = FULL_PATH . 'temp';
	
	$fd = @opendir($dir);
	$file_array = array();

	while ( ( $part = @readdir($fd)) == true ) {
		if ( $part != "." && $part != ".." ) { 
			if ( preg_match( '/^mail_attachment_(.*)/', $part ) )
				@unlink( $dir . '/' . $part );
		}
	}
	
	// do not continue check for un-deleted
	// attachments during this session
	
	$_SESSION['email']['attachmentsRemoved'] = 1;
}


function replaceUserInfo( $matches ) {
    
	global $user;
	
	if ( $matches[1] == '_type' ) {
	   $resource_type = $matches[2];
	   $resource_id = $matches[4];
	}
	else {
	   $resource_type = $matches[4];
	   $resource_id = $matches[2];
	}

    return $user[$resource_id];

}

$mailSendProcess = 0;

if ( $mailFormSubmitted ) {
	
	saveMessage();
	
	if ( $attachmentRemove && $_SESSION['email']['attachments'] ) {
		
		removeAttachment( $attachmentRemove );
		
	}
	else {
		
		$mailSendProcess = 1;
		
	    $email = $_SESSION['email'];
	
	    // get users to send emails to
	    
	    switch( $email['to'] ) {
	    	
	    	case 'all':
	    		$users = $db->getAll( "select * from " . USERS_TABLE ." where site_key='$site'" );
	    		break;
	    		
	    	case 'mailingList':
	    		$users = loadMailingListUsers( $email['list_id'] );
	    		break;
	    		
	    	case 'selected':
	    		$users = getMailingUsers( $_SESSION['email'] );
	    		break;
	    }
	    
	    if ( @count( $users ) ) {
	    
			// try send mails
	
		    require_once INCLUDE_DIR . 'class.phpmailer.php';
		    $mail = new PHPMailer();
		    
		    if ( MAIL_TYPE == 'smtp' ) {
		        $mail->IsSMTP();
		        $mail->Host = SMTP_HOST;
		        $mail->Port = SMTP_PORT;
		        if ( SMTP_AUTH ) {
		            $mail->SMTPAuth = 1;
		            $mail->Username = SMTP_USER;
		            $mail->Password = SMTP_PASS;
		        }
		    }
		    else if ( MAIL_TYPE == 'sendmail' ) {
		        $mail->IsSendmail();
		        $mail->Sendmail = SM_PATH;
		    }
		    else {
		        $mail->IsMail();
		    }
	
		    if ( MAIL_FORMAT == 'html' ) {
		        $mail->IsHTML(true);
		    }
		    else {
		        $mail->IsHTML(false);
		    }
		    
		    $mail->From = $email['from_email'];
		
		    $mail->FromName = $email['from_name'];
		
		    $mail->Priority = $email['priority'];
		
		    $mail->Subject = $email['subject'];
		    
		    $mail->WordWrap = 50;

	    
	    	// replace variables
	    	$mailBody = $system->replaceInternalVariables( $email['body'] );
		    
		    // add attachments if any
		    if ( $email['attachments'] )
		    foreach( $email['attachments'] as $aid=>$att )
		    	$mail->AddAttachment( $att['realPath'], $att['name'] );
		    
		    $sent = array();
		    	    
		    $mailToSend = new PHPMailer();
		    	    
		    foreach( $users as $idx=>$user ) {
		    	
		    	$mailToSend = $mail;
		    	
		    	$mailToSend->AddAddress( $user['email'] );
		    	
		    	// replace internal variables
		    	
				$body = preg_replace_callback( "/{internal\s+resource(_type|_id)='([^}]*)'\s+resource(_type|_id)='([^}]*)'}/", 'replaceUserInfo', $mailBody );
		    	
			    $mailToSend->Body = $body;
			    
			    //print_r( $mailToSend );
			    
			    $sent[$user['login_id']] = $mailToSend->Send();
			    
		    }
		    
		    unset( $mailToSend );
		    
		    // clear this mail session data
		    removeAllAttachments();
		    $_SESSION['email'] = '';
	    }
	    
	    $t->assign( 'sent', $sent );
	}
}


if ( !$mailSendProcess ) {

	// get mailing lists
	
	$listValues = array( '0' );
	$listTitles = array( '- Select Mailing List -' );
	
	$mls = $db->getAll( 'select * from '. MAILINGLISTS_TABLE. " where site_key='$site'" );
	
	foreach( $mls as $idx=>$item ) {
		$listValues[] = $item['id'];
		$listTitles[] = $item['name'];
	}
	
	$t->assign( 'listValues', $listValues );
	$t->assign( 'listTitles', $listTitles );
	
	$t->assign( 'numSelected', getSelectedCount() );
	$t->assign( 'selectedList', $_POST['list_id'] );
	
	$template = 'manage/emailBlast.tpl';

}
else {
	
	$template = 'manage/afterSend.tpl';
}

if ( !hasAdminAccess( 'cm_users' ) ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', $template );
}

$session->updateLocation( 'mail_composing', 'E-Mail Blast Message' );
include_once( '../init_bottom.php' );
$t->display( $templateName );
	
?>