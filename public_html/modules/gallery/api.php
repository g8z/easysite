<?php

/**
 * Used when new user site is created (includes/internal/class.site.php)
 */
function gallery_getFieldDependance() {
    
    $modDep = array(
        DB_PREFIX.'_gallery_items' => array( 'id' => DB_PREFIX.'_gallery_items',
                                             'man_id' => DB_PREFIX.'_gallery_manufacturers' ),

        DB_PREFIX.'_gallery_display_options' => array( 'id' => DB_PREFIX.'_gallery_display_options',
                                             'field_id' => DB_PREFIX.'_gallery_product_attributes' ),
                                             
        DB_PREFIX.'_gallery_product_attributes' => array( 'id' => DB_PREFIX.'_gallery_product_attributes' ),
                                             
        DB_PREFIX.'_gallery_manufacturers' => array( 'id' => DB_PREFIX.'_gallery_manufacturers' ),
                                             
        DB_PREFIX.'_gallery_orders' => array( 'id' => DB_PREFIX.'_gallery_orders',
                                             'shipping_method' => DB_PREFIX.'_gallery_shipping_options' ),
                                             
        DB_PREFIX.'_gallery_order_contents' => array( 'id' => DB_PREFIX.'_gallery_order_contents',
                                             'order_id' => DB_PREFIX.'_gallery_orders' ),
                                             
        DB_PREFIX.'_gallery_product_values' => array( 'id' => DB_PREFIX.'_gallery_product_values',
                                             'product_id' => DB_PREFIX.'_gallery_items',
                                             'attr_id' => DB_PREFIX.'_gallery_product_attributes' ),
                                             
        DB_PREFIX.'_gallery_shipping_options' => array( 'id' => DB_PREFIX.'_gallery_shipping_options' ),
        
        DB_PREFIX.'_gallery_att_pricing' => array( 'id' => DB_PREFIX.'_gallery_att_pricing', 
                                             'product_id' => DB_PREFIX.'_gallery_items' ),
                                             
        DB_PREFIX.'_gallery_att_price_values' => array( 'id' => DB_PREFIX.'_gallery_att_price_values',
                                             'price_id' => DB_PREFIX.'_gallery_att_pricing',
                                             'attr_id' => DB_PREFIX.'_gallery_product_attributes' ),
        
        DB_PREFIX.'_gallery_item_cat' => array( 'id' => DB_PREFIX.'_gallery_item_cat',
                                             'img_id' => DB_PREFIX.'_gallery_items',
                                             'cat_id' => MODULECATEGORIES_TABLE ),
    );
    
    return $modDep;
}


/**
* Returns tables used by the module for backup
*/
function gallery_getTableList( $params ) {

    return array( DB_PREFIX.'_gallery_items', 
    			  DB_PREFIX.'_gallery_display_options',
    			  DB_PREFIX.'_gallery_product_attributes',
    			  DB_PREFIX.'_gallery_manufacturers',
    			  DB_PREFIX.'_gallery_orders',
    			  DB_PREFIX.'_gallery_order_contents',
    			  DB_PREFIX.'_gallery_product_values',
    			  DB_PREFIX.'_gallery_shipping_options',
    			  DB_PREFIX.'_gallery_att_pricing',
    			  DB_PREFIX.'_gallery_att_price_values',
    			  DB_PREFIX.'_gallery_item_cat',
    		     );
}

/**
* Returns an array of possible module persmissions
*/
function gallery_getPermissions( $params ) {
    
    $restrictedSections2 = $params[0];
    $new = $params[1];

    $moduleKey = 'gallery';

    $items = array(
                array(
                    'id'        => 'cm_'.$moduleKey.'_manage_categories',
                    'title'     => 'Manage Categories',
                    'restricted'=> $restrictedSections2['0_cm_'.$moduleKey.'_manage_categories'],
                    'indent'    => 2
                ),
                array(
                    'id'        => 'cm_'.$moduleKey.'_add_images',
                    'title'     => 'Add Images',
                    'restricted'=> $restrictedSections2['0_cm_'.$moduleKey.'_add_images'],
                    'indent'    => 2
                ),
                array(
                    'id'        => 'cm_'.$moduleKey.'_edit_images',
                    'title'     => 'Edit Images',
                    'restricted'=> $restrictedSections2['0_cm_'.$moduleKey.'_edit_images'],
                    'indent'    => 2
                ),
                array(
                    'id'        => 'cm_'.$moduleKey.'_delete_images',
                    'title'     => 'Delete Images',
                    'restricted'=> $restrictedSections2['0_cm_'.$moduleKey.'_delete_images'],
                    'indent'    => 2
                ),
                array(
                    'id'        => 'cm_'.$moduleKey.'_edit_settings',
                    'title'     => 'Edit Settings',
                    'restricted'=> $restrictedSections2['0_cm_'.$moduleKey.'_edit_settings'],
                    'indent'    => 2
                )
            );

    return $items;

}

?>