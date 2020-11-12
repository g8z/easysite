<?php

class Payment
{
	
	var $_type;
	var $_vars;
	var $_url;
	
	var $_totalPrice;
	var $_order_id;
	var $_shipPrice;
	
	function Payment( $totalPrice, $order_id, $shipPrice=0 ) {
		$this->_type='';
		$this->_url='';
		
		$this->_totalPrice = $totalPrice;
		$this->_order_id = $order_id;
		$this->_shipPrice = $shipPrice;
	}
	
	// prepare vars to send
	function _prepareVars() {
	}
	
	function makePayment() {
		
		$this->_prepareVars();
		
		$vars1 = array();
		foreach ($this->_vars as $kk=>$vv)
		{
		    $v = urlencode($vv);
		    $k = urlencode($kk);
		    $vars1[] = "$kk=$vv";
		}
		$vars = join('&', $vars1);
		header("Location: {$this->_url}?$vars"); 
	}
	
	function validateResponce() {
	}
	
	
}

?>