<?php

class Form extends DB_Item
{
	
	var $settings;
	
    function Form( $id=0 ) {
        $this->DB_Item( $id, FORMS_TABLE );

        // init child classes for cascade deletion
        include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Section.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Redirect.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Submission.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Report.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Filter_Override.php' );
        $this->_childClass[] = new Form_Section();
        $this->_childClass[] = new Form_Redirect();
        $this->_childClass[] = new Submission();
        $this->_childClass[] = new Report();
        $this->_childClass[] = new Filter_Override();
    }
    
    
    function getFieldTitles( $id, $addDummy=1 ) {
        
        $fSect = new Form_Section();
        
        $conds = array( 'form_id'=>$id, 'field_type!='=>'page_section' );
        $fieldTitles = $fSect->loadColumnCond( 'label', $conds, '_order' );
        
        if ( $addDummy ) {
            array_unshift( $fieldTitles, ' - Select Field - ' );
        }
        
        unset( $fSect );
        
        return $fieldTitles;
    }
    
    
    function getFieldIds( $id, $addDummy=1 ) {
        
        $fSect = new Form_Section();
        
        $conds = array( 'form_id'=>$id, 'field_type!='=>'page_section' );
        $fieldIds = $fSect->loadColumnCond( 'id', $conds, '_order' );
        
        if ( $addDummy ) {
            array_unshift( $fieldIds, 0 );
        }
        
        unset( $fSect );
        
        return $fieldIds;
    }
    
    
    function getSettings( $formId ) {
    	
    	$tss = $this->_db->getAll( 'select * from '. SETTINGS_TABLE ." where resource_type='form' and resource_id='$formId'" );
    	
    	$formSettings = array();
    	
    	foreach( $tss as $num=>$ts ) {
    		$formSettings[$ts[property]] = $ts[value];
    	}
    	
    	$this->settings = $formSettings;
    	
    	return $this->settings;
    }
    
    
    function getFormContents( $formID, &$formContents, &$customFields ) {
        
        $fSect = new Form_Section();
        require_once INCLUDE_DIR . 'internal/class.File.php';
        
        // array for test for conditions if needed
        $formContents = array();
        $formData = array();
        $customFields = array();
        
        foreach( $_POST as $key => $value ) {
        
            if ( $key == 'startFormFields' ) {
                $startFormFieldsReached = true;
                
                // assign unchecked value to unchecked checkboxes
                
                $sects = $fSect->loadByParentId( $formID, array( 'field_type', 'id'), 'form' );
                foreach( $sects as $num=>$sect ) {
                    if ( $sect[field_type] == 'checkbox')
                     if ( !$_POST['field_name_'.$sect[id]]  )
                        $formData['field_name_'.$sect[id]] = 'unchecked';
                }
                
                continue;
            }
        
            if ( $key == 'endFormFields' ) {
                break;
            }
            
            if ( $startFormFieldsReached ) {
                
                if ( preg_match( '/^custom_(.+)$/', $key, $matches ) ) {
                    $customFields[$matches[1]] = $value;
                }
                else 
                    $formData[$key] = $value;
            }
        }
        
        foreach( $formData as $key => $value ) {
        
                // skip month and day of date
                // we will get the all on the year
                if ( preg_match( '/([a-zA-Z]*?_)+([0-9]+)_(Month|Day)/', $key, $matches ) )
                    continue;
                    
                preg_match( '/^([a-zA-Z_]*?_)([0-9]+)/', $key, $matches );
                
                if ( $matches[1] == 'case_sen_' )
                	continue;
                	
                $fieldId = $matches[2];
                
                $fSect->load( $fieldId, array( 'field_type' ) );
                $fieldType = $fSect->fields['field_type'];
        
                if ( $fieldType == 'date' ) {
                    $mon   = $_POST["field_name_{$fieldId}_Month"];
                    $day   = $_POST["field_name_{$fieldId}_Day"];
                    $year  = $_POST["field_name_{$fieldId}_Year"];
        
/*        			if ( !$mon )
        				$mon = date('m');
        			if ( !$year )
        				$year = date('Y');
        			if ( !$day )
        				$day = date('d');*/
        
        			if ( strlen( $day ) == 1 )
        				$day = '0' . $day;
        
        			if ( strlen( $mon ) == 1 )
        				$mon = '0' . $mon;
        
                    $value = $year . '-' . $mon . '-' . $day;
        
                }
        
                $formContents[] = array( 'field_id'=> $fieldId, 'value'=>$value, 'field_type'=>$fieldType );
        }
        
        

        foreach ( $_FILES as $key => $file ) {
        
            $isFormSubmitted = true;
        
            $image = new File( $key );
        
            preg_match( '/([a-zA-Z]*?_)+([0-9]+)/', $key, $matches );
            $fieldId = $matches[2];
        
            if ( $image->isUploaded() ) {
                $formContents[] = array( 'field_id'=> $fieldId, 'blob_value'=>$image->getContent(), 'file_data_path' => $image->userName );
		$image->delete();
            }
            
            unset( $image );
        }        
        
        unset( $fSect );
        
        return $formContents;
    }
}

?>