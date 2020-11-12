<?php
class Page_Section extends Ordered_DB_Item
{
    function Page_Section( $id=0 ) {
        $this->DB_Item( $id, SECTIONS_TABLE );

        // init child classes for cascade deletion
        $this->_parentField['page'] = 'page_id';

	$this->_orderField = 'page_id';
    }
}

?>