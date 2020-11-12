<?php

class List_Item extends Ordered_DB_Item
{
    function List_Item( $id=0 ) {
        $this->DB_Item( $id, LISTITEMS_TABLE );

        // init child classes for cascade deletion
		$this->_parentField['custom_list'] = 'list_key';
    }
}

?>