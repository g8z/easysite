<?php

include_once( '../config.php' );
include_once( INCLUDE_DIR . 'internal/class.File.php' );

$table = MODULESETTINGS_TABLE;

$cat_id = intval( $_REQUEST['cat_id'] );

// default settings for this module

$defaultSettings = array(
    'meta_keywords'    => array( 'Meta Keywords', 'text', '' ),
    'meta_desc' => array( 'Meta Description', 'textarea', '' ),
	'useEcommerce'	   => array( 'Use E-Commerce mode', 'boolean', 'no' ),
	'createThumb'		   => array( 'Auto Create Thumbnails?<br /><small>Note: GD library must be installed</small>', 'isThumb', 'yes' ),
	//'newWindow'			 => array( 'Open Full Image in New Window', 'boolean', 'no' ),
	'imageWidth'			=> array( 'Image Max Width', 'number', '800' ),
	'imageHeight'		   => array( 'Image Max Height', 'number', '600' ),
	'thumbnailWidth'		=> array( 'Fixed Thumbnail Width<br /><small>leave blank for no width restriction; only used when thumbnail auto-creation is turned off</small>', 'number', '' ),
	'thumbnailHeight'	   => array( 'Fixed Thumbnail Height<br /><small>leave blank for no height restriction; only used when thumbnail auto-creation is turned off</small>', 'number', '' ),
	'gridWidth'			 => array( 'Thumbnail Grid Width', 'number', '5' ),
	'gridHeight'			=> array( 'Thumbnail Grid Height', 'number', '5' ),
	'galleryName'		   => array( 'Gallery Name/Title', 'text', 'Image Gallery' ),
	'align'				 => array( 'Horizontal Image Alignment', 'align', 'center' ),
	'showPath'			  => array( 'Show Path Bar', 'boolean', 'yes' ),
	'showPagination'		=> array( 'Show Pagination Bar', 'boolean', 'yes' ),
	'showHeader'			=> array( 'Show Page Header', 'boolean', 'yes' ),
	'fullTableWidth'		=> array( 'Full Image Table Width', 'number', '100%' ),
	//'fullTableSpacing'	  => array( 'Full Image Table Spasing', 'number', '2' ),
	'thumbTableWidth'	   => array( 'Thumbnail Image Table Width', 'number', '100%' ),
	'gridTableWidth'	   => array( 'Grid Table Width', 'number', '100%' ),
	'thumbVAlign'   		=> array( 'Thumbnail Image Vertical Align', 'valign', 'middle' ),
	//'showName'			  => array( 'Show Image Name', 'boolean', 'yes' ),
	//'showDesc'			  => array( 'Show Image Description', 'boolean', 'yes' ),
	//'showTitle'			 => array( 'Show Image Title', 'boolean', 'yes' ),
	//'showCount'			   => array( 'Show Counter Bar', 'boolean', 'yes' ),
	//'titlePosition'		 => array( 'Title Vertical Position', 'valign', 'bottom' ),
	'tImgBorderSize'		=> array( 'Thumbnail Image Border Size', 'number', '0' ),
	'tImgBorderColor'	   => array( 'Thumbnail Image Border Color', 'color', '#000000' ),
	'tImgAlign'	   		=> array( 'Thumbnail Image Align', 'align', 'center' ),
	'cImgBorderSize'		=> array( 'Category Image Border Size', 'number', '0' ),
	'cImgBorderColor'	   => array( 'Category Image Border Color', 'color', '#000000' ),
	'cImgAlign'	   		=> array( 'Category Image Align', 'align', 'center' ),
	'fImgBorderSize'		=> array( 'Full Image Border Size', 'number', '0' ),
	'fImgBorderColor'	   => array( 'Full Image Border Color', 'color', '#000000' ),
	'fImgAlign'	  		=> array( 'Full Image Align', 'align', 'center' ),
	'defImage'			  => array( 'Category Image', 'image', '' ),
	'catImageWidth' => array( 'Category Image Width<br /><small>Set to 0 to use original or proportional to width</small>', 'number', '' ),
	'catImageHeight' => array( 'Category Image Height<br /><small>Set to 0 to use original or proportional to height</small>', 'number', '' ),
	'tableBorderSize' => array( 'Thumbnail Table Border Size', 'number', '0' ),
	'tableBorderColor' => array( 'Thumbnail Table Border Color', 'color', '' ),
	'tableBGColor' => array( 'Thumbnail Table Background Color', 'color', '' ),
	'tableBetweenSize' => array( 'Thumbnail Table Cells Separation Width', 'number', '0' ),
	'tableBetweenColor' => array( 'Thumbnail Table Cells Separation Color', 'color', '' ),
	'noImageThumb' => array( 'Thumbnail No-Image', 'image', '' ),
	'noImageFull' => array( 'Full No-Image', 'image', '' ),
	'emptyCategoryMessage' => array( 'Empty Category Message', 'textarea', 'There are currently no items or categories in the store.' ),
);

$categorySettings = array(
	//'description' => array( 'Category Description', 'textarea', '' ),
    'cat_meta_keywords'    => array( 'Meta Keywords', 'text', '' ),
    'cat_meta_desc' => array( 'Meta Description', 'textarea', '' ),
	'useOverImage' => array( 'Use uploaded image?', 'boolean', 'no' ),
	'catImage' => array( 'Category Image', 'image', '' ),
	'useGalleryTitle' => array( 'Use Gallery Title As Page Header', 'boolean', 'no' ),
	'catPageHeader' => array( 'Page Header<br /><small>use {$title} for category title</small>', 'text', '{$title}' ),
	'defaultPrice' => array( 'Default Price for Items<br /><small>numeric decimal value without symbols</small>', 'number', '0.00' ),
);

$ecommerceSettings = array(
	'title1' => array( 'Payment Processing', 'title', '' ),
	'paymentGateway' => array( 'Payment Gateway', 'gateway', '' ),
	'emailReceipt' => array( 'E-Mail Receipt', 'boolean', 'yes' ),
	//'processingScript' => array( 'Processing Script', 'text', '' ),

	'title2' => array( 'Storefront', 'title', '' ),
	//'LaddToCart' => array( 'Add To Cart', 'text', 'Add To Cart' ),
	'Lcheckout' => array( 'Checkout Title', 'text', 'Checkout' ),
	'LcheckoutDesc' => array( 'Checkout Description', 'textarea', 'To finalize your order, please provide some information about yourself, including your shipping address and method of payment. Thanks for shopping with us!' ),
	'LitemsNumber' => array( 'Number Of Items', 'text', 'View Cart: ({$count} items)' ),
	'currency' => array( 'Currency', 'currency', 'USD' ),
	'priceFormat' => array( 'Price Format', 'price', '' ),

	'title4' => array( 'Calculations', 'title', '' ),
	'tax' => array( 'Tax %:', 'number', '' ),
	'discountPrc1' => array( 'Discount, level 1 %:', 'number', '' ),
	'discountPrc2' => array( 'Discount, level 2 %:', 'number', '' ),
	'discountPrc3' => array( 'Discount, level 3 %:', 'number', '' ),
	'discountThr1' => array( 'Discount, level 1 thresold:', 'number', '' ),
	'discountThr2' => array( 'Discount, level 2 thresold:', 'number', '' ),
	'discountThr3' => array( 'Discount, level 3 thresold:', 'number', '' ),
	'shipping' => array( 'Shipping Options:', 'shipping', '' ),
);

$shoppingCartSettings = array(
	'mainTitle' => array( 'Main Title', 'text', 'Your Shopping Cart' ),
	'headerRowColor' => array( 'Header Row BG Color', 'color', '#333333' ),
	'headerTextColor' => array( 'Header Row Text Color', 'color', '#EEEEEE' ),
	'oddRowColor' => array( 'Odd Row BG Color', 'color', '#EEEEFF' ),
	'evenRowColor' => array( 'Even Row BG Color', 'color', '#EEEEEE' ),
	'cellSpacing' => array( 'Cell Spacing', 'number', '2' ),
	'cellPadding' => array( 'Cell Padding', 'number', '2' ),
	'removeTitle' => array( '"Remove" Label', 'text', 'Remove' ),
	'itemTitle' => array( '"Item" Label', 'text', 'Item' ),
	'quantityTitle' => array( '"Quantity" Label', 'text', 'Quantity' ),
	'priceTitle' => array( '"Price" Label', 'text', 'Price' ),
	'subtotalTitle' => array( '"Sub-Total" Label', 'text', 'Sub-Total:' ),
	'discountTitle' => array( '"Discount" Label', 'text', 'Discount ($value%):' ),
	'taxTitle' => array( '"Tax" Label', 'text', 'Tax ($value%):' ),
	'grandTotalTitle' => array( '"Grand Total" Label', 'text', 'Grand Total:' ),
	'attributesTitle' => array( '"Attributes" Label', 'text', 'Attributes' ),
	'showSubtotal' => array( 'Show Sub-Total', 'boolean', 'yes' ),
	'showDiscount' => array( 'Show Discount', 'boolean', 'yes' ),
	'showTax' => array( 'Show Tax', 'boolean', 'yes' ),
	'showThumbnail' => array( 'Show Thumbnail', 'boolean', 'yes' ),
	'showTitle' => array( 'Show Product Title', 'boolean', 'yes' ),
	'titleTPosition' => array( 'Title Position', 'title_position', 'below' ),
	'titleAlign' => array( 'Title Align', 'align', 'left' ),
	'titleLink' => array( 'Link Title To Product Description', 'boolean', 'yes' ),
	'titleWidth' => array( 'Title Column Width', 'number', '100' ),
	'showAttributes' => array( 'Show Product Attributes', 'boolean', 'no' ),
	'attributesList' => array( 'Attributes To Display', 'attributes', '' ),
	'styleAttributes' => array( 'Attributes Style', 'style', 'normal' ),
	'styleSubtotal' => array( 'Sub-Total Style', 'style', 'normal' ),
	'styleDiscount' => array( 'Discount Style', 'style', 'normal' ),
	'styleTax' => array( 'Tax Style', 'style', 'normal' ),
	'styleGrandtotal' => array( 'Grand Total Style', 'style', 'normal' ),
);

$emailReceiptSettings = array(
	'mail_cc' => array( 'CC E-Mail to', 'text', '' ),
	'mail_subject' => array( 'E-Mail Subject', 'text', 'Thanks for your order from {$site}' ),
	'mail_format' => array( 'Mail Format', 'mail_format', 'no' ),
	'mail_body' => array( 'Content of the E-Mail', 'mail_content', '' ),
);

$paypalSettings = array(
	'currentGateway' => array( 'Current Gateway', 'gateway', '' ),
	'paypalAccount' => array( 'PayPal e-mail', 'text', '' ),
	'returnPage' => array( 'Return (Thanks) Page', 'page', '' ),
	'cancelPage' => array( 'Cancellation Page', 'page', '' ),
	'allowUserNotes' => array( 'Allow user-inputted notes?', 'boolean', 'no' ),
	'itemName' => array( 'Item Name', 'text', 'Your {$site} purchase' ),
);

$checkoutSettings = array(
	'currentGateway' => array( 'Current Gateway', 'gateway', '' ),
	'twocheckoutAccount' => array( 'Account Number', 'number', '' ),
	'secretWord' => array( 'Secret Word', 'text', '' ),
	'passUser' => array( 'Pass User Info from Checkout Page?', 'boolean', 'yes' ),
	'successPage' => array( 'Success Return Page', 'page', '' ),
	'errorPage' => array( 'Error/Pending Return Page', 'page', '' ),
	'demoMode' => array( 'Use Demo Mode?', 'boolean', 'no' ),
);

$pricingListSettings = array(
	'showCaption' => array( 'Show Caption', 'boolean', 'yes' ),
	'caption' => array( 'Caption', 'text', 'Available Product Configurations:' ),
	'captionStyle' => array( 'Caption Style', 'style', 'normal' ),
	'PLaddToCart' => array( '"Add To Cart" Label', 'text', 'Add To Cart' ),
	'PSaddToCart' => array( '"Add To Cart" Style', 'style', 'normal' ),
	'PSprice' => array( 'Price Style', 'style', 'normal' ),
	'attributeLabelsStyle' => array( 'Attribute Labels Style', 'style', 'normal' ),
	'attributeValuesStyle' => array( 'Attribute Values Style', 'style', 'normal' ),
	'separationHeight' => array( 'Separation Line Height', 'number', '2' ),
	'separationColor' => array( 'Separation Line Color', 'color', '#888888' ),
	'visibleFields' => array( 'Show Attributes :', 'pricingAttributes', array('all') ),
);

// --------------------------------------
// determine what settings we are editing
// --------------------------------------

if ( $cat_id ) {

	// editing category settigns
	$structure = $categorySettings;
	$resourceWhere = "cat_id='$cat_id'";

	$imagePaths['catImage'] = $gallerySettings['catImagePath'];

	$t->assign( 'settingsTitle', 'Product Gallery Category Settings' );

}
elseif ( $mode == 'ecommerce' ) {
	$structure = $ecommerceSettings;
	$resourceWhere = '1';

	$t->assign( 'settingsTitle', 'E-Commerce Settings' );
	$t->assign( 'settingsDesc', 'Your Image Gallery can be used as an e-commerce store front. When e-commerce mode is enabled, additional options like "Add to Cart" and "Checkout" are instantly added to every page of the gallery. You may also set e-commerce options like price and quantity for each item, and link these to a payment gateway like PayPal, 2Checkout.com, or Authorize.Net.' );
}
elseif ( $mode == 'gateway' ) {

	$currentGateway = $_POST['currentGateway'] ? $_POST['currentGateway'] : $gallerySettings['currentGateway'];

	switch( $currentGateway ) {
		case '2checkout':
			$structure = $checkoutSettings;
			break;
		case 'paypal':
		default:
			$structure = $paypalSettings;
			break;
	}

	$resourceWhere = '1';

	$t->assign( 'settingsTitle', 'Gateway Settings' );
	$t->assign( 'settingsDesc', 'Your payment gateway provides for many variables which can be passed from your online store. These variables can then be passed back to your web server, thus keeping a record in your own database of the purchases and customer information. Please use the form below to specify the values which should be passed to the gateway.' );

}
elseif ( $mode == 'email_receipt' ) {
	$structure = $emailReceiptSettings;
	$resourceWhere = '1';

	$t->assign( 'firstWidth', '20%' );

	$t->assign( 'settingsTitle', 'E-Mail Receipt Settings' );
	$t->assign( 'settingsDesc', 'When a user makes a purchase from your online store, an email will be automatically sent by the gateway processing script, confirming the user\'s order and providing detailed information. You may use this template to customize the look and feel of this email. <br /><br /> The following built-in variables are available for you to use:<br />{$site} = the name of your website, as set in the global site settings<br />{$order_num} = auto-generated order number<br />{$order_amt} = the total amount of order<br />{$ship_method} = shipping method<br />{$ship_period} = the period required for shipping<br />{$ship_cost} = shipping cost<br />{$first_name} = the first name of the customer<br />{$last_name} = the last name of the customer<br />{$date} = the date of the order' );
}
elseif ( $mode == 'shopping_cart' ) {
	$structure = $shoppingCartSettings;
	$resourceWhere = '1';

	$t->assign( 'settingsTitle', 'Shopping Cart Settings' );

	$t->assign( 'titlePositionCombo', array( 'above'=>'Above Thumbnail', 'below'=>'Below Thumbail' ));

	$attributes = array(
		'title' => 'Title',
		'description' => 'Description',
		'price' => 'Price',
		'quantity' => 'Quantity in Stock',
	);

	$attr = $db->getAll( 'select id, name from '.ATTRIBUTES_TABLE ." where site_key='$site'" );
	foreach ( $attr as $idx=>$item ) {
		$attributes['attr_'.$item['id']] = $item['name'];
	}
	$t->assign( 'attributes', $attributes );
}
elseif ( $mode == 'pricingList' ) {
	$structure = $pricingListSettings;
	$resourceWhere = '1';

	$pricingAttributes = array(
		'all' => 'All',
		'quantity' => 'Quantity in Stock',
	);

	$attr = $db->getAll( 'select id, name from '.ATTRIBUTES_TABLE ." where site_key='$site'" );
	foreach ( $attr as $idx=>$item ) {
		$pricingAttributes[$item['id']] = $item['name'];
	}
	$t->assign( 'pricingAttributes', $pricingAttributes );

	$t->assign( 'settingsTitle', 'Product Configurations Output' );
}
else {

	// editing global module settings
	$structure = $defaultSettings;
	$resourceWhere = '1';

	$t->assign( 'settingsTitle', 'Image Gallery Settings' );

}

if ( $formIsSubmitted ) {

	// update the settings in the $table table

	//$db->query( "delete from $table where site_key = '$site' and module_key = '$moduleKey'" );

	foreach( $structure as $index => $row ) {

		$isFile = false;

		if ( $_POST['remove_'.$index] ) {
   			$id = $db->getOne( 'select id from '. MODULESETTINGS_TABLE." where name='$index' and module_key='$moduleKey'" );
   			$db->query( 'delete from '. MODULESETTINGS_TABLE." where (name='$index' or name='{$index}Path') and module_key='$moduleKey'" );
			$c->_table = MODULESETTINGS_TABLE;
			$c->_field = 'value';
			$c->_id = $id;
			$c->remove();
			continue;
		}

		if ( $index == 'attributesList' ) {
			$val = @serialize( $_POST['attributes'] );
		}
		elseif ( $index == 'visibleFields' ) {
			$val = @serialize( $_POST['pricingAttributes'] );
		}
		elseif ( array_key_exists( $index, $_POST ) ) {
				$val = $_POST[$index];
		}
		else {
			$file = new File( $index );
			if ( $file->isUploaded() ) {
				$val = $file->getContent();
				$isFile = true;

				$db->query( 'delete from '. MODULESETTINGS_TABLE." where name='{$index}Path' and module_key='$moduleKey'" );
				$db->query( 'insert into '. MODULESETTINGS_TABLE." (name, value, cat_id, site_key, module_key) values ('{$index}Path', '{$file->userName}', '$cat_id', '$site', '$moduleKey')" );
				$imagePaths[$index] = $file->userName;
				$file->delete();
				unset( $file );
			}
			else {
				unset( $file );
				continue;
			}
		}

		$id = $db->getOne( "select id from $table where name='$index' and module_key='$moduleKey' and site_key='$site' and $resourceWhere" );

		if ( $id ) {
			$db->query( "update $table set value='$val' where name='$index' and module_key='$moduleKey' and site_key='$site' and $resourceWhere" );
		}
		else {

			$db->query( "insert into $table (
				name,
				value,
				cat_id,
				site_key,
				module_key
				) values (
				'$index',
				'$val',
				'$cat_id',
				'$site',
				'$moduleKey'
				)" );

			$id = $db->getOne( "select max(id) from $table" );
		}

		if ( $isFile ) {

			// remove previously cached images
			$c->_table = MODULESETTINGS_TABLE;
			$c->_field = 'value';
			$c->_id = $id;
			$c->remove();
		}
	}
}

$t->assign( 'imagePaths', $imagePaths );

$data = $db->getAll( "select * from $table where site_key = '$site' and module_key = '$moduleKey' and $resourceWhere" );
$data2 = array();

foreach( $data as $index => $row ) {
	if ( $row['name'] == 'attributesList' || $row['name'] == 'visibleFields' )
		$row['value'] = @unserialize( $row['value'] );
	$data2[$row[name]] = $row;
}


if ( $gdInstalled = gdInstalled() ) {
	$isThumb = array( 'yes', 'no' );
} else {
	$isThumb = array( 'no' );
}

$hasAccess = hasAdminAccess( 'cm_module' ) && hasAdminAccess( 'cm_'.$moduleKey ) && hasAdminAccess( 'cm_'.$moduleKey.'_edit_settings' );
$t->assign( 'hasAccess', $hasAccess );

$styleList = $system->getStyleList();
$t->assign( 'styleList', $styleList );

$t->assign( 'booleanCombo', array( 'yes', 'no' ) );

$t->assign( 'mformatValues', array( 'system', 'text', 'html' ) );
$t->assign( 'mformatTitles', array( 'Use System Defaults', 'Text', 'HTML' ) );

$t->assign( 'alignCombo', array( 'left', 'center', 'right' ) );
$t->assign( 'valignCombo', array( 'top', 'middle', 'bottom' ) );
$t->assign( 'gdInstalled', $gdInstalled );
$t->assign( 'isThumb', $isThumb );
$t->assign( 'positionCombo', array( 'above', 'below' ) );

$pageValues = array();
$pageTitles = array();
$pages = $db->getAll( 'select id, title from '. PAGES_TABLE." where site_key='$site' order by title" );
foreach( $pages as $idx=>$page ) {
	$pageValues[] = $page[id];
	$pageTitles[] = $page[title];
}

$t->assign( 'pageValues', $pageValues );
$t->assign( 'pageTitles', $pageTitles );

$t->assign( 'gatewayTitles', array( 'PayPal', '2CheckOut' ) );
$t->assign( 'gatewayValues', array( 'paypal', '2checkout' ) );

$t->assign( 'currencyCombo', array( 'USD', 'EUR', 'GBP' ) );

$shipValues = array();
$shipTitles = array();
$ships = $db->getAll( 'select id, name, price from '. SHIPPING_TABLE." where site_key='$site' order by name" );
foreach( $ships as $idx=>$ship ) {
	$shipValues[] = $ship[id];
	$shipTitles[] = $ship[name].' ('.$ship[price].')';
}

$t->assign( 'shipValues', $shipValues );
$t->assign( 'shipTitles', $shipTitles );


$t->assign( 'priceValues', array( '2.,', '2. ', '2.', '0. ', '0', '0.,' ) );
$t->assign( 'priceTitles', array( '15,999.99', '15 999.99', '15999.99', '15 999', '15999', '15,999' ) );

$t->assign( 'data', $data2);

$t->assign( 'defaultSettings', $structure );


// shared 'settings' template
$t->assign( 'bodyTemplate', 'modules/gallery/manage/settings.tpl' );

// display the main template, with body content embedded


$gallerySettings = getAllSettings();

$t->assign( 'gallery', $gallerySettings );

$session->updateLocation( 'gallery_settings', 'Edit Settings/Options' );
include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );

?>