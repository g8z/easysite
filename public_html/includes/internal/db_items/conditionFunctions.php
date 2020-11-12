<?php


function getColumnType( $columnName ) {
    switch ( $columnName ) {
        
        case 'creation_date':
        case 'date_created':
        case 'last_login':
        case 'date_expires':
            $fieldType = 'date';
            break;
            
        case 'shipping_method':
        case 'payment_method':
        case 'country':
        case 'status':
        case 'group_id':
            $fieldType = 'select';
            break;
            
        default:
            $fieldType = 'text';
            break;
    }
    
    return $fieldType;
}

function prepareConditionData( $data ) {
    
    include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Section.php' );
    
    $fSect = new Form_Section();
    
    for ( $i=0, $n=count( $data ); $i<$n; $i++ ) {
        
        $field = $data[$i][section_id];
        if ( is_numeric( $field ) ) {
            $fSect->load( $data[$i][section_id], array() );
        
            $fieldType = $fSect->fields[field_type];
            preg_match( '/([a-zA-Z]+)_?([0-9])*?/', $fieldType, $matches );
            $fieldType = $matches[1];
    
        }
        else if ( $field ) {
            $fieldType = getColumnType( $field );
        }
        
        if ( $fieldType == 'radio' || $fieldType == 'select' || $fieldType == 'modcat') {
            $data[$i][list_values] = $data[$i][value];
        } else if ( $fieldType == 'date' ) {
            $data[$i][date_value] = $data[$i][value];
        } else if ( $fieldType == 'checkbox' ) {
            $data[$i][condition] = $data[$i][condition] == 'ch' ? 'checked' : 'unchecked';
        }
    
    	$data[$i]['redirect'] = $data[$i]['redirect_type'] . '-' . $data[$i]['redirect_id'];
    
    }
    
    
    array_unshift( $data, array( 'id' => 'NEW' ) );
    
    return $data;
    
}


function getFieldsForForm( $fieldTypes ) {
    
    global $db, $site;
    
    include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Group.php' );
    include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Section.php' );
    include_once( INCLUDE_DIR . 'internal/db_items/class.List_Item.php' );
    
    $fSect = new Form_Section();
    $fGroup = new Form_Group();
    $lItem  = new List_Item();

    $fields = array();
    foreach ( $fieldTypes as $field ) {
    
        $choiceTitles = array();
        $choiceValues = array();
        $choices = array();
    
        preg_match( '/([a-zA-Z]+)_?([0-9]*)/', $field[field_type], $matches );
        $fieldType = $matches[1];
        $fieldId = $matches[2];
        switch ( $fieldType ) {
            
            case 'radio':
                // get all radio groups id and titles as possible choices
                $choices = $fGroup->loadByParentId( $field[id], array( 'id', 'label', ), '', 'value' );
                break;
    
            case 'select':
                // get all select list choices
                $choices = $lItem->loadByParentId( $field[list_data], array( 'label as id', 'label' ), '', 'label' );
                break;
    
            case 'modcat':
    
                $moduleKey = $db->getOne( 'select module_key from ' . MODULES_TABLE ." where site_key='$site' and id='$fieldId'" );
                $add_fields = array( 'site_key', 'module_key' );
                $add_values = array( $site, $moduleKey );
    
                $category = new Category( $db, MODULECATEGORIES_TABLE, $add_fields, $add_values );
    
                $categories = $category->getCategoryArray();
    
    
                foreach( $categories as $id => $label ) {
                    $choices[] = array( 'id'=>$id, 'label'=>$label );
                }
    
                break;
    
        }
    
        if ( $choices ) {
            foreach ( $choices as $i => $choice ) {
                $choiceTitles[] = addslashes( $choice[label] );
                $choiceValues[] = addslashes( $choice[id] );
            }
        }
        $fields[] = array( 'id'=>$field[id], 'values'=>'\''.@implode( '\', \'', $choiceValues ).'\'', 'titles'=>'\''.@implode( '\', \'', $choiceTitles ).'\'' );
    
    }
    
    return $fields;
}

function getCondValue( $id ) {
    
    include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Section.php' );
    
    $fSect = new Form_Section();
    
    // get value depending on the field type
    
    // determine if this is table column name or form field id
    
    $field = $_POST["section_id_$id"];
    
    if ( is_numeric( $field ) ) {
        
        // get the form field type
        
        $fSect->load( $_POST["section_id_$id"], array( 'field_type' ) );
        preg_match( '/([a-zA-Z]+)_?([0-9]*)/', $fSect->fields[field_type], $matches );
        $fieldType = $matches[1];
        $fieldId = $matches[2];
    
    }
    else {
        
        // get the table column type
        
        $fieldType = getColumnType( $field );
        
    }

    switch ( $fieldType ) {
       case 'modcat':
       case 'radio':
       case 'select':
           $value = $_POST["list_values_$id"];
           break;

       case 'date':
           list( $year, $month, $day ) = split( '-', $_POST["date_value_$id"] );
           if ( strlen( $day ) == 1 )
                $day = '0'.$day;
           $value = $year.'-'.$month.'-'.$day;
           break;

       default:
           $value = $_POST["value_$id"];
           break;
    }

    return $value;
}

?>