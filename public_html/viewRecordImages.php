<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( 'init.php' );
    
$submission_id = intval( $_GET['submission_id'] );
$form_id = intval( $_GET['form_id'] );
$report_id = intval( $_GET['report_id'] );

include_once( INCLUDE_DIR . 'internal/db_items/class.Report.php' );

$report = new Report();

$outImages = array();
$images = $db->getAll( 'select sub.id, sub.file_data_path from '.FORMSUBMISSIONS_TABLE.' sub left join '.FORMSECTIONS_TABLE." sec on sec.id=sub.field_id where sub.submission_id='$submission_id' and sub.form_id='$form_id' and sub.site_key='$site' and sec.field_type='image'" );

$cols = 3;
$rows = ceil( count( $images ) / $cols );

for ( $i=0; $i<$rows; $i++ ) {
    for ( $j=0; $j<$cols; $j++ )
        $outImages[$i][$j] = $images[$i*$cols + $j];
}


$t->assign( 'cols', $cols );
$t->assign( 'rows', $rows );

$t->assign( 'images', $outImages );
$report->getSettings( $report_id );
$t->assign( 'reportSettings', $report->settings );

include_once( 'init_bottom.php' );

$t->assign( 'bodyTemplate', 'pages/viewRecordImages.tpl' );
$t->display( $templateName );

?>