{if $newWindow}
<html>
<head>
<title>{if $image.title}{$image.title}{else}{$gallery.galleryName|default:"Image Gallery"}{/if}</title>
<style>
{literal}
    body { margin-left: 0px; margin-top: 0px; margin-bottom: 0px; margin-right: 0px;}
{/literal}
</style>
</head>
<body>
{/if}

<script language="JavaScript" src="{$docroot}libs/JSHttpRequest/JSHttpRequest.js"></script>

{literal}
<script language="JavaScript">
<!--

function doLoad(id, category, attr_id, value) {
var div_id = 'price';

var req = new JSHttpRequest();
req.onreadystatechange = function() {
  if (req.readyState == 4) {
    if ( document.getElementById(div_id) )
    	document.getElementById(div_id).innerHTML = req.responseJS.price;
    document.getElementById('visible').style.border="1px";
    document.getElementById('visible').style.border='0px';
	colorDiv( div_id, 'red', 'black', 150, 6 );
  }
}
req.caching = false;
req.open('GET', '{/literal}{$docroot}{literal}modules/gallery/helpers/changeAttribute.php', true);
req.send({ id: id, category:category, attr_id:attr_id, value:value });
}


function colorDiv( div_id, clr1, clr2, delay, c ) {
	
	var tagDiv = document.getElementById(div_id);
	
	if ( c ) {
		tagDiv.style.color = clr1;
		c--;
	}
	
	setTimeout( "colorDiv( '"+div_id+"', '"+ clr2+"', '"+ clr1 + "', " + delay + ", " + c + ")", delay );
} 
-->
</script>
{/literal}

{if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery}
	{literal}
	<script language="javascript">
	<!--
	
	function submitLink( action ) {
	    document.mainForm.galleryAction.value = action;   
	    document.mainForm.submit();
	    return false;
	}
	
	-->
	</script>
	{/literal}

	<p class="normal">
	[ <a href="manage/index.php">Gallery Management</a> ] 
	
	[ <a href='manage/editDisplayOptions.php'" title="">Display Options</a> ]<br />
	
	{if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery and $smarty.session.cm_auth.cm_gallery_edit_images}
	[ <a href="#" onclick="javascript: submitLink( 'edit_image_{$image.id}' );">Edit Item</a> ]
	{/if}
	
	{if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery and $smarty.session.cm_auth.cm_gallery_delete_images}
	[ <a href="#" onclick="javascript: if ( confirm( 'Do you really want to delete this image?' ) ) submitLink( 'delete_image_{$image.id}' );">Delete Item</a> ] 
	{/if}
	
	</p>

    <form method=post name=mainForm>

    {foreach from=$prevPost item=post}
    <input type=hidden name="{$post.name}" value="{$post.value}">
    {/foreach}

    
    <input type=hidden name=galleryAction value=''>
    </form>
    
{/if}


{if $gallery.showPath|default:"yes" eq 'yes' || $gallery.useEcommerce eq 'yes'}
<p>
<table class="normal" width="100%" cellpadding="0" cellspacing="0">
<tr><td align="left" class="normal">{if $gallery.showPath|default:"yes" eq 'yes'}{$galleryPath}{else}&nbsp;{/if}</td>
{if $gallery.useEcommerce eq 'yes'}
	<td align="right">{$cartLabel}</td>
{/if}
</tr>
</table>
</p>

{/if}

{if $image}

{if !$newWindow}
	{if $gallery.showPagination|default:"yes" eq 'yes'}
	<span class="normal">{$navigation}</span>
	{else}
	<br />
	{/if}
{/if}

<table width="{$gallery.fullTableWidth|default:'100%'}" align=center class=normal cellpadding=2 cellspacing=0 border="0">

	{foreach item=row from=$fields.top}
	<tr><td>
		<table class=normal width="100%" height="100%"><tr>
		{foreach item=field from=$row}
		{if $field|is_array}
			<td align="{$field.align}" class="{$field.style|default:'normal'}">
			{$field.title}
			</td>
		{/if}
		{/foreach}
		</tr></table>
	</td></tr>
	{/foreach}  

	<tr><td align="center" width="100%">
	
	<table class="normal" border=0 width="100%" cellpadding="1" cellspacing="1">
	<tr>
	
	{if $fields.left}
		<td width=25%>
		<table class="normal" border=0 width="100%" height="100%">
		{foreach item=row from=$fields.left}
		<tr><td>
			<table class=normal width="100%" height="100%"><tr>
			{foreach item=field from=$row}
			{if $field|is_array}
				<td align="{$field.align}" class="{$field.style|default:'normal'}">
				{$field.title}
				</td>
			{/if}
			{/foreach}
			</tr></table>
		</td></tr>
		{/foreach} 
		</table>
		</td>
	{/if}
		
	<td align="{$gallery.fImgAlign|default:'center'}"><img style='border: {$gallery.fImgBorderSize|default:"0"}px solid {$gallery.fImgBorderColor|default:"#000000"};' src="{if $image.is_empty neq 1}{imgsrc table=$smarty.const.IMAGES_TABLE field=img_large id=$image.id}{else}{imgsrc table=$smarty.const.MODULESETTINGS_TABLE field=value id=$setImages.noImageFull}{/if} {if $createThumb eq 'no'} width="{$gallery.imageWidth|default:800}"{/if}"></td>

	{if $fields.right}
		<td width=25%>
		<table class="normal" border=0 width="100%" height="100%">
		{foreach item=row from=$fields.right}
		<tr><td>
			<table class=normal width="100%" height="100%"><tr>
			{foreach item=field from=$row}
			{if $field|is_array}
				<td align="{$field.align}" class="{$field.style|default:'normal'}">
				{$field.title}
				</td>
			{/if}
			{/foreach}
			</tr></table>
		</td></tr>
		{/foreach} 
		</table>
		</td>
	{/if}
		
	</tr></table>
	</td></tr>

	{foreach item=row from=$fields.bottom}
	<tr><td>
		<table class=normal width="100%" height="100%"><tr>
		{foreach item=field from=$row}
		{if $field|is_array}
			<td align="{$field.align}" class="{$field.style|default:'normal'}">
			{$field.title}
			</td>
		{/if}
		{/foreach}
		</tr></table>
	</td></tr>
	{/foreach} 
	
	{if $pricingOptions}
	<tr><td>
	  <table class="normal" width="100%">
	    <tr><td width="100%">
	    {if $gallery.showCaption|default:'yes' eq 'yes'}<span class="{$gallery.captionStyle|default:'normal'}">{$gallery.caption|default:'Available Product Configurations:'}</span>{/if}
	      {if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery} [ <a href="manage/ecommerce/editPricing.php?item_id={$image.id}">Edit Configurations</a> ] [ <a href="manage/settings.php?mode=pricingList">Edit Output</a> ] {/if}
	    </td></tr>
	  </table>
	  {if $gallery.separationHeight|default:2}
	  <hr noshade size="{$gallery.separationHeight|default:2}" color="{$gallery.separationColor|default:'#888888'}" width="100%">
	  {/if}
	</td></tr>
	{foreach from=$pricingOptions item=option}
	<tr><td>
	  <table class="normal" width="100%">
	    <tr>
	     <td valign="top" width="100%" nowrap>
	     {foreach from=$option.attributes item=attr}
	     {if @'all'|in_array:$gallery.visibleFields or @$attr.id|in_array:$gallery.visibleFields}
	     <span class="{$gallery.attributeLabelsStyle|default:'normal'}">{$attr.name}: </span>
	     <span class="{$gallery.attributeValuesStyle|default:'normal'}">{$attr.value|default:"n/a"}</span><br />
	     {/if}
	     {/foreach}
	     {if @'all'|in_array:$gallery.visibleFields or @'quantity'|in_array:$gallery.visibleFields}
	     <span class="{$gallery.attributeLabelsStyle|default:'normal'}">Quantity in Stock: </span>
	     <span class="{$gallery.attributeValuesStyle|default:'normal'}">{$option.quantity}</span>
	     {/if}
	     </td>
	     <td align="right" nowrap><span class="{$gallery.PSaddToCart|default:'normal'}"><a href="cart.php?action=add&id={$image.id}&category={$smarty.request.category}&pid={$option.id}">{$gallery.PLaddToCart|default:'Add To Cart'}</a></span><br /><span class="{$gallery.PSprice|default:'normal'}">{$option.price|galleryPrice}</span></td>
	    </tr>
	  </table>
	  {if $gallery.separationHeight|default:2}
	  <hr noshade size="{$gallery.separationHeight|default:2}" color="{$gallery.separationColor|default:'#888888'}" width="100%">
	  {/if}
	</td></tr>
	{/foreach}
	{/if}

</table>

{/if}

{if $newWindow}
</body>
</html>
{/if}