<?php

class Submission extends DB_Item 
{
    
    function Submission( $id=0 ) {
        $this->DB_Item( $id, FORMSUBMISSIONS_TABLE );
        
        // init child classes for cascade deletion
	$this->_parentField['form'] = 'form_id';
	$this->_parentField['form_redirect'] = 'redirect_id';
	$this->_parentField['form_section'] = 'field_id';
    }
}

?>