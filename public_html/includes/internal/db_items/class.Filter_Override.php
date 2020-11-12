<?php

class Filter_Override extends DB_Item
{
    function Filter_Override( $id=0 ) {
        $this->DB_Item( $id, FILTEROVERRIDES_TABLE );

        // init child classes for cascade deletion
        $this->_parentField['form'] = 'form_id';
        $this->_parentField['form_section'] = 'section_id';
        $this->_parentField['report'] = 'report_id';
    }

}

?>