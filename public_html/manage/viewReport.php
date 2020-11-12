<?php

if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );

if ( $clear ) {
	// remove all submission data for this report

	$db->query( "delete from " . FORMSUBMISSIONS_TABLE . " where form_id = '$form_id' and site_key = '$site'" );
}

//$formName = $db->getOne( 'select form_title from ' . FORMS_TABLE . " where id = '$form_id' and site_key = '$site'" ) ;
$formName = htmlentities( $db->getOne( 'select value as form_title from '. SETTINGS_TABLE." where resource_type='form' and property='title' and resource_id='$form_id' and site_key='$site'" ) );

$t->assign( 'formName', $formName );
$t->assign( 'title', 'Submission Report for ' . $formName );

$sections = $db->getAll( "select id, field_type, label from " . FORMSECTIONS_TABLE . " where form_id = '$form_id' and site_key = '$site' order by _order" );

$t->assign( 'headers', $sections );

$headers = array();

foreach( $sections as $index => $row ) {
	$headers[$row[id]] = $row[field_type];
}

$data = $db->getAll( "select * from " . FORMSUBMISSIONS_TABLE . " where form_id = '$form_id' and site_key = '$site' order by submission_id desc" );


$dataAsRows = array();

$numSubmissions = 0;

foreach( $data as $index => $row ) {

	if ( $row[submission_id] != $currentSubmissionId ) {

		if ( is_array( $temp ) )
			$dataAsRows [] = $temp;

		$temp = array();

		// populate temp with some 'filler' data

		foreach( $sections as $a => $b ) {
			$temp[ $b[id] ] = '&nbsp;';
		}

		$currentSubmissionId = $row[submission_id];

		$numSubmissions++;
	}


	if ( $row[value] ) {

		if ( $headers[$row[field_id]] == 'date' ) {

			$parts = explode( '-', $row[value] );

			$temp[ $row[field_id] ] = $parts[1] . '/' . $parts[2] . '/' . $parts[0];
		}
		else if ( stristr( $headers[$row[field_id]], 'modcat_' ) ) {
			$temp[ $row[field_id] ] = $db->getOne( 'select title from ' . MODULECATEGORIES_TABLE . " where id = '$row[value]' and site_key = '$site'" );
		}
		else {
			$temp[ $row[field_id] ] = $row[value];
		}
	}
	else if ( $row[blob_value] ) {

		if ( $headers[$row[field_id]] == 'image' ) {
            require_once $t->_get_plugin_filepath('function', 'imgsrc');
            $params = array( 'table'=>FORMSUBMISSIONS_TABLE, 'field'=>'blob_value', 'id'=>$row[id] );
			$temp[ $row[field_id] ] = '<img width=100 src="'.smarty_function_imgsrc($params, $t).'">';
		}
		else {//file
			$temp[ $row[field_id] ] = "<a href=" . DOC_ROOT . "getObject.php?mode=formFile&id=" . $row[id] . '>Download</a>';
		}
	}
}

if ( is_array( $temp ) ) {
	$dataAsRows [] = $temp;
}

$t->assign( 'numSubmissions', $numSubmissions );

$t->assign( 'data', $dataAsRows );

include_once( '../init_bottom.php' );

$t->display( 'popupHeader.tpl' );
$t->display( 'manage/viewReport.tpl' );
$t->display( 'popupFooter.tpl' );

?>