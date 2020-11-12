<?php

define( 'HELPER', true );

require_once dirname(__FILE__) . '/../config.php'; 

require_once FULL_PATH . "libs/JSHttpRequest/JSHttpRequest.php";
$JSHttpRequest = new JSHttpRequest("windows-1251");

$name = $_REQUEST['n'];
$value = $_REQUEST['v'];

if ( $value == 'number' ) {
	$output = "<input type=text size=5 name='$name'>";
}
elseif ( $value == 'single-text' || $value == 'multi-text' ) {
	$output = "<input type=text name='$name'>";
}
elseif ( $value == 'date' ) {
	require_once $t->_get_plugin_filepath('function', 'html_select_date');
	$params = array( 'prefix'=>$name.'_' );
	$output = smarty_function_html_select_date($params, $t);
}
elseif ( preg_match( '/^list_(.*)/', $value, $m ) ) {
	require_once $t->_get_plugin_filepath('function', 'list');
	$params = array( 'key'=>$m[1], 'name'=>$name.'[]', 'extra'=>'multiple' );
	$output = smarty_function_list($params, $t);
}

$_RESULT = array(
  "q"   => $q,
  "output" => $output
);

?> 