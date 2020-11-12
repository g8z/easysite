<?php

require_once dirname(__FILE__).'/../../config.php';

class TwoCheckOut extends Payment 
{
	function TwoCheckOut( $totalPrice, $order_id, $shipPrice=0 ) {
		$this->Payment( $totalPrice, $order_id, $shipPrice=0 );
		$this->_type = 'twocheckout';
		$this->_url = 'https://www2.2checkout.com/2co/buyer/purchase';
	}
	
	function _prepareVars() {
		
		global $gallerySettings, $system, $moduleKey;
		
	    $this->_vars = array(
	        'x_login'    => $gallerySettings['twocheckoutAccount'],
	        'x_receipt_link_url' => 'http://'.$_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . '/include/payment/validate.php',
	        'x_amount'      => sprintf('%.2f', $this->_totalPrice ),
	        'x_invoice_num'     => $this->_order_id,
	        'custom'     => $this->_type,
	    );
	    
	    if ( $gallerySettings['demoMode'] == 'yes' )
	    	$this->_vars['demo'] = 'Y';
	    	
	    if ( $gallerySettings['passUser'] == 'yes' ) {
	    	$this->_vars['x_First_Name'] = $_POST['first_name'];
	    	$this->_vars['x_Last_Name'] = $_POST['last_name'];
	    	$this->_vars['x_Phone'] = $_POST['phone'];
	    	$this->_vars['x_Email'] = $_POST['email'];
	    	$this->_vars['x_Address'] = $_POST['address_1'];
	    	$this->_vars['x_City'] = $_POST['city'];
	    	$this->_vars['x_State'] = $_POST['states'];
	    	$this->_vars['x_Country'] = $_POST['countries'];
	    }
	}
	
	function validateResponce() {
		
		global $db, $gallerySettings, $system;
		
		if ( $_POST['x_login'] != $gallerySettings['twocheckoutAccount'] )
			die( 'Reseiver account do not match' );
			
		if ( !($invoice = $_POST['x_invoice_num']) ) 
			die( 'No Order Number Spesified.' );
			
		$order = $db->getRow( 'select from '. ORDERS_TABLE." where id='$invoice'" );
		
		if ( $order['total_ammount'] != $_POST['x_amount'] )
			die( 'Amounts did not match' );
		
        if ( !( $txn_id = intval($_POST['x_trans_id']) ) )
                die( 'Bad Transaction Id' );
                
        if ( $_POST['x_2checked'] == 'Y' ) {
        	$payment_status = 'completed';
        	$returnPage = $gallerySettings['successPage'];
        }
        else {
        	$payment_status = 'pending';
        	$returnPage = $gallerySettings['errorPage'];
        }
        	
            
        if (strtoupper(md5($gallerySettings['secretWord'].$gallerySettings['twocheckoutAccount'].$_POST['x_trans_id'].$_POST['x_amount']))!=$_POST['x_MD5_Hash'])
            die( "Unable to validate that you have paid, please contact the webmaster" );
				
		// Success
		
		$db->query( 'update '. ORDERS_TABLE." set txn_id='$txn_id', status='$payment_status' where id='$invoice'" );
		
		header( 'Location: '.$system->getURL( 'page', $returnPage) );
	}
}
?>