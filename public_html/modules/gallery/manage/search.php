<?php

require_once '../config.php';

$startid = 0;
$startLevel = 0;
$levelLimit = 0;

$add_fields = array( 'site_key' );
$add_values = array( $site );


/**
 * @return void
 * @param int $parent
 * @param string $query sql query which may contain additional conditions
 * @param int $count reference to variable where count to be placed
 * @desc Recursively gets the count of images starting with $parent category
*/
function getImagesCount( $parent, $query ) {
    global $db, $category;

    $categories = array_keys( $category->getCategoryArray( $parent ) );
    $count = 0;

    for ( $i=0, $n=count( $categories ); $i<$n; $i++ ) {
        $cat_id = $categories[$i];
        $im = $db->query( $query." and i.cat_id='$cat_id' order by i._order" );

        $count += $im->numRows();

    }

    return $count;

}


/**
 * @return array $images
 * @param int $parent
 * @param string $query
 * @desc Recursively gets the array of images starting with $parent category
*/
function getImages( $parent, $query ) {
    global $db, $category;

    $images = array();
    $categories = @array_keys( $category->getCategoryArray( $parent ) );

    for ( $i=0, $n=count( $categories ); $i<$n; $i++ ) {

        $im = array();
        
        $cat_id = $categories[$i];
        $im = $db->getAll( $query." and i.cat_id='$cat_id' order by i._order" );

        if ( count( $im ) ) {
            $im[0][first] = 1;
            $im[ count( $im ) - 1 ][last] = 1;

            $images = array_append( $images, $im );
        }

    }

    return $images;
}




if ( $search ) {


    extract( $_POST );
    $title = addslashes( $title );

    $startTime = mktime( 0, 0, 0, $Start_Month, $Start_Day, $Start_Year );
    $endTime = mktime( 23, 59, 59, $End_Month, $End_Day, $End_Year );

    $cond = "UNIX_TIMESTAMP(i.created)>=$startTime and UNIX_TIMESTAMP(i.created)<=$endTime";
    //if ( $name ) $cond .= " and i.name LIKE '%$name%'";
    if ( $title ) $cond .= " and i.title LIKE '%$name%'";
    $query = 'select i.*, 0 as first, 0 as last, c.title as c_title from ' . IMAGES_TABLE . ' i '.
                                  'LEFT JOIN ' . MODULECATEGORIES_TABLE . " c on i.cat_id=c.id and c.site_key='$site' ".
                                  "where $cond and i.site_key='$site'";
    $images = array();
    if ( $cat_id && !$subCategories) {
        $cond .= " and i.cat_id='$cat_id'";
        $images = $db->getAll( $query.' order by i._order');

        $count = count( $images );

        $n = new Navigation();
        $n->_total = $count;
        $n->_start = $_REQUEST['start'];

        if ( !$n->_start )
          $n->_start = '0';

        $limit = ' limit ' . $n->_start . ',' . $n->_itemsPerPage;


        $images = $db->getAll( $query." order by i._order $limit" );

        $t->assign( 'minLevel', 0 );
        $out = $t->fetch( 'modules/gallery/manage/imagesList.tpl' );

    } else {

        $minLevel = 0;

        $t->assign( 'minLevel', $minLevel );

        $count = getImagesCount( $category, $query );

        $n = new Navigation();

        // total number of items
        $n->_total = $count;

        // number of items to display per page
        $n->_itemsPerPage = $perPage;

        // the number of links to show in the navigation bar (in case > 10)
        $n->_pagesPerScreen = 10;

        // the search vars that we should pass from screen to screen
        $n->_requestVars = $_REQUEST;

        $n->_separator = ' | ';

        $images = getImages( $category, $query );
        $images = array_slice( $images, $n->_start, $n->_itemsPerPage );

        if ( $count ) {
            $t->assign( 'images', $images );
            $out = $t->fetch( 'modules/gallery/manage/imagesList.tpl' );
        }

    }

    // Store post data for return to the previous page

    $prevPost = getPostArray();

    $t->assign( 'prevPost', $prevPost );
    $t->assign( 'navigation', $n->output() );
    $t->assign( 'images_list', $out );

    $t->assign( 'bodyTemplate', 'modules/gallery/manage/searchResults.tpl' );


} else {


    // get the minimum image year

    $minYear = $db->getOne( 'select min( year( created ) ) from ' . IMAGES_TABLE . " where site_key = '$site'" );

    $t->assign( 'minYear', $minYear );

    $t->assign( 'perPageList', array( 5, 10, 20, 30, 40, 50, 75, 100, 200, 300, 500, 1000 ) );

    $t->assign( 'bodyTemplate', 'modules/gallery/manage/search.tpl' );
}


include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );

?>