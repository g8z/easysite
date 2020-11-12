<?php

/**
Generic PHP functions. Please only put general-use PHP functions
in this file. Feature-specific functions should be kept local to
the particular feature.
*/



/**
 * Builds an associative array from an $array columns $key and $value
 */
function getAssocArray( $key, $value, $array ) {

    $assocArray = array ();

    $arrayCount = count ($array);

    for ($i = 0; $i < $arrayCount; $i++) {
        $assocArray[$array[$i][$key]] = $array[$i][$value];
    }

    return $assocArray;
}

/**
 * Display any polls for the given user id. If $uid = false, then display any
 * polls being shown to all users.
 */
function displayPolls() {
	global $db, $t, $site;

	if ( !$uid )
		$uid = '0';

	//print_r( $_SESSION );

	$group = $_SESSION['es_auth']['group_id'];

	$activePolls = $db->getAll( 'select * from ' . POLLS_TABLE . " where site_key = '$site' and active = '1'" );

	//print_r( $activePolls );

	// for each available active poll, check to see if it has been displayed to the users

	$newActivePolls = array();

	$userip = $_SERVER['REMOTE_ADDR'];

	foreach( $activePolls as $index => $row ) {

		// check user authentication to view this poll

		if ( ( $_SESSION['es_auth']['group_id'] > 0 && $row[group_id] == 'auth' )
			|| $_SESSION['es_auth']['group_id'] == $row[group_id]
			|| $row[group_id] == 'all' ) {

			$pollViewed = $db->getOne( 'select id from ' . POLLRESULTS_TABLE . " where poll_id = '$row[id]' and site_key = '$site' and user_ip = '$userip'" );


			if ( !$pollViewed ) {
				$newActivePolls [] = $row;
			}
		}
	}

	//print_r( $newActivePolls );

	// poll popup code will be displayed in default.tpl
	$t->assign( 'activePolls', $newActivePolls );
}

/**
 * Determines the login form ID, and generates an internal path to it
 */
/*
function getLoginFormPath() {
	return FULL_PATH . 'getForm.php';
}
*/

/**
 * Write a binary file to the specified path. Used for image caching feature.
 */
function writeFile ( $path, $data )
{
	$fp = fopen ( $path, 'wb' ) or die ( "Can't open file $path for writing" );
	$fout = fwrite ( $fp , $data );

	if ( !$data )
		die ( "Write failure! No data to write to $path!" );

	if ( $fout == 0 )
		die ( "Write failure! File $path NOT written" );

	fclose ( $fp );
}

/**
 * Returns a link to a cached image
 * //image.php?type=vt_image
 */
function getImage( $options ) {

	global $cacheImages, $refreshCache, $site;

	if ( $refreshCache )
		$ext = '?' . time();

	if ( $cacheImages && !$options['id'] ) {
		echo DOC_ROOT . TEMP_DIR . '/' . $site . '/' . $options['type'] . '.gif' . $ext;
	}
	else if ( $cacheImages ) {
		echo DOC_ROOT . TEMP_DIR . '/' . $site . '/' . $options['type'] . $options['id'] . '.gif' . $ext;
	}
	else if ( $options['id'] ) {
		echo DOC_ROOT . 'image.php?type=' . $options['type'] . '&id=' . $options['id'];
	}
	else {
		echo DOC_ROOT . 'image.php?type=' . $options['type'];
	}
}

/**
 * E-mail syntax checker
 */
function validEmail( $email ) {
	if ( eregi( "^[0-9a-z]([-_.]?[0-9a-z]*)*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]*$", $email, $check ) ) {
		if ( strstr( $check[0], "@" ) )
			return true;
	}
	return false;
}

/**
 * Reads data from the $_FILES array for the image named $name
 */
function getFileData( $name ) {
	return addslashes( @fread( fopen( $_FILES[$name][tmp_name], "rb" ), @filesize( $_FILES[$name][tmp_name] ) ) );
}

/**
 * Reads data from the $_FILES array for the image named $name
 */
function getFileFromDiskData( $name ) {
	return addslashes( @fread( fopen( $name, "rb" ), @filesize( $name ) ) );
}


/*
// somewhat buggy when inputs contain / and \ symbols
function stri_replace($old, $new, $haystack) {
	 return preg_replace('/'.quotemeta($old).'/i', $new, $haystack);
}
*/

/**
 * Case-insensitive string replacement - from www.php.net
 */
function stri_replace($find,$replace,$string)
{
    if( !is_array($find) )
    	$find = array($find);

	if(!is_array($replace))
	{
		if(!is_array($find)) {
			$replace = array($replace);
		}
		else {
			// this will duplicate the string into an array the size of $find
			$c = count($find);
			$rString = $replace;
			unset($replace);

			for ($i = 0; $i < $c; $i++) {
				$replace[$i] = $rString;
			}
		}
	}
	foreach($find as $fKey => $fItem) {
		$between = explode(strtolower($fItem),strtolower($string));
		$pos = 0;

		foreach($between as $bKey => $bItem) {
			$between[$bKey] = substr($string,$pos,strlen($bItem));
			$pos += strlen($bItem) + strlen($fItem);
		}
		$string = implode($replace[$fKey],$between);
	}
	return($string);
}

/**
 * Splits set of sql queries into an array
 */
function splitSql($sql)
{
    $sql = preg_replace("/\r/s", "\n", $sql);
    $sql = preg_replace("/[\n]{2,}/s", "\n", $sql);
    $lines = explode("\n", $sql);
    $queries = array();
    $inQuery = 0;
    $i = 0;

    foreach ($lines as $line) {
        $line = trim($line);

        if (!$inQuery) {
            if (preg_match("/^CREATE/i", $line)) {
                $inQuery = 1;
                $queries[$i] = $line;
            }
            elseif (!empty($line) && $line[0] != "#") {
                $queries[$i] = preg_replace("/;$/i", "", $line);
                $i++;
            }
        }
        elseif ($inQuery) {
            if (preg_match("/^[\)]/", $line)) {
                $inQuery = 0;
                $queries[$i] .= preg_replace("/;$/i", "", $line);
                $i++;
            }
            elseif (!empty($line) && $line[0] != "#") {
                $queries[$i] .= $line;
            }
        }
    }

    return $queries;
}


function parseInsertSql( $sql, $table, &$fields, &$values ) {
    
      $sql = trim( $sql );

      if ( empty( $sql ) || $sql[0] == '#' )
          return false;
          
      $sql = str_replace( '[prefix]_', DB_PREFIX.'_', $sql );
      
      $pattern = '/insert\s+into\s+`?'.$table.'`?\s+\((.*?)\)\s+values\s+\((.*?)\)\s*$/i';
      
      if ( !preg_match( $pattern, $sql, $matches ) ) 
          return false;
      
      $tempFields = split( ',', trim( $matches[1], ' ' ) );
      
      foreach ( $tempFields as $field ) {
          $fields[] = trim( $field, ' `' );
      }
      
      $tempValues = split( ',', trim( $matches[2], ' ' ) );
      $value = '';
      
      for ( $i=0, $n=count( $tempValues ); $i<$n; $i++ ) {
          
          $value .= $tempValues[$i];
          
          if ( (substr_count( $value, '\'' ) - substr_count( $value, '\\\'' )) % 2 == 0 ) {
              $values[] = trim( $value, " '" );
              $value = '';
          } else { 
              $value .= ',';
          }
      }
      
      return true;
 

}

/**
 * retrieves the user's browser type
 */
function getUserBrowser()
{
    global $HTTP_USER_AGENT, $_SERVER;
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    }
    elseif (getenv("HTTP_USER_AGENT")) {
        $HTTP_USER_AGENT = getenv("HTTP_USER_AGENT");
    }
    elseif (empty($HTTP_USER_AGENT)) {
        $HTTP_USER_AGENT = "";
    }

    if (eregi("MSIE ([0-9].[0-9]{1,2})", $HTTP_USER_AGENT, $regs)) {
        $browser['agent'] = 'MSIE';
        $browser['version'] = $regs[1];
    }
    elseif (eregi("Mozilla/([0-9].[0-9]{1,2})", $HTTP_USER_AGENT, $regs)) {
        $browser['agent'] = 'MOZILLA';
        $browser['version'] = $regs[1];
    }
    elseif (eregi("Opera(/| )([0-9].[0-9]{1,2})", $HTTP_USER_AGENT, $regs)) {
        $browser['agent'] = 'OPERA';
        $browser['version'] = $regs[2];
    }
    else {
        $browser['agent'] = 'OTHER';
        $browser['version'] = 0;
    }

    return $browser['agent'];
}

// possibly eliminate?
function getNewWindowHref( $href, $width, $height ) {
    $uw = $width + 10;
    $uh = $height + 20;
    return "javascript:launchCentered( '$href', $uw, $uh, 'resizable=no,scrollbars=no' );";
}

/**
 * Appends array $source to the end of array $dest
 */
function array_append( $dest, $source ) {
    $n = count( $dest );
    $n1 = count( $source );
    for ( $index=$n; $index<$n+$n1; $index++ ) {
        $dest[$index] = $source[$index-$n];
    }
    return $dest;
}




/**
 * Determines if GD library is installed
 */
function gdInstalled() {
    return function_exists( 'gd_info' );
}



function getSetting( $name, $default=NULL ) {
    global $gallerySettings;

    return isset( $gallerySettings[$name] ) ? $gallerySettings[$name] : $default;
}

function assignCenterVariables( $body_x, $body_w, &$layerData ) {
    
    global $db, $t, $c, $site;
    
    
    $bodyWPercent = ereg( "\%", $body_w );
    $bodyXPercent = ereg( "\%", $body_x );
    
    $minLayerX = 10000;
    $maxLayerX = 0;
    
    if ( is_array($layerData) && count($layerData) )

    foreach( $layerData as $layer ) {
    	
    	$layer[_left] = intval( $layer[_left] );
    	$layer[width] = intval( $layer[width] );
    	
        if ( $layer[_left] < $minLayerX )
            $minLayerX = $layer[_left];

        if ( $layer[_left] + $layer[width] > $maxLayerX )
            $maxLayerX = $layer[_left] + $layer[width];
    }
    

    $c->_table = SETTINGS_TABLE;
    $c->_field = 'value';
    $c->_id = $t->get_template_vars( 'bgImageId' );
    
    //echo $c->_id, $c->path( 'full' );
    
    $bgDim = @getimagesize( $c->path( 'full' ) );
    //print_r( $bgDim );
    $t->assign( 'bgImageWidth', intval( $bgDim[0] ) );

    $t->assign( 'minLayerX', $minLayerX );
    $t->assign( 'maxLayerX', $maxLayerX );
    $t->assign( 'bodyWPercent' , $bodyWPercent );
    $t->assign( 'bodyXPercent' , $bodyXPercent );
    $t->assign( 'percentWValue', sprintf( '%2.2f', $body_w / 100.0 ) );
    $t->assign( 'percentXValue', sprintf( '%2.2f', $body_x / 100.0 ) );
}


function isInTemplate( $resourceType, $resourceId ) {
    
    global $site, $db;

    $menuItem = $db->getRow( 'select id, in_template from '. MENUITEMS_TABLE ." where site_key='$site' and resource_type='$resourceType' and resource_id='$resourceId'" );
    if ( $menuItem ) {
        $in_template = $menuItem['in_template'];
    }
    else 
        $in_template = 'yes';
        
    return $in_template == 'yes' ? true : false;

}

function cut( $value, $len ) {
    return ( strlen($value) > $len ) ? substr( $value, 0, $len - 1) .'...' : $value;
}


/** 
* This will remove HTML tags, javascript sections
* and white space. It will also convert some
* common HTML entities to their text equivalent.
* $conent should contain an HTML document.
* From PHP Manual.
*/
function stripHTMLTags( $content ) {
    
    $search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
                     "'<\s*br\s*(\/)?>'i",              // Replace brs to spaces
                     "'<[\/\!]*?[^<>]*?>'si",           // Strip out html tags
                     "'([\r\n])[\s]+'",                 // Strip out white space
                     "'&(quot|#34);'i",                 // Replace html entities
                     "'&(amp|#38);'i",
                     "'&(lt|#60);'i",
                     "'&(gt|#62);'i",
                     "'&(nbsp|#160);'i",
                     "'&(iexcl|#161);'i",
                     "'&(cent|#162);'i",
                     "'&(pound|#163);'i",
                     "'&(copy|#169);'i",
                     "'&#(\d+);'");
    
    $replace = array ("",
                      " ",
                      "",
                      "\\1",
                      "\"",
                      "&",
                      "<",
                      ">",
                      " ",
                      chr(161),
                      chr(162),
                      chr(163),
                      chr(169),
                      "chr(\\1)");
    
    $content = preg_replace ($search, $replace, $content);
    
    return $content;
}


/**
 * Return values and titles array from table to be used in smarty template
 */
function getCombo( $table, $values, $titles, $where='' ) {
	
	global $site, $db;
	
	if ( $where == '' )
		$where = "site_key='$site'";
		
	$items = $db->getAll( "select $values, $titles from $table where $where" );
	
	$aValues = array();
	$aTitles = array();
	
	if ( is_array( $items ) && count( $items ) ) {
		foreach ( $items as $idx=>$item ) {
			$aValues[] = $item[$values];
			$aTitles[] = $item[$titles];
		}
	}
	
	return array( $aValues, $aTitles );
}


function getSQLShares( $resource, $permission='' ) {
	
	$ids = array();
	
	if ( !$permission ) {
		// any permissison
		$ids = @array_keys( $_SESSION['shares'][$resource] );
	}
	else {
	
		if ( is_array( $_SESSION['shares'][$resource] ) && count( $_SESSION['shares'][$resource] ) )
		foreach ( $_SESSION['shares'][$resource] as $id=>$prop ) {
			if ( $prop[$permission] ) 
				$ids[] = $id;
		}
	}
	
	if ( !@count( $ids ) )
		$ids = array( 'null' );
		
	return implode( ',', $ids );
	
}


/**
 * Used for creation smarty html_select arrays
 */
function getKeyTitle( $key, $title, $table, $shared='' ) {
	
	global $db, $site;
	
	// get arrays for smarty html_options
	
	$keys = array();
	$titles = array();
	
	if ( $shared ) {
		$list = $db->getAll( "select $key, if( $key in ($shared), concat($title, '[shared]'), $title) as $title from $table where (site_key='$site' or $key in ($shared)) order by $title" );
	}
	else {
		$list = $db->getAll( "select $key, $title from $table where site_key='$site' order by $title" );
	}
	
	foreach ( $list as $idx=>$item ) {
		$keys[] = $item[$key];
		$titles[] = $item[$title];
	}
	
	array_unshift( $keys, 0 );
	array_unshift( $titles, '- Select One -' );
	
	return array( $keys, $titles );
}


function isError( $resource ) {
	
	return strtolower( @get_class( $resource ) ) == 'error';
}


function getCacheFileName( $type, $id ) {
	
	global $system;
	
	$dir = FULL_PATH.TEMP_DIR.'/';
	
	switch( $type ) {
		case 'autoCenter':
			$filename = $dir.intval($system->skin_id).'_'.$_SESSION['site'].'_autoCenter.js';
			break;
			
		case 'system':
			$filename = $dir.intval($system->skin_id).'_'.$_SESSION['site'].'_system.js';
			break;
			
		case 'systemStyles':
			$filename = $dir.intval($system->skin_id).'_'.$_SESSION['site'].'_systemStyles.css';
			break;
			
		case 'areaStyles':
			$filename = $dir.intval($system->skin_id).'_'.$_SESSION['site'].'_areaStyles.css';
			break;
			
		case 'menu_js':
			$filename = $dir.'menu'.intval($id).'_script.js';
			break;
		
		case 'menu_css':
			$filename = $dir.'menu'.intval($id).'_styles.css';
			break;
			
		case 'menu_links':
			$filename = $dir.'menu'.intval($id).'_links.html';
			break;
	}
	
	return $filename;
}


function checkCache( $lastChange, $type, $source, $id=0 ) {
	global $t;
	
	$filename = getCacheFileName( $type, $id );
	if ( @filemtime( $filename ) < $lastChange ) {
		deleteCache( $type, $id );
		writeCache( $type, $t->fetch( $source.'.tpl' ), $id );
	}
	
}

function deleteCache( $type, $id=0 ) {
	
	$filename = getCacheFileName( $type, $id );
	
	@unlink( $filename );
}


function writeCache( $type, $content, $id=0 ) {
	
	$filename = getCacheFileName( $type, $id );
	
	return @writeFile( $filename, $content );
}

function cacheExists( $type, $id=0 ) {
	
	$filename = getCacheFileName( $type, $id );
	
	return @file_exists( $filename );
}

function getCache( $type, $id=0 ) {
	$content = '';
	$filename = getCacheFileName( $type, $id );
	$fp = @fopen( $filename, 'r' );
	if ( $fp ) {
		while ( !feof( $fp ) ) 
			$content .= fread( $fp, 10000 );
		fclose( $fp );
	}
	return $content;
}

function loginError( $message='' ) {
	global $t, $user, $system, $site, $db, $session, $templateName;
	$_REQUEST['form_id'] = '';
	$_GET['form_id'] = '';
	$_POST['form_id'] = '';
	$form_id = '';
	$t->assign( 'login_error', $message );
	include( FULL_PATH . 'getForm.php' );
	exit;
}


// $related supposes 'parent' field exists
function getMenuArrays( $table, $key, $title, $w='1', $order='', $parent=-1, $level=0 ) {
	
	global $db;
	
	$ids = array();
	$titles = array();
	
	$indent = '--';
	
	$where = 'where '.$w;
	
	if ( $parent != -1 ) {
		$where .= " and parent='$parent'";
		$parentField = ', parent';
	}
	
	if ( !$order )
		$order = $title;
		
	$items = $db->getAll( "select $key, $title $parentField from $table $where order by $order" );
	
	if ( is_array( $items ) && count( $items ) )
	foreach ( $items as $idx=>$item ) {
		$ids[] = $item[$key];
		$titles[] = str_pad( $item[$title], strlen( $indent ) * $level + strlen( $item[$title] ), $indent, STR_PAD_LEFT );
		
		if ( $parent != -1 ) {
			list( $i, $t ) = getMenuArrays( $table, $key, $title, $w, $order, $item['id'], $level+1 );
			$ids = array_append( $ids, $i );
			$titles = array_append( $titles, $t );
		}
	}
	
	return array( $ids, $titles );
}


function getSefTitle( $title, $table, $field, $id ) {
	
	global $db, $system;
	
	// if we call this function then we are going to change some resource title
	// and some menu item may link to that resource 
	// so we need update menus in case we are using sef urls
	if ( $system && $system->settings['sef_urls'] == 'yes' )
		$db->query( 'update '.MENUITEMS_TABLE.' set last_change=NOW()' );
	
	$sefTitleF = sanitize_title( $title );
	$i = -1;
	do {
		$i++;
		$sefTitle = $i ? $sefTitleF.'-'.$i : $sefTitleF;
		$sameSefNameId = $db->getOne( "select id from $table where $field='$sefTitle'" );
	}
	while ( $sameSefNameId && ($sameSefNameId != $id) );
	
	return $sefTitle;	
}


// -- functions below was taken from WordPress for generating SEF title -- //

function sanitize_title($title) {
	$title = strip_tags($title);
	// Preserve escaped octets.
	$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
	// Remove percent signs that are not part of an octet.
	$title = str_replace('%', '', $title);
	// Restore octets.
	$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

	$title = remove_accents($title);
	if (seems_utf8($title)) {
		if (function_exists('mb_strtolower')) {
			$title = mb_strtolower($title, 'UTF-8');
		}
		$title = utf8_uri_encode($title);
	}

	$title = strtolower($title);
	$title = preg_replace('/&.+?;/', '', $title); // kill entities
	$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
	$title = preg_replace('/\s+/', '-', $title);
	$title = preg_replace('|-+|', '-', $title);
	$title = trim($title, '-');

	return $title;
} 

function remove_accents($string) {
	if (seems_utf8($string)) {
		$chars = array(
		// Decompositions for Latin-1 Supplement
		chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
		chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
		chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
		chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
		chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
		chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
		chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
		chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
		chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
		chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
		chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
		chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
		chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
		chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
		chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
		chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
		chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
		chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
		chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
		chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
		chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
		chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
		chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
		chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
		chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
		chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
		chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
		chr(195).chr(191) => 'y',
		// Decompositions for Latin Extended-A
		chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
		chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
		chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
		chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
		chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
		chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
		chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
		chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
		chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
		chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
		chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
		chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
		chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
		chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
		chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
		chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
		chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
		chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
		chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
		chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
		chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
		chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
		chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
		chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
		chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
		chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
		chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
		chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
		chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
		chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
		chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
		chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
		chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
		chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
		chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
		chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
		chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
		chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
		chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
		chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
		chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
		chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
		chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
		chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
		chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
		chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
		chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
		chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
		chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
		chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
		chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
		chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
		chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
		chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
		chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
		chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
		chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
		chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
		chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
		chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
		chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
		chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
		chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
		chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
		// Euro Sign
		chr(226).chr(130).chr(172) => 'E');
		
		$string = strtr($string, $chars);
	} else {
		// Assume ISO-8859-1 if not UTF-8
		$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
			.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
			.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
			.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
			.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
			.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
			.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
			.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
			.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
			.chr(252).chr(253).chr(255);

		$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

		$string = strtr($string, $chars['in'], $chars['out']);
		$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
		$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
		$string = str_replace($double_chars['in'], $double_chars['out'], $string);
	}

	return $string;
}


function seems_utf8($Str) { # by bmorel at ssi dot fr
	for ($i=0; $i<strlen($Str); $i++) {
		if (ord($Str[$i]) < 0x80) continue; # 0bbbbbbb
		elseif ((ord($Str[$i]) & 0xE0) == 0xC0) $n=1; # 110bbbbb
		elseif ((ord($Str[$i]) & 0xF0) == 0xE0) $n=2; # 1110bbbb
		elseif ((ord($Str[$i]) & 0xF8) == 0xF0) $n=3; # 11110bbb
		elseif ((ord($Str[$i]) & 0xFC) == 0xF8) $n=4; # 111110bb
		elseif ((ord($Str[$i]) & 0xFE) == 0xFC) $n=5; # 1111110b
		else return false; # Does not match any model
		for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
			if ((++$i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80))
			return false;
		}
	}
	return true;
} 


function utf8_uri_encode( $utf8_string ) {
  $unicode = '';        
  $values = array();
  $num_octets = 1;
        
  for ($i = 0; $i < strlen( $utf8_string ); $i++ ) {

    $value = ord( $utf8_string[ $i ] );
            
    if ( $value < 128 ) {
      $unicode .= chr($value);
    } else {
      if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;
                
      $values[] = $value;
      
      if ( count( $values ) == $num_octets ) {
	if ($num_octets == 3) {
	  $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
	} else {
	  $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
	}

	$values = array();
	$num_octets = 1;
      }
    }
  }

  return $unicode;    
} 

// -- end WordPress functions -- //

?>