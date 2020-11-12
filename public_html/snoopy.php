<?php

if ( !defined( 'SMARTY_DIR' ) )
    include_once( 'init.php' );

include( INCLUDE_DIR . 'Snoopy.class.php' );
@set_time_limit( 0 );

// This array contain names of files that
// was downloaded during the page load.
$downloaded_files = array();

//path to script - what about https:// links?
$server = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];


// use server temp folder to store cached css images
//$download_path = $_SERVER['DOCUMENT_ROOT'].TEMP_DIR;
//$download_path = DOC_ROOT.TEMP_DIR;

$download_path = FULL_PATH.TEMP_DIR.'/';// from EasySite config.php

$snoopy = new Snoopy;
$url = html_entity_decode($_REQUEST['url']);

$get = explode( '&', $_SERVER['QUERY_STRING'] );

$fields = array();

foreach( $get as $item ) {
    list ( $key, $value ) = split( '=', $item );
    if ( $key != 'url' ) {
        $fields[] = $item;
    }
}

$query_string = implode( '&', $fields );

if ( $url ) $delim = strpos( '?', $url ) ? '&' : '?';
$url .= $delim.$query_string;

if ($_POST) {
    $snoopy->submit( $url, $_POST );
} else  {
    $snoopy->fetch( $url );
}

$parts = parse_url( $url );

$body = $snoopy->results;

//Convert links that will redirect to a new page
$body = preg_replace_callback('/(<\s*form\s.*?>)/isx', "addFormField", $body);
$body = preg_replace_callback('/(<\s*(a|area|form)\s.*?(href|action|src)\s*=\s*)([\"\'])?(.+?["\' >])/isx', "href_replace", $body);


//Only convert image/css paths if there is no BASE tag on page. If there is, the browser will handle it.
if (preg_match("/<base/i", $body) == false) {
    $body = preg_replace_callback('/<(link |img |input |embed |table |td |tr |script )(.*?)(href|src|background|bgimage)=["\']?(.+?["\' >])/is', "local_replace", $body);
    $body = preg_replace_callback('/<(param\s+name\s*=\s*[\"\']?movie[\"\']?\s+)(.*?)(value)=["\']?(.+?["\' >])/is', "local_replace", $body);
    $body = preg_replace_callback('/<(link\s*.*?)>/is', "file_replace", $body);
    $body = preg_replace_callback('/<\s*style.*?<\/\s*style\s*>/is', "style_fixing", $body);
}

$body = html_entity_decode($body);

/**
 * This function prevents changing url(..) in javascript code
 * when needed only style
 */
function style_fixing( $match ) {
    $match[0] = preg_replace_callback('/url\s*\(\s*[\'\"]?(.*?)\s*[\'\"]?\s*\)/is', "style_url_replace", $match[0]);
    //echo "<pre>".htmlspecialchars($match[0])."</pre>";
    return $match[0];
}

/**
 * Download images and replaces urls in the styles
 */
function style_url_replace( $match ) {
    global $snoopy, $download_path, $downloaded_files;

    $relative = $match[1];
    $image = convertLink( $match[1] );

    if ( in_array( $image, array_keys( $downloaded_files ) ) ) {
        $filename = $downloaded_files[$image];
    } else {

        $ext = end( explode( '.', $image ) );
        $name = time();

        $snoopy->fetch( $image );

        $filename = $download_path.$name.'.'.$ext;
        $downloaded_files[$image] = $filename;

        // can we handle fwrite/fopen failures in a better way?
        $fp = @fopen( $filename, 'wb' );
        @fwrite( $fp, $snoopy->results, strlen( $snoopy->results ) );
        @fclose( $fp );
    }

    return 'url('.$filename.')';
}

/**
 * Replaces <link href=..> to <style>..</style>
 * for proper image displaying in styles with url(..) entries
 */
function file_replace( $match ) {
    global $snoopy;

    // Href was already converted to the proper server path
    preg_match( '/href\s*=\s*[\'\"]?(.*?)[\'\" >]/', $match[0], $m );
    $href = $m[1];

    $snoopy->fetch( $href );

    return "<style>\r\n".$snoopy->results."</style>\r\n";
}

function convertLink( $link ) {
    global $url, $server, $parts;

    if ( $link[0] == '#' ) return $link;

    if ( $link[0] == '/' ) {
        return $link = 'http://'.$parts[host] . $link;
    }
    if ( substr($link, 0, 2) == ".." ) {
        $count = 0;
        for ( $i=0, $n=strlen( $parts[path] ); $i<$n; $i++ )
            if ( $parts[$i] == '/' ) $count++;
        if ( $count < 2 )
            return $link = 'http://'.$parts[host]. substr( $link, 2, strlen( $link ) -2 );
        else {
            return $link = 'http://'.$parts[host].'/' . $link;
        }
    }

    preg_match("/^[^\?]+/",$url,$m);

    $mat = preg_replace("|/[^\/\.]+\.[^\/\.]+$|","",$m[0]);
    if ( $mat == 'http:/' ) $mat = 'http://'.$parts[host];

    $search = array(    "|^http://".preg_quote($server)."|i",
                        "|^(?!http://)(\/)?(?!mailto:)|i",
                        "|/\./|",
                        "|/[^\/]+/\.\./|"
                    );

    $replace = array(   "",
                        $mat."/",
                        "/",
                        "/"
                    );

    $expandedLink = preg_replace($search,$replace,$link);

    return $expandedLink;
}


function addFormField( $m ) {
    global $url, $server;

    preg_match( '/.*?action\s*=\s*[\"\']?(.+?["\' >])/isx', $m[0], $match );

    $link = substr($match[1],0,-1);
    $link = convertLink( $link );

    return $m[0]."<input type='hidden' name='url' value='$link'>";
}

function href_replace($match) {
    global $url, $server;

    $link = substr($match[5],0,-1);

    $end = "";
    if (preg_match("/javascript:/i", $match[5])) {
        return $match[0];
    }

    if (substr($match[5],-1) == ">") {
        $end = ">";
    }

    if ( $link[0] != '#' && !eregi ( 'mailto', $link ) ) {
        $link = $server.'?url='.urlencode( convertLink( $link ) );
    }

    return $match[1]."'".$link."'".$end;
}

function local_replace($match) {
    global $url;
    $end = "";
    if (substr($match[4],-1) == ">") {
        $end = ">";
    }
    $match[4] = substr($match[4],0,-1);
    $match[4] = convertLink( $match[4] ) ;

    return '<'.$match[1].' '.$match[2].' '.$match[3].'="'.$match[4].'"'.$end;
}

$t->assign( 'body', $body );
$t->assign( 'bodyTemplate', 'pages/snoopy.tpl' );

include_once( 'init_bottom.php' );
$t->display( $templateName );


?>