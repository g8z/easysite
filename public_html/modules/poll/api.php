<?php

/**
 * Used when new user site is created (includes/internal/class.site.php)
 */
function poll_getFieldDependance() {
    
    $modDep = array(
        DB_PREFIX.'_polls' => array( 'id' => DB_PREFIX.'_polls',
                                     'form_id' => FORMS_TABLE,
                                     'group_id' => GROUPS_TABLE ),

        // no poll results in new site
        // DB_PREFIX.'_poll_results' => array( 'id' => DB_PREFIX.'_poll_results', )
                                            
    );
    
    return $modDep;
}


/**
 * Used for backup (manage/backup.php)
 */ 
function poll_getTableList() {

    return array( DB_PREFIX.'_polls', DB_PREFIX.'_poll_results' );
}


/**
 * Used for permission managament (manage/editPermssions.php)
 */
function poll_getPermissions( $params ) {
    
    $restrictedSections2 = $params[0];
    $new = $params[1];

    $moduleKey = 'poll';

    $items = array(
                    array(
                        'id'        => 'cm_'.$moduleKey.'_add_polls',
                        'title'     => 'Add Polls',
                        'restricted'=> $restrictedSections2['0_cm_'.$moduleKey.'_add_polls'],
                        'indent'    => 2
                    ),
                    array(
                        'id'        => 'cm_'.$moduleKey.'_edit_polls',
                        'title'     => 'Edit Polls',
                        'restricted'=> $restrictedSections2['0_cm_'.$moduleKey.'_edit_polls'],
                        'indent'    => 2
                    ),
                    array(
                        'id'        => 'cm_'.$moduleKey.'_reset_polls',
                        'title'     => 'Reset Poll Results',
                        'restricted'=> $restrictedSections2['0_cm_'.$moduleKey.'_reset_polls'],
                        'indent'    => 2
                    ),
                    array(
                        'id'        => 'cm_'.$moduleKey.'_delete_polls',
                        'title'     => 'Delete Polls',
                        'restricted'=> $restrictedSections2['0_cm_'.$moduleKey.'_delete_polls'],
                        'indent'    => 2
                    ),
            );

    return $items;

}

?>