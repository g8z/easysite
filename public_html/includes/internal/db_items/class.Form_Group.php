<?php
class Form_Group extends DB_Item
{
    function Form_Group( $id=0 ) {
        $this->DB_Item( $id, FORMGROUPS_TABLE );

        // init child classes for cascade deletion
        $this->_parentField['form_section'] = '_group';
    }
}

?>