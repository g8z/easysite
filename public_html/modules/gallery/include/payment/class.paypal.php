<?php

require_once dirname(__FILE__).'/../../config.php';

class PayPal extends Payment 
{
	function PayPal( $totalPrice, $order_id, $shipPrice=0 ) {
		$this->Payment( $totalPrice, $order_id, $shipPrice=0 );
		$this->_type = 'paypal';
		$this->_url = 'https://www.paypal.com/cgi-bin/webscr';
	}
	
	function _prepareVars() {
		
		global $gallerySettings, $system, $moduleKey;
		
	    $this->_vars = array(
	        'cmd'         => '_xclick',
	        'business'    => $gallerySettings['paypalAccount'],
	        'return'      => $system->getURL( 'page', $gallerySettings['returnPage']),
	        'notify_url'  => 'http://'.$_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . '/include/payment/validate.php',
	        'item_name'   => str_replace( '{$site}', $system->settings['title'], $gallerySettings['itemName'] ),
	        'cancel_return' => $system->getURL( 'page', $gallerySettings['cancelPage']),
	        'no_note'     => 1,
	        'shipping'    => sprintf( '%.2f', $this->_shipPrice ),
	        'amount'      => sprintf('%.2f', $this->_totalPrice ),
	        'invoice'     => $this->_order_id,
			'tax'		  => $_SESSION['cart']['tax'],
			'currency_code' => $gallerySettings['currency'],
			'custom'		=> $this->_type,
	    );
	}
	
	function validateResponce() {
		
		global $db;
		
		// read post from PayPal system and add 'cmd'
		
		$vars = $_POST;
		$req = 'cmd=_notify-validate';
		
		foreach ( $vars as $k => $v ) {
		    $vars[$k] = $v = stripslashes( $v );
		    $req .= "&" . urlencode( $k ) . "=" . urlencode ( $v );
		}
		
		// assign posted variables to local variables
		// note: additional IPN variables also available -- see IPN documentation
		
		$item_name      = $vars['item_name'];
		$receiver_email = $vars['receiver_email'];
		$item_number    = $vars['item_number'];
		$invoice        = $vars['invoice'];
		$payment_status = $vars['payment_status'];
		$payment_gross  = $vars['payment_gross'];
		$txn_id         = $vars['txn_id'];
		$payer_email    = $vars['payer_email'];
		$custom         = $vars['custom'];
		
		$business = $gallerySettings['paypalAccount'];
		
		// post back to PayPal system to validate
		
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen ($req) . "\r\n\r\n";
		$fp = fsockopen( "www.paypal.com", 80, $errno, $errstr, 30 );
		
		if ( !$fp ) {
			echo "Error establishing connection with PayPal";
			return 0;
		} 
		else {
			fputs( $fp, $header . $req );
		    
			// read responce
			
			$res = '';
		    while ( !feof( $fp ) ) {
		        $res .= fgets( $fp, 1024 );
		    }
		    fclose ($fp);
		
		    if ( preg_match( '/^VERIFIED/m', $res ) != false ) {
		    	
		        // check if txn_id has not been previously processed
		        
				$result = $db->getOne( "select txn_id from ".ORDERS_TABLE. " where txn_id='$txn_id'" );
				if ( $result ) 
					return 0;
		
		        // check that receiver_email is an email address 
		        // in your PayPal account
		        
		        if ( $receiver_email != $business ) {
		            echo "Payment receiver is '$receiver_email' instead of my business: '$business'";
					return 0;
				}
				
		        if ( $payment_status != 'Completed' ) {
		            echo "Payment is not completed, status: '$payment_status'";
					$db->query( 'update '. ORDERS_TABLE." set status='$payment_status' where id='$invoice'" );
					return 0;
				}
				
				// Success
				
				$db->query( 'update '. ORDERS_TABLE." set txn_id='$txn_id', status='$payment_status' where id='$invoice'" );
			}
		}
	}
}
?>