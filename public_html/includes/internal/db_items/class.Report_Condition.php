<?php

class Report_Condition extends DB_Item
{
    function Report_Condition( $id=0 ) {
        $this->DB_Item( $id, REPORTCONDITIONS_TABLE );

        // init child classes for cascade deletion
        $this->_parentField['form'] = 'form_id';
        $this->_parentField['form_section'] = 'section_id';
        $this->_parentField['report'] = 'report_id';
    }

}

?>