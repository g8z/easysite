<?php



function editRecordForm( $reportId, $submission_id ) {
    
    global $fSubm, $fSect, $db, $t, $resource, $site;
    
    $sections = $db->getAll( 'select s.*, sub.user_id, sub.redirect_id, sub.value, sub.blob_value, sub.file_data_path, sub.id as unique_id from '.FORMSECTIONS_TABLE.' s left join '.FORMSUBMISSIONS_TABLE." sub on s.id=sub.field_id and sub.submission_id='$submission_id' where s.form_id='$resource' and s.site_key='$site' order by s._order" );
    
    $data = array();
    
    foreach( $sections as $section ) {
        
        $data[] = $fSect->prepareToOutput( $section );
    }
    
    $action = $_SERVER['PHP_SELF']."?action=saveRecord&id=$reportId";
    $t->assign( 'action', $action );
    $t->assign( 'backURL', $_SERVER['PHP_SELF']."?action=viewHtml&id=$reportId" );
    
    $t->assign( 'data', $data );
    
    $t->assign( 'reportId', $reportId );
    $t->assign( 'submission_id', $submission_id );
    $t->assign( 'user_id', $sections[0]['user_id'] );
    $t->assign( 'redirect_id', $sections[0]['redirect_id'] );
    $t->assign( 'formID', $sections[0]['form_id'] );
    
    // assign pagination information
    $t->assign( 'start', $_REQUEST['start'] );
    $t->assign( 'set', $_REQUEST['set'] );
    
    
}


function saveRecord( $submission_id ) {
    
    global $form, $fSubm, $c, $site;
    
    $formID = intval( $_POST['formID'] );
    
    $formContents = array();
    $customFields = array();
    
    $form->getFormContents( $formID, $formContents, $customFields );
    
    $idsToDelete = array();
    
    $c->_table = FORMSUBMISSIONS_TABLE;
    $c->_field = 'blob_value';
    
    foreach( $customFields as $key=>$value ) {
        
        preg_match( '/remove_file_([0-9]+)/', $key, $matches );
        
        $idsToDelete[] = $matches[1];
        
        // remove file or image
        
        $c->_id = $matches[1];
        $c->remove();
        
        $fSubm->deleteCond( array( 'submission_id'=>$submission_id, 'field_id'=>$matches[1] ) );
    }
    
    foreach( $formContents as $num=>$row ) {
        
        $srow = $fSubm->loadCond( array( 'id' ), array( 'submission_id'=>$submission_id, 'field_id'=>$row['field_id'] ) );
            
        if ( !in_array( $row['field_id'], $idsToDelete ) ) {
            
            $fields = array(
                'submission_id' 	=> $submission_id,
                'form_id' 			=> intval( $_POST['formID'] ),
                'redirect_id' 		=> intval( $_POST['redirect_id'] ),
                'field_id' 			=> $row['field_id'],
                'user_id' 			=> intval( $_POST['user_id'] ),
                'value' 			=> $row['value'],
                'blob_value'        => $row['blob_value'],
                'file_data_path'    => $row['file_data_path'],
                'site_key' 			=> $site
            );
            
            if ( $fSubm->exists( $srow[0]['id'] ) ) 
                $fSubm->updateId( $srow[0]['id'], $fields );
            else
                $fSubm->create( $fields );
                
            $c->_id = $srow[0]['id'];
            $c->remove();
        
        }
    }

}


function deleteRecord( $submission_id, $form_id ) {
    
    global $fSubm, $site, $c;
    
    $rows = $fSubm->loadCond( array( 'id' ), array( 'submission_id'=>$submission_id, 'form_id'=>$form_id, 'site_key'=>$site ) );
    
    $c->_table = FORMSUBMISSIONS_TABLE;
    $c->_field = 'blob_value';
    
    foreach( $rows as $num=>$row ) {

        $c->_id = $row['id'];
        $c->remove();
        
    }
    
    $fSubm->deleteCond( array( 'submission_id'=>$submission_id, 'form_id'=>$form_id, 'site_key'=>$site ) );
}


?>