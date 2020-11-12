<?php


function assignOrderFields() {
	global $t, $gallerySettings;
	
	$fields = unserialize( $gallerySettings['checkoutFields'] );
	
	$personalFields = array( 'first_name', 'last_name', 'email', 'phone' );
	$shippingFields = array( 'address_1', 'address_2', 'city', 'states', 'countries', 'zip' );
	
	foreach( $personalFields as $idx=>$field ) {
		if ( $fields[$field]['visible'] )
			$pFields[$field] = $fields[$field];
	}
	
	foreach( $shippingFields as $idx=>$field ) {
		if ( $fields[$field]['visible'] )
			$sFields[$field] = $fields[$field];
	}
	$sFields['shipping_method'] = array_merge( 
		array( 'visible'=>$fields['shipping_method']['show_shipping'] ), 
		$fields['shipping_method'] 
	);
	
	$t->assign( 'fields', $fields );
	$t->assign( 'pFields', $pFields );
	$t->assign( 'sFields', $sFields );
	$t->assign( 'paymentField', $fields['payment_method'] );
	$t->assign( 'payment', $fields['payment_method']['payment'] );
	$t->assign( 'require_shipping', $fields['require_shipping']['require'] );
}

function saveSetting( $name, $value ) {
    global $db, $site;
    $result = $db->query( 'select value from ' . MODULESETTINGS_TABLE . " where name='$name' and site_key='$site'" );
    if ( $result->numRows() ) {
        $db->query( 'update  ' . MODULESETTINGS_TABLE . " set value='$value' where name='$name' and site_key='$site'" );
    } else {
        $db->query( 'insert into ' . MODULESETTINGS_TABLE . "( name, value, site_key ) values ( '$name', '$value', '$site' )" );
    }
}

function getAllSettings( $skipImages=array() ) {
    global $db, $site, $moduleKey;
    
    $skipSQL = "'". implode( "', '", $skipImages ) ."'";

    $data = $db->getAll( 'select name, value from '.MODULESETTINGS_TABLE." where site_key='$site' and module_key = '$moduleKey' and name not in ($skipSQL)" );

    $arr = array();

    foreach( $data as $index => $row ) {
    	if ( $row['name'] == 'attributesList' || $row['name'] == 'visibleFields' )
    		$value = @unserialize( $row['value'] );
    	else
    		$value = $row['value'];
    		
        $arr["$row[name]"] = $value;
    }
    
    if ( !@count( $arr['visibleFields'] ) )
    	$arr['visibleFields'] = array( 'all' );

    return $arr;

    //return isset( $value ) ? $value : $default;
}

function getSetImages( $images ) {
	global $db, $moduleKey, $site;
	
    $SQL = "'". implode( "', '", $images ) ."'";
    
    $imgs = $db->getAll( 'select id, name from '. MODULESETTINGS_TABLE." where name in ($SQL) and module_key='$moduleKey'  and site_key='$site'" );
    
    $images = array();
    foreach( $imgs as $idx=>$item ) {
    	$images[$item['name']] = $item['id'];
    }
    
    return $images;
}

function getPostArray() {
    $post = array();
    $i=-1;
    foreach ( $_POST as $key => $value ) {
        if ( $key != 'galleryAction' ) {
            $i++;
            $post[$i][name] = $key;
            $post[$i][value] = $value;
        }
    }

    return $post;
}

function getArray( $arr ) {
    $post = array();
    $i=-1;
    foreach ( $arr as $key => $value ) {
        if ( $key != 'galleryAction' ) {
            $i++;
            $post[$i][name] = $key;
            $post[$i][value] = $value;
        }
    }

    return $post;
}

function getPath( $parent, $link=0 ) {
	
    global $db, $site;

    $cat = $db->getRow( 'select id, title, parent from ' . MODULECATEGORIES_TABLE . " where site_key='$site' and id='$parent' and module_key = 'gallery'" );
    if ( $cat ) {
    	
    	if ( $link > 0 ) {
	        $item = '<a href=\''.DOC_ROOT.MODULES_DIR."/gallery/index.php?category=$cat[id]"."'>$cat[title]</a>";
	    }
	    else {
	        $item = $cat[title];
	    }
	    
        $path = getPath( $cat[parent], 1 ).' >> '. $item;

    }
    else {
    	$path = "<a href='".DOC_ROOT.MODULES_DIR."/gallery/index.php'>Index</a>";
    }
    return $path;
}

function displayImage( $id, $category ) {
    global $db, $t, $newWindow, $site, $gallerySettings, $system, $images, $moduleKey;

    $createThumb = gdInstalled() ? getSetting( 'createThumb', 'yes' ) : 'no';
    if ( $createThumb == 'no' ) $newWindow = 'no';

    $nav = new Navigation();
    // total number of items
    $nav->_total = count( $images );

    // number of items to display per page
    $nav->_itemsPerPage = 1;

    // the number of links to show in the navigation bar (in case > 10)
    $nav->_pagesPerScreen = 15;

    // the search vars that we should pass from screen to screen
    $nav->_requestVars = $_GET;

    $nav->_separator = ' | ';

    // the current 'start' index
    $nav->_start = $_REQUEST['start'];

    // the current set that we are in
    $nav->_set = $_REQUEST['set'];

    $image = $db->getRow( 'select id, title, description, created, site_key, use_cat_price, price, quantity, man_id, if( length(img_thumb) = 0, 1, 0) as is_empty from '.IMAGES_TABLE." where id='$id'" );
    
    if ( $image['use_cat_price'] ) {
    	$image['price'] = $db->getOne( 'select value from '. MODULESETTINGS_TABLE." where site_key='$site' and module_key='$moduleKey' and cat_id='$category' and name ='defaultPrice'" );
    	$image['cat_id'] = $category;
    }
    
    $image['attributes'] = getAttributes( $image['id'] );
    // get additional pricing options
    
    $options = $db->getAll( 'select * from '. ATTRPRICING_TABLE." where product_id='$image[id]'" );
    if ( $options )
    foreach ( $options as $idx=>$option ) {
    	$attr = $db->getAll( 'select *, a.id as id, v.value1 as value from '.ATTRIBUTES_TABLE." a left join " . ATTRPRICEVALUES_TABLE." v on v.attr_id=a.id where v.price_id='$option[id]' and v.site_key='$site'" );
    	$options[$idx]['price'] = getPriceValue( $option, $image['price'] );
    	$options[$idx]['attributes'] = $image['attributes'];
    	foreach ( $attr as $aidx=>$a ) {
    		$options[$idx]['attributes'][$a['id']] = $a;
    	}
    }
    $t->assign( 'pricingOptions', $options );
    
    $image['price'] = calculatePrice( $image );

    if ( !$image ) {
        $t->assign( 'error', 'The image that you requested could not be found.' );
    }
    
    // if we are in eccommerce mode - add ecommerce attributes
    
    if ( $gallerySettings['useEcommerce'] == 'yes' ) {
    	
    	$iid = $image[id];
    	
	    $attributes = $db->getAll( 'select id, name, type from '. ATTRIBUTES_TABLE." where site_key='$site' and visible=1" );
	    $prAttr = array();
	    foreach ( $attributes as $idx=>$attr ) {

	    	
	    	$value = $db->getOne( 'select value from '.ATTRVALUES_TABLE." where product_id='$iid' and attr_id='$attr[id]'" );
	    	$prAttr[] = array( 'name'=>$attr[name], 'value'=>$value );
	    }
	    
	    $image[attr] = $prAttr;
    	
    }    

    // ------------------------------------------------
    // get fields related to this image and format them
    // ------------------------------------------------
    
    $fields = getFields( 'full' );
    
    $fs = array();
    foreach ( $fields as $num=>$item ) {
    	
		if ( !$item[visible] )
			continue;
			
		$item = fetchField( $item, $image, 'full' );
		$fs[$item[section]][$item[row]][] = $item;
		
		$fs[$item[section]][$item[row]][canDown] = 0;
		//$fs[$item[section]][$item[row]][canUp] = 0;
		
		if ( $item[row] == 1 && count( $fs[$item[section]][$item[row]] ) == 2 ) {
			$fs[$item[section]][$item[row]][canUp] = 0;
		}
		else {
			$fs[$item[section]][$item[row]][canUp] = 1;
			
			//if ( $item[row] != 1 )
				//$fs[$item[section]][$item[row]-1][canDown] = 1;
		}
		
		if ( count( $fs[$item[section]][$item[row]] ) > 3 ) {
			$fs[$item[section]][$item[row]][canDown] = 1;
			//$fs[$item[section]][$item[row]][canUp] = 1;
		}
						
    }
    
    $t->assign( 'fields', $fs );
    
    if ( $createThumb == 'no' ) 
    	$imageType = strtolower( end( explode( '.', $image[name] ) ) );
    	
    $t->assign( 'image', $image );
    $t->assign( 'newWindow', $newWindow=='yes' );
    $t->assign( 'type', $imageType );
    
    if ( $newWindow == 'no' ) 
    	$t->assign( 'navigation', $nav->output() );

	$path = getPath( $category, 1 ) . ' >> ' . $image['title'];

    $title = $image['title'] .' - '. $gallerySettings['galleryName']. ' - ' . $system->settings['title'];
    
    $t->assign( 'galleryPath', $path );
    $t->assign( 'title', $title );
    
}


// $type ::= full | cat_thumb | pr_thumb
function getFields( $type ) {
	
	global $db, $site, $gallerySettings;
	
	$fieldTitles = array( 'title' => 'Title',
						  'description' => 'Description',
						  'items_count' => 'Count Items in Category',
						  'category_title' => 'Category Title',
						  'price' => 'Price',
						  'quantity' => 'Quantity in Stock',
						  'add_to_cart' => 'Add To Cart',
						  'manufacturer' => 'Manufacturer' );
	
	if ( $type == 'cat_thumb' ) 
		$fieldsToDisplay = array( 'items_count', 'category_title' );
	else
		$fieldsToDisplay = array( 'title', 'description' );
	
	if ( $gallerySettings['useEcommerce'] == 'yes' && $type != 'cat_thumb' ) {
		
		$fieldsToDisplay = array_append( $fieldsToDisplay, array( 'price', 'quantity', 'manufacturer', 'add_to_cart' ) );
		
		$attributes = $db->getAll( 'select a.id as a_id, a.name, a._default, d.* from '. ATTRIBUTES_TABLE. " a left join ". DISPLAYOPTIONS_TABLE." d on a.id=d.field_id and d.type='$type' and d.site_key='$site' where a.site_key='$site' and a.visible=1" );
		//print_r( $attributes );
		foreach ( $attributes as $num=>$attr ) {
			if ( $attr[id] == '' ) {
				$row = $db->getOne( 'select max(row) from '. DISPLAYOPTIONS_TABLE." where site_key='$site' and type='$type' and visible=1" );
				$row++;
				$db->query( 'insert into '.DISPLAYOPTIONS_TABLE." (section, field_id, type, row, layout, style, visible, site_key) values ( 'top', '$attr[a_id]', '$type', '$row', '{\$name}: {\$value}', '', '0', '$site') " );
			}
			$fieldsToDisplay[] = $attr[a_id];
		}
	
	}
	$fieldsToDisplay = '\''.implode( '\', \'', $fieldsToDisplay ).'\'';
	
	$items = $db->getAll( 'select 1 as canLeft, 1 as canRight, d.*, a.name from '. DISPLAYOPTIONS_TABLE." d left join ". ATTRIBUTES_TABLE." a on d.field_id=a.id where d.field_id in ($fieldsToDisplay) and d.type='$type' and d.site_key='$site' order by d.visible desc, d.section asc, d.row, d.row_position" );
	foreach ( $items as $num=>$item ) {
		
		if ( in_array( $item['field_id'], array_keys( $fieldTitles ) ) )
			$items[$num]['name'] = $fieldTitles[$item['field_id']];
			
		if ( $num == 0 ) {
			$items[$num]['canLeft'] = 0;
			$prevSection = $items[$num]['section'];
			$prevRow = $items[$num]['row'];
		}
			
		if ( ($items[$num]['section'] != $prevSection || $items[$num]['row'] != $prevRow) && $num!=0 ) {
			$items[$num-1]['canRight'] = 0;
			$items[$num]['canLeft'] = 0;
			$prevSection = $items[$num]['section'];
			$prevRow = $items[$num]['row'];
		}
		
		if ( !$items[$num]['visible'] )
			$items[$num-1]['canRight'] = 0;
	}
	
	$items[count( $items) - 1]['canRight'] = 0;
	
	return $items;

}


function fetchField( $field, &$resource, $type='', $cart_id='' ) {
	
	global $t, $db, $site;
	
	switch( $field[field_id] ) {
		
		case 'title':
			if ( $type != 'full' )
       			$field['layout'] = '<a href="displayImage.php?category='.$resource['cat_id'].'&id='.$resource['id'].'">'.$field['layout'].'</a>';
       		$value = $resource[$field[field_id]];
       		break;
       		
		case 'price':
    		$value = "<div id='price'>" . galleryPrice($resource[$field[field_id]]) . '</div>';
    		break;
    		
		case 'manufacturer':
			$value = $resource[man_id];
			$value = $db->getRow( 'select name, url from '. MANUFACTURERS_TABLE." where id='$value'" );
			if ( $value[url] ) {
				if ( !preg_match( '/^http:\/\//i', $value[url] ) ) $value[url] = 'http://'.$value[url];
				$value = "<a href='".$value[url]."' targt=_blank>".$value[name]."</a>";
			}
			else
           		$value = $value[name];
			break;
			
		case 'add_to_cart':
			$field['layout'] = "<a href=\"cart.php?action=add&id={$resource[id]}&category=$_REQUEST[category]\">". $field['layout'] .'</a>';
			break;
			
		case 'quantity':
		case 'description':
       		$value = $resource[$field[field_id]];
       		break;
       		
		default:
			$iid = $resource[id];
			$sql = 
				'SELECT '.
				'a.id, a.name, a.type, if (v.use_default, a._default, v.value) as value, p.id as pricing '.
				'FROM '.ATTRIBUTES_TABLE." a ".
				'LEFT JOIN '. ATTRVALUES_TABLE." v on a.id=v.attr_id and v.product_id='$iid' ".
				'LEFT JOIN '. ATTRPRICING_TABLE." p on p.product_id='$iid' ".
				"WHERE a.id='$field[field_id]'";
			$row = $db->getRow( $sql );
			
			$field['name'] = $row['name'];
			
			if ( $type != 'cart' ) {
			
			$value = !strlen( $row['value'] ) ? 'n/a' : $row['value'];
			
			if ( preg_match( '/^list_(.*)/', $row['type'], $m ) ) {
				$attr_id = $row['id'];
				$values = unserialize( $value );
				if ( @count($values) ) {
					$titles = array();
					foreach( $values as $v=>$val ) {
						$titles[] = $db->getOne( 'select label from '. LISTITEMS_TABLE." where data='$val' and list_key='$m[1]' and site_key='$site'" );
					}
					if ( @count( $titles ) > 1 ) {
						require_once $t->_get_plugin_filepath('function', 'html_options');
						$params = array( 'values'=>$values, 'output'=>$titles, 'selected'=>$resource['attributes'][$field[field_id]] );
						$value = smarty_function_html_options($params, $t);
						
						//if ( $type == 'cart' ) {
							//$loadFunct = "doLoad('$cart_id','$attr_id',this.value)";
						//}
						//else
							$loadFunct = "doLoad('$iid','$resource[cat_id]','$attr_id',this.value)";
						
						//if ( $row['pricing'] )
							$onchange = "onchange=\"javascript:$loadFunct;\"";
							
						$value = "<select $onchange>" . $value . "</select>";
					}
					else
						$value = $titles[0];
				}
				else
					$value = "n/a";
			}
			}
			else
				$value = !strlen($resource['attributes'][$field[field_id]]['value']) ? 'n/a' : $resource['attributes'][$field[field_id]]['value'];
			break;
	}
	
	$field[title] = str_replace( array( '{$name}', '{$value}' ), array( $field['name'], $value ), $field['layout'] );
	
	return $field;

}


function fetchCartAttributes( $cart_id, &$item ) {
	global $db, $gallerySettings, $site;
	
	$attributes = $gallerySettings['attributesList'];
	
	$static = array(
		'title' => 'Title',
		'price' => 'Price',
		'description' => 'Description',
		'quantity' => 'Quantity',
	);
	
	$out = array();
	
	if ( $attributes )
	foreach ( $attributes as $idx=>$att ) {
		
		$f['layout'] = '{$value}';
		if ( preg_match( '/^attr_([0-9]+)$/', $att, $matches ) ){
			$f['field_id'] = $matches[1];
		}
		else {
			$f['field_id'] = $att;
		}
		$rez = fetchField( $f, $item, 'cart', $cart_id );
		
		if ( @in_array( $att, array_keys( $static )) ) {
			$rez['name'] = $static[$att];
		}
		
		$out[$att] = array( 'title'=>$rez['name'], 'value'=>$rez['title'] );
	}
	return $out;
}


/**
 * $discounts = array( thresold, percent )
 * return - array( disountPrice, discountPercent )
 */
function calculateDiscount( $ammount, $discounts ) {
	
	foreach ( $discounts as $idx=>$item ) {
		if ( $ammount >= $item[0] && $item[1] > $percent ) {
			$percent = $item[1];
			$price = $ammount*$percent/100;
		}
	}
	
	return array( intval($price), intval($percent) );
}


function getCartContents() {
	
	global $db, $site, $moduleKey, $t;
	
	$cartContents = array();
	
	$_SESSION['cart']['totalPrice'] = 0;
	
	if ( is_array( $_SESSION['cart']['items'] ) && count( $_SESSION['cart']['items'] ) ) {
	foreach ( $_SESSION['cart']['items'] as $cart_id=>$cart_item ) {
		
		$count = $cart_item['count'];
		$itemId = $cart_item['item']['id'];
		
		$item = $db->getRow( 'select id, title, price, use_cat_price from '. IMAGES_TABLE." where id='$itemId'" );
		
		if ( $item['use_cat_price'] ) {
			$item['price'] = $db->getOne( 'select value from '. MODULESETTINGS_TABLE." where site_key='$site' and module_key='$moduleKey' and cat_id='{$cart_item['item']['cat_id']}' and name ='defaultPrice'" );
		}
		
		$item['cat_id'] = $cart_item['item']['cat_id'];
		$item['attributes'] = @unserialize( $cart_item['item']['attributes'] );
		$item['price'] = calculatePrice( $item );
		
		require_once $t->_get_plugin_filepath('function', 'imgsrc');
		$params = array( 'table'=>IMAGES_TABLE, 'field'=>'img_thumb', 'id'=>$itemId );
		
		$cartContents[] = array( 
			'id'         => $itemId, 
			'cart_id'    => $cart_id,
			'count'      => $count, 
			'price'      => $count*$item['price'], 
			'title'      => $item['title'],
			'thumbnail'  => smarty_function_imgsrc($params, $t),
			'cat_id'     => $item['cat_id'],
			'attributes' => fetchCartAttributes( $cart_id, $item )
		);

		$_SESSION['cart']['totalPrice'] += $count*$item['price'];
	}
	}
	
	return $cartContents;
}

function getAttrValue( $type, $prefix ) {
	if ( $type == 'date' ) {
		$value = $_POST[$prefix.'_Year'].'-'.$_POST[$prefix.'_Month'].'-'.$_POST[$prefix.'_Day'];
	}
	elseif ( preg_match( '/^list_(.*)/', $type, $m ) ) {
		$value = serialize( $_POST[$prefix] );
	}
	else {
		$value = $_POST[$prefix];
	}
	
	return $value;
}

function galleryPrice( $string ) {
	global $gallerySettings;
	
	switch ( $gallerySettings['currency'] ) {
		case 'EUR':
			$sign = '&euro;';
			break;
			
		case 'GBP':
			$sign = '&pound;';
			break;
			
		case 'USD':
		default:
			$sign = '$';
			break;
	}
	
	$decimals = $gallerySettings['priceFormat'][0];
	$dec_point = $gallerySettings['priceFormat'][1];
	$thousands_sep = $gallerySettings['priceFormat'][2];
	
	return $sign .number_format( $string, $decimals, $dec_point, $thousands_sep );
}


// used for price calculations
function getAttributes( $itemId ) {
	global $db, $site;
	$sql = 
		'SELECT '.
		'a.id as id, v.id as v_id, a.name, a.type, if (v.use_default, a._default, v.value) as value '.
		'FROM '.ATTRIBUTES_TABLE." a ".
		'LEFT JOIN '. ATTRVALUES_TABLE." v on a.id=v.attr_id and v.product_id='$itemId' ".
		"WHERE a.visible=1 and v.product_id='$itemId' and v.site_key='$site'";
	
	$attributes = $db->getAll( $sql );
	
	$out = array();
	
	if ( $attributes )
	foreach ( $attributes as $idx=>$item ) {
		$out[$item['id']] = $item;
		if ( preg_match( '/^list/', $item['type'] ) ) {
			$v = @unserialize( $item['value'] );
			$out[$item['id']]['value'] = $v[0];
		}
	}
	
	return $out;
}


// calculates item price based on attributes

function calculatePrice( &$item ) {
	
	global $db, $site;
	
	$resultPrice = $basePrice = $item['price'];
	
	$pricing = $db->getAll( 'select * from '. ATTRPRICING_TABLE." where product_id='$item[id]' and site_key='$site'" );
	
	foreach ( $pricing as $pidx=>$price ) {
		
		$match = true;
		
		if ( $item['attributes'] )
		foreach( $item['attributes'] as $attr_id=>$attr ) {
			
			$values = $db->getRow( 'select value1, value2 from '. ATTRPRICEVALUES_TABLE." where price_id='$price[id]' and attr_id='$attr_id'" );
			
			if ( !$values )
				continue;
				
			$value1 = $values['value1'];
			$value2 = $values['value2'];
			$value = $attr['value'];
		
			switch( $attr['type'] ) {
				case 'number':
/*					if ( strlen( $value1 ) )
						$match &= $value >= $value1;
						
					if ( strlen( $value2 ) )
						$match &= $value <= $value2;
					break;
*/					
				case 'single-text':
				case 'multi-text':
				case 'date':
				default:
					$match &= $value == $value1;
					break;
			}
		}
		
		if ( $match ) {
			$resultPrice = getPriceValue( $price, $basePrice );
		}
	}
	
	return $resultPrice;
	
}


function getPriceValue( $pricingOption, $basePrice ) {
	
	$resultPrice = $basePrice;
	
	if ( $pricingOption['type'] == 'fixed' )
		$resultPrice = $pricingOption['fixed_price'];
	else {
		
		$deltaPrice = $pricingOption['delta_price'];
		
		if ( $pricingOption['delta_item'] == '%' )
			$deltaPrice = $deltaPrice / 100 * $basePrice;
			
		if ( $pricingOption['delta_type'] == 'increase' )
			$resultPrice += $deltaPrice;
		else
			$resultPrice -= $deltaPrice;
	}
	
	return $resultPrice;
}


function getCategorySettings( $cat_id ) {
	
	global $db, $moduleKey, $site;
					
	$aSet = $db->getAll( 'select * from '. MODULESETTINGS_TABLE." where site_key='$site' and module_key='$moduleKey' and cat_id='$cat_id' and name !='catImage'" );
	
	$set = array();
	foreach( $aSet as $num=>$row ) {
		$set[$row[name]] = $row[value];
	}
	
	return $set;
}

?>