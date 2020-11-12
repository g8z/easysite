<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );

include_once( '../init_bottom.php' );

$t->assign( 'title', 'Special Operators' );

$t->display( 'popupHeader.tpl' );
$t->display( 'manage/message.tpl' );
$t->display( 'popupFooter.tpl' );

?>