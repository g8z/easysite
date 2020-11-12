<?php

$moduleKey = 'realty';


/**
 * Used when new user site is created (includes/ES_Site.php)
 */
function realty_getFieldDependance() {
    return $modDep = array();
}



function realty_getTableList() {

    return array( DB_PREFIX.'_realty_items' );
}

function realty_getOptions() {

    // the following properties correspond to DB fields...
    // true = the field should be used, false = the field should not be used
    // these options are also used in searches

	// note: the last boolean value indicates whether or not the actual
	// data for this field is boolean or not

	return array(

    'state'				=> array( 'Show State List', 'boolean', 'yes', 'State', false ),
    'country'			=> array( 'Show Country List', 'boolean', 'yes', 'Country', false ),
    'district'			=> array( 'Show District Option', 'boolean', 'yes', 'District', false ),
    'closing_date'		=> array( 'Show Closing Date', 'boolean', 'yes', 'Closing Date', false ),
    'city'				=> array( 'Show City Option', 'boolean', 'yes', 'City', false ),
    'county'			=> array( 'Show County Option', 'boolean', 'yes', 'County', false ),
    'home_age'     		=> array( 'Show Home Age Option', 'boolean', 'yes', 'Home Age', false ),
    'num_stories'     	=> array( 'Show # Stories Option', 'boolean', 'yes', '# Stories', false ),
	'floorsize'			=> array( 'Show Floor Size Option', 'boolean', 'yes', 'Floor Size', false ),
	'lotsize'			=> array( 'Show Lot Size Option', 'boolean', 'yes', 'Lot Size', false ),
    'fireplace'			=> array( 'Show Fireplace Option', 'boolean', 'yes', 'Fireplace', true ),
    'near_school'		=> array( 'Show Near School Option', 'boolean', 'yes', 'Near School', true ),
    'ocean_view'		=> array( 'Show Ocean View Option', 'boolean', 'yes', 'Ocean View', true ),
    'lake_view'			=> array( 'Show Lake View Option', 'boolean', 'yes', 'Lake View', true ),
    'mountain_view'		=> array( 'Show Mountain View Option', 'boolean', 'yes', 'Mountain View', true ),
    'river_front'		=> array( 'Show River Front Option', 'boolean', 'yes', 'River Front', true ),
    'ocean_front'		=> array( 'Show Ocean Front Option', 'boolean', 'yes', 'Ocean Front', true ),
    'lake_front'		=> array( 'Show Lake Front Option', 'boolean', 'yes', 'Lake Front', true ),
    'balcony'			=> array( 'Show Balcony Option', 'boolean', 'yes', 'Balcony Front', true ),
    'fitness_center'	=> array( 'Show Fitness Center Option', 'boolean', 'yes', 'Fitness Center', true ),
    'jacuzzi'			=> array( 'Show Jacuzzi Option', 'boolean', 'yes', 'Jacuzzi', true ),
    'garage'			=> array( 'Show Garage Option', 'boolean', 'yes', 'Garage', true ),
	'near_transit'		=> array( 'Show Near Transit Option', 'boolean', 'yes', 'Near Transit', true ),
	'laundry'			=> array( 'Show Laundry Option', 'boolean', 'yes', 'Laundry', true ),
	'pool'				=> array( 'Show Pool Option', 'boolean', 'yes', 'Pool', true ),
	'guest_house'		=> array( 'Show Guest House Option', 'boolean', 'yes', 'Guest House', true ),

	);
}

function realty_getPermissions( $params ) {

    $restrictedSections2 = $params[0];
    $new = $params[1];

    $moduleKey = 'realty';

    $items = array(
                    array(
                        'id'        => 'cm_'.$moduleKey.'_manage_categories',
                        'title'     => 'Manage Categories',
                        'restricted'=> $restrictedSections2['0_cm_'.$moduleKey.'_manage_categories'],
                        'indent'    => 2
                    ),
                    array(
                        'id'        => 'cm_'.$moduleKey.'_add_listings',
                        'title'     => 'Add Property Listings',
                        'restricted'=> $restrictedSections2['0_cm_'.$moduleKey.'_add_listings'],
                        'indent'    => 2
                    ),
                    array(
                        'id'        => 'cm_'.$moduleKey.'_edit_listings',
                        'title'     => 'Edit Property Listings',
                        'restricted'=> $restrictedSections2['0_cm_'.$moduleKey.'_edit_listings'],
                        'indent'    => 2
                    ),
                    array(
                        'id'        => 'cm_'.$moduleKey.'_delete_listings',
                        'title'     => 'Delete Property Listings',
                        'restricted'=> $restrictedSections2['0_cm_'.$moduleKey.'_delete_listings'],
                        'indent'    => 2
                    ),
                    array(
                        'id'        => 'cm_'.$moduleKey.'_edit_settings',
                        'title'     => 'Edit Settings',
                        'restricted'=> $restrictedSections2['0_cm_'.$moduleKey.'_edit_settings'],
                        'indent'    => 2
                    ),
            );

/*    if ( !$new ) {

        // add category permissions if not new site is created

        $items[] = array(
                            'id'        => 'comment',
                            'title'     => 'OR Browse property listings in the following categories: ',
                            'indent'    => 3
                        );

        $items = array_append( $items, getCategoryPermissionsArray( $moduleKey, 'listingscategory',  $restrictedSections2, 3, 0 ) );

    }*/


    return $items;

}

?>