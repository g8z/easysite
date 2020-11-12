<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );
    
include_once( INCLUDE_DIR . 'internal/db_items/class.Report_Group.php' );

$rGroup = new Report_Group();

$reportId = intval( $_REQUEST['id'] );

$t->assign( 'reportId', $reportId );

$t->assign( 'bodyTemplate', 'manage/editReportGroups.tpl' );

include_once( '../init_bottom.php' );

$t->display( $templateName );
?>