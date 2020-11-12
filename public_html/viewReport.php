<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( 'init.php' );
    
include_once( INCLUDE_DIR . 'internal/db_items/class.Report.php' );
include_once( INCLUDE_DIR . 'internal/db_items/reportFunctions.php' );
    
include_once( INCLUDE_DIR . 'internal/db_items/class.Form.php' );
    
$form   = new Form();
$fSect  = new Form_Section();
$fGroup = new Form_Group();
$fSubm  = new Submission();

$report = new Report();

if ( $_GET['report_sef_title'] ) {
	$id = $system->getIdFromSefTitle( 'report', $_GET['report_sef_title'] );
	$_REQUEST['id'] = $id;
}
$reportId = intval( $_REQUEST['id'] );
$report->load( $reportId );

$t->assign( 'reportSettings', $report->getSettings( $reportId ) );

/*$report->mode = 'html';
$content = $report->generate( $reportId );

$t->assign( 'content', $content );*/

function viewHTMLReport( $reportId ) {
    
    global $t, $report, $system;
    
    $report->mode = 'html';
    $content = $report->generate( $reportId );

    $t->assign( 'content', $content );

}

switch ( $action ) {
    
    case 'editRecord':
        $resource = $report->fields['resource'];
        editRecordForm( $reportId, intval( $_GET['sub_id'] ) );
        $t->assign( 'bodyTemplate', 'pages/editReportRecord.tpl' );
        break;
        
    case 'saveRecord':
        saveRecord( intval( $_POST['submission_id'] ) );
        viewHTMLReport( $reportId );
        $t->assign( 'bodyTemplate', 'pages/reportViewer.tpl' );
        break;
        
    case 'deleteRecord':
        deleteRecord( intval( $_GET['sub_id'] ), intval( $_GET['form_id'] ) );
        viewHTMLReport( $reportId );
        $t->assign( 'bodyTemplate', 'pages/reportViewer.tpl' );
        break;
        
    default:
        viewHTMLReport( $reportId );
        $t->assign( 'bodyTemplate', 'pages/reportViewer.tpl' );
        break;
        
}

include_once( 'init_bottom.php' );

$t->display( $templateName );
  
?>