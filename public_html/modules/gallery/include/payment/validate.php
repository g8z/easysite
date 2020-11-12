<?php

// used when payment system sends responce
// payment system type us stored in 'custom' GET variable

$custom = $_GET['custom'];

switch ( $custom ) {
	case 'twocheckout':
		$payment = new TwoCheckOut();
		break;

	case 'paypal':
		$payment = new PayPal();
	default:
		break;
}

$payment->validateResponce();
?>