<?php
class Report_Field extends DB_Item
{
    function Report_Field( $id=0 ) {
        $this->DB_Item( $id, REPORTFIELDS_TABLE );

        // init child classes for cascade deletion
        $this->_parentField['report'] = 'report_id';


    }
}

?>