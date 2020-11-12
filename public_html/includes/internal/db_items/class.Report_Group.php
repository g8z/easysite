<?php
class Report_Group extends Ordered_DB_Item
{
    function Report_Group( $id=0 ) {
        $this->DB_Item( $id, REPORTGROUPS_TABLE );

        // init child classes for cascade deletion
        $this->_parentField['report'] = 'report_id';
        $this->_parentField['form_section'] = 'field_id';

	$this->_orderField = 'report_id';

    }
}

?>