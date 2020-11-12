<?php
class Form_Section extends Ordered_DB_Item
{
    function Form_Section( $id=0 ) {
        $this->DB_Item( $id, FORMSECTIONS_TABLE );

        // init child classes for cascade deletion
        include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Group.php' );
        //include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Condition.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Redirect.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Submission.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Report_Condition.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Report_Group.php' );
        $this->_childClass[] = new Form_Group();
        $this->_childClass[] = new Form_Redirect();
        $this->_childClass[] = new Submission();
        $this->_childClass[] = new Report_Condition();
        $this->_childClass[] = new Report_Group();
        $this->_parentField['form'] = 'form_id';

	    $this->_orderField = 'form_id';
    }
    
    
    function prepareToOutput( $section ) {
        
        global $site, $system, $db, $t;
        
        $fGroup = new Form_Group();
        
        preg_match( '/([a-zA-Z_]+)([0-9]*)/', $section[field_type], $matches );
        $fieldType = $matches[1];
        $fieldId = $matches[2];
    
        if ( $fieldType == 'textarea' || $fieldType == 'text' ) {
    		list( $section['field_rows'], $section['field_cols'] ) = explode( ',', $section[field_size] );
    
    		// in case we have a 'text' type, but rows,cols for field_size
    
    		if ( trim( $section['field_cols'] ) ) {
    			$section['field_size'] = $section['field_rows'];
    		}
    
    	}
    	else if ( $fieldType == 'radio' ) {
    
    		// get all radio options from FORMGROUPS_TABLE
    
    		$radioList = array();
    
    		//value, label, orientation, selected
    
    		$temp = $fGroup->loadCond( array(), array( '_group'=>$section[id] ), '_order' );
    
    		foreach( $temp as $radioItem ) {
    		    
    		    if ( $section['value'] == $radioItem['value'] )
    		      $radioItem['selected'] = true;
    		      
    			$radioList [] = $radioItem;
    		}
    
    		$section['radio_list'] = $radioList;
    
    	}
    
    	else if ( $fieldType == 'modcat_' ) {
            $moduleKey = $this->_db->getOne( 'select module_key from ' . MODULES_TABLE ." where site_key='$site' and id='$fieldId'" );
            $add_fields = array( 'site_key', 'module_key' );
            $add_values = array( $site, $moduleKey );
    
            $category = new Category( $db, MODULECATEGORIES_TABLE, $add_fields, $add_values );
    
            $categories = $category->getCategoryArray();
    
            $t->assign( 'categories', $categories );
            $section[field_type] = 'modcat';
    	}
    	
    	else if ( $fieldType == 'date' ) {
            list( $year, $month, $day ) = split( '-', $section['value'] );
            $section['year'] = $year;
            $section['month'] = $month;
            $section['day'] = $day;
    	}
    	
    	else if ( $fieldType == 'page_section' ) {
    	    
            $pageSectionData = $this->_db->getAll( "select * from " . SECTIONS_TABLE . " where id = '$section[page_section]' order by _order" );
            
            // massage the data a bit to account for bulleted lists, etc.
            
            $pageSectionData = $system->applyFormat( $pageSectionData );
            
            $section[page_section_data] = $pageSectionData[0];
    
    	}
    	
    	else if ( $fieldType == 'user_groups' ) {
    		list( $groupIds, $groupTitles ) = getMenuArrays( GROUPS_TABLE, 'id', 'name', 1, 'name' );
    		$t->assign( 'groupIds', $groupIds );
    		$t->assign( 'groupTitles', $groupTitles );
    	}
    	
    	unset( $fGroup );
    	
    	return $section;
    }
}

?>