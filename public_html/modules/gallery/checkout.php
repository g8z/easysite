<?php

require_once 'config.php';

if ( $_POST['formIsSubmitted'] ) {
	
	// ----------------
	// store order info
	// ---------------- 
	
	$shipping = $db->getRow( 'select id,name,price,period,p_item from '. SHIPPING_TABLE." where id='$shipping_method'" );
	
	$cart = $_SESSION['cart'];
	
	$db->query( 'insert into '.ORDERS_TABLE." (first_name,last_name,email,phone,country,state,city,address_1,address_2,zip,total_amount,tax,discount,creation_date,shipping_method,payment_method,status,site_key) values ('$first_name','$last_name','$email','$phone','$countries','$states','$city','$address_1','$address_2','$zip','$cart[totalPrice]','$cart[tax]','$cart[discount]',NOW(),'$shipping[id]','$payment_method','Pending','$site')" );
	$order_id = $db->getOne( 'select max(id) from '. ORDERS_TABLE );
	
	// ------------------
	// save cart contents
	// ------------------
	
	$cartContents = getCartContents();
	foreach ( $cartContents as $idx=>$item ) {
		$db->query( 'insert into '. ORDERCONTENTS_TABLE." (order_id, item_id, item_title, item_count, item_price, site_key) values ( '$order_id', '$item[id]', '$item[title]', '$item[count]', '".intval($item['price']/$item['count'])."', '$site' )" );
	}
	
	// -----------
	// send emails
	// -----------
	
	if ( $gallerySettings['emailReceipt'] == 'yes' ) {

		$search = array( 
			'{$site}', 
			'{$order_num}', 
			'{$order_amt}', 
			'{$ship_method}', 
			'{$ship_period}', 
			'{$ship_cost}', 
			'{$first_name}', 
			'{$last_name}', 
			'{$date}' 
		);
		$replace = array( 
			$system->settings['title'], 
			$order_id, 
			galleryPrice( $cart['totalPrice'] ),
			$shipping['name'], 
			$shipping['period'].' '.$shipping['p_item'], 
			galleryPrice( $shipping['price'] ),
			$first_name, 
			$last_name, 
			$system->getDate() 
		);
		
		$mailBody = str_replace( $search, $replace, $gallerySettings['mail_body'] );
		$mailSubject = str_replace( $search, $replace, $gallerySettings['mail_subject'] );
		
	    require_once INCLUDE_DIR . 'class.phpmailer.php';
	    $mail = new PHPMailer();
	
	    $mail->PluginDir = INCLUDE_DIR;

	    switch( $gallerySettings['mail_format'] ) {
	    	
	    	case 'system':
	    		$mailFormat = MAIL_FORMAT;
	    		break;
	    		
	    	case 'html':
	    		$mailFormat = 'html';
	    		break;
	    		
	    	case 'text':
	    	case 'default':
	    		$mailFormat = 'text';
	    		break;
	    	
	    }
	    
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
	
	    $mail->From = $system->settings['admin_email'];
	
	    $mail->FromName = $system->settings['admin_name'];
	
	    $mail->Priority = 3;
	
	    $mail->AddAddress($email);
	    
	    if ( $gallerySettings['mail_cc'] )
	    	$mail->AddCC($gallerySettings['mail_cc']);
	    
	    $mail->Subject = $mailSubject;
	    $mail->Body = $mailBody;
	    $mail->WordWrap = 80;
	    $success = $mail->Send();
	    
	}
	
	// ------------
	// make payment
	// ------------
	
	include_once( dirname(__FILE__) . '/include/payment/class.payment.php' );
	
	switch( $payment_method ) {
		
		case 'twocheckout':
			include_once( dirname(__FILE__) . '/include/payment/class.twocheckout.php' );
			$payment = new TwoCheckOut( $_SESSION['cart']['totalPrice']+$_SESSION['cart']['tax']-$_SESSION['cart']['discount'], $order_id, $shipping[price] );
			break;
		
		case 'paypal':
		default:
		
			include_once( dirname(__FILE__) . '/include/payment/class.paypal.php' );
			$payment = new PayPal( $_SESSION['cart']['totalPrice']+$_SESSION['cart']['tax']-$_SESSION['cart']['discount'], $order_id, $shipping[price] );
			//paypal_payment( $_SESSION['cart']['totalPrice'], $order_id, $shipping[price] );
			
			break;
	}
	
	$payment->makePayment();
	
	$_SESSION['cart'] = '';
	
}
else {
	
	$shipOptions = array();
	$ships = $db->getAll( 'select id, name, price from '. SHIPPING_TABLE." where site_key='$site' order by name" );
	foreach( $ships as $idx=>$ship ) {
		$shipOptions[$ship[id]] = $ship[name].' ('.$ship[price].')';
	}
	
	$t->assign( 'shipOptions', $shipOptions );
	
	$t->assign( 'paymentOptions', array( 'paypal'=>'PayPal', 'twocheckout'=>'2CheckOut' ) );
	
	assignOrderFields();
	
	include_once( FULL_PATH . 'init_bottom.php' );

	$t->assign( 'bodyTemplate', 'modules/gallery/checkout.tpl' );
	$t->display( $templateName );
}


?>