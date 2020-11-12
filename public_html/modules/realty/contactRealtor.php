<?php

include( 'config.php' );

require_once INCLUDE_DIR . 'class.phpmailer.php';

$table = REALTYITEMS_TABLE;
$userTable = USERS_TABLE;
$modCatTable = MODULECATEGORIES_TABLE;

$propertyId = intval( $propertyId );

if ( $propertyId ) {

	// get the information about this property

	$listingInfo = $db->getRow( "select * from $table where id = '$propertyId'" );

	// get the information about this user

	$userInfo = $db->getRow( "select * from $userTable where id = '$listingInfo[user_id]'" );

	$listingInfoString = '';

	$skip = array(
		'site_key', 'property_type'
	);

	$booleanProperties = realty_getOptions();

	foreach( $listingInfo as $field => $value ) {

		if ( in_array( $value, $skip ) )
			continue;

		if ( $booleanProperties[ $field ] && $booleanProperties[ $field ][4] == true ) {
			if ( $value == 1 )
				$value = 'YES';
			else
				$value = 'NO';
		}
		else if ( $field == 'cat_id' ) {
			$value = $db->getOne( "select title from $modCatTable where id = '$value'" );
		}

		$listingInfoString .= str_replace( '_', ' ', strtoupper( $field ) ) . ' => ' . $value . "\n";
	}

	$mail = new PHPMailer();

	$mail->PluginDir = INCLUDE_DIR;

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

	$mail->From = $userEmail;
	$mail->Priority = 3;

	$mail->AddAddress( $userInfo[email] );

	foreach( $_POST as $key => $val ) {

		if ( $key == 'propertyId' )
			continue;

		$userInfoString .= str_replace( '_', ' ', strtoupper( $key ) ) . ' => ' . $val . "\n";
	}

	$message = "Dear Realtor,\n\nA user has requested information regarding a property that you posted.\n\nUSER INFO:\n\n$userInfoString\n\nPROPERTY INFO:\n\n$listingInfoString";

	$mail->Subject = 'Realty Contact Request';
	$mail->Body = $message;
	$mail->WordWrap = 50;

	if ( !$mail->Send() ) {
		$t->assign( 'mailError', true );
	}
}

$t->assign( 'title', 'Contact the Realtor' );

include_once( FULL_PATH . 'init_bottom.php' );

$t->display( 'popupHeader.tpl' );
$t->display( 'modules/realty/contactRealtor.tpl' );
$t->display( 'popupFooter.tpl' );

?>