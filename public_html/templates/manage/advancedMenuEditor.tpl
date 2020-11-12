<script type="text/javascript" src="{$docroot}{$smarty.const.ADMIN_DIR}/picker.js"></script>

{if $propieties.menuType eq "Standard"}
{literal}
<script type="text/javascript">
<!--
function isValid() {
	var formObj = document.forms[0];

	if ( formObj.item_width.value.trim() != '' && !isNumeric( formObj.item_width.value ) ) {
		alert( 'Width must be a number.' );
		formObj.item_width.focus();
		return false;
	}
	else if ( formObj.item_height.value.trim() != '' && !isNumeric( formObj.item_height.value ) ) 
	{
		alert( 'Height must be a number.' );
		formObj.item_height.focus();
		return false;
	}
	else if ( formObj.y_offset.value.trim() != '' && !isNumeric( formObj.y_offset.value ) ) {
		alert( 'Y-Offset must be a number.' );
		formObj.y_offset.focus();
		return false;
	}
	else if ( formObj.x_offset.value.trim() != '' && !isNumeric( formObj.x_offset.value ) ) {
		alert( 'X-Offset must be a number.' );
		formObj.x_offset.focus();
		return false;
	}
	else {
		return true;
	}
}
function doSubmit() {
	var formObj = document.forms[0];
	
	if ( isValid() ) {
		formObj.submit();
	}
}
function deleteImage( imgType ) {
	var formObj = document.forms[0];
	
	if ( isValid() ) {	
		formObj.deleteImageType.value = imgType;
		formObj.submit();
	}
}
//-->
</script>
{/literal}
{else}
{literal}
<script type="text/javascript">
<!--
function isValid() {
		return true;
}
function doSubmit() {
	var formObj = document.forms[0];
	
	if ( isValid() ) {
		formObj.submit();
	}
}
function deleteImage( imgType ) {
	var formObj = document.forms[0];
	
	if ( isValid() ) {	
		formObj.deleteImageType.value = imgType;
		formObj.submit();
	}
}
//-->
{/literal}
</script>
{/if}


<form method="POST" enctype="multipart/form-data">

<table border="0" cellpadding="2" width="100%" cellspacing="0" class="normal">

<tr><td colspan="2" class="normal">{$logoutLink} {$pathway}</td></tr>

<tr>
<td colspan="2">&nbsp;</td>
</tr>

<tr>
<td colspan="2"><span class="title">Advanced Properties</span></td>
</tr>

<tr>
<td colspan="2"><span class="subtitle">For the menu item named "{$menuItemTitle}"</span></td>
</tr>

<tr>
<td colspan="2"><span class=normal>These options are different, depending on whether your menu is the "Standard" or "Tree" menu type. For "Tree" menus, some of these are item-specific, and some are level-specific.</span></td>
</tr>

<tr>
<td colspan="2" class="small">&nbsp;</td>
</tr>

{if $properties.menuType eq "Standard" or $properties.menuType eq ""}
<tr><td class="normal" colspan="2"><input type="button" value="Submit Updates" onClick="javascript:doSubmit();"></td></tr>


<tr>
<td width="25%" valign="top" align="right">Roll-out Image:<br /><small>64K Max</small></td>
<td><input type="file" name="image_out"><br /><small>(when the mouse is not over the menu item)</small></td>
</tr>

{if $properties.image_out ne '' and $properties.image_out ne 'none'}
	<tr>
	<td align="right" nowrap valign="top">Current Image: {* [ <a href="?remove={$properties.id}">remove</a> ] *}</td>
	<td>
	
	<table border="0" cellpadding="2" cellspacing="0">
	<tr><td><img src="{imgsrc table=$smarty.const.MENUITEMS_TABLE field=image_out id=$properties.id}" alt=""></td>
	<td valign="top"><input type="button" value="Remove" onClick="javascript:deleteImage('image_out');"></td></tr>
	</table>
	
	</td>
	</tr>
{/if}

<tr>
<td valign="top" nowrap align="right">Roll-over Image:<br /><small>64K Max</small></td>
<td><input type="file" name="image_over"><br /><small>(when the mouse is over the menu item)</small></td>
</tr>

{if $properties.image_over ne '' and $properties.image_over ne 'none'}
	<tr>
	<td align="right" nowrap valign="top">Current Image: {* [ <a href="?remove={$properties.id}">remove</a> ] *}</td>
	<td>
	
	<table border="0" cellpadding="2" cellspacing="0">
	<tr><td><img src="{imgsrc table=$smarty.const.MENUITEMS_TABLE field=image_over id=$properties.id}" alt=""></td>
	<td valign="top"><input type="button" value="Remove" onClick="javascript:deleteImage('image_over');"></td></tr>
	</table>
	
	</td>
	</tr>
{/if}

<tr><td align="right">Roll-over Style: </td><td><select name="over_style"><option value=''>(none)</option>{html_options values=$styleList output=$styleList selected=$properties.over_style}</select></td></tr>

<tr><td align="right">Roll-out Style: </td><td><select name="out_style"><option value=''>(none)</option>{html_options values=$styleList output=$styleList selected=$properties.out_style}</select></td></tr>

<tr><td align="right">Sticky Rollover: </td><td><input type="checkbox" name="sticky_rollover" value="1" {if $properties.sticky_rollover}checked{/if}></td></tr>

<tr><td class="normal" align="right">Target Window: </td><td>
<select name="target">{html_options output=$targetOptions values=$targetOptions selected=$properties.target|default:"_blank"}</select>

{if $menuItemType eq "url"}
<tr><td class="normal" align="right">Display within the template: </td><td>
<select name="in_template">{html_options output=$booleanOptions values=$booleanOptions selected=$properties.in_template|default:"no"}</select>

{/if}
<tr><td class="normal" align="right">Item Width: </td><td>
<input type="text" name="item_width"  size="10" value="{$properties.item_width}"> (set to 0 to use default)</td></tr>

<tr><td class="normal" align="right">Item Height: </td><td>
<input type="text" name="item_height"  size="10" value="{$properties.item_height}"> (set to 0 to use default)</td></tr>

<tr><td class="normal" align="right">Image Height: </td><td>
<input type="text" name="img_height" size="10" value="{$properties.image_height|default:15}"> (for both uploaded images)</td></tr>

<tr><td class="normal" align="right">Image Width: </td><td>
<input type="text" name="img_width" size="10" value="{$properties.image_width|default:15}"> (for both uploaded images)</td></tr>

<tr><td class="normal" align="right">X-Offset: </td><td>
<input type="text" name="x_offset" size="10" value="{$properties.x_offset}"> (for menu items after the first)</td></tr>

<tr><td class="normal" align="right">Y-Offset: </td><td>
<input type="text" name="y_offset" size="10" value="{$properties.y_offset}"> (for menu items after the first)</td></tr>

<tr><td class="normal" align="right" nowrap>Roll-out BG Color: </td><td>
<input type="text" name="out_color" size="10" value="{$properties.out_color}"> <a href="javascript:TCP.popup(document.forms[0].elements['out_color'], 1)"><img src="{$docroot}images/color.gif" border=0></a></td></tr>

<tr><td class="normal" align="right" nowrap>Roll-over BG Color: </td><td>
<input type="text" name="over_color" size="10" value="{$properties.over_color}"> <a href="javascript:TCP.popup(document.forms[0].elements['over_color'], 1)"><img src="{$docroot}images/color.gif" border=0></a></td></tr>

<tr><td class="normal" align="right">Borders: </td><td>
<input type="text" name="borders" size="10" value="{$properties.borders|default:'1,1,1,1'}"> [Left,Top,Right,Bottom] in pixels</td></tr>

<tr><td class="normal" align="right">Visibility: </td><td>
<a href="javascript:launchCentered('{$docroot}manage/editRestriction.php?resource_type=menuitem&amp;resource_id={$properties.id}',{$help.width},{$help.height},'{$help.options}');">Edit Visibility</a>

{else}
<tr><td class="normal" colspan="2"><input type="button" value="Submit Updates" onClick="javascript:doSubmit();"></td></tr>

{if $properties.hasChildren ne 0}
<tr>
<td width="25%" valign="top" align="right">Closed Image:<br /><small>64K Max</small></td>
<td><input type="file" name="image_out"><br /><small>(when this node is closed)</small></td>
</tr>

{if $properties.image_out ne '' and $properties.image_out ne 'none'}
	<tr>
	<td align="right" nowrap valign="top">Current Image: {* [ <a href="?remove={$properties.id}">remove</a> ] *}</td>
	<td>
	
	<table border="0" cellpadding="2" cellspacing="0">
	<tr><td><img src="{imgsrc table=$smarty.const.MENUITEMS_TABLE field=image_out id=$properties.id}" alt=""></td>
	<td valign="top"><input type="button" value="Remove" onClick="javascript:deleteImage('image_out');"></td></tr>
	</table>
	
	</td>
	</tr>
{/if}

<tr>
<td valign="top" nowrap align="right">Expanded Image:<br /><small>64K Max</small></td>
<td><input type="file" name="image_over"><br /><small>(when this node is expanded)</small></td>
</tr>

{if $properties.image_over ne '' and $properties.image_over ne 'none'}
	<tr>
	<td align="right" nowrap valign="top">Current Image: {* [ <a href="?remove={$properties.id}">remove</a> ] *}</td>
	<td>
	
	<table border="0" cellpadding="2" cellspacing="0">
	<tr><td><img src="{imgsrc table=$smarty.const.MENUITEMS_TABLE field=image_over id=$properties.id}" alt=""></td>
	<td valign="top"><input type="button" value="Remove" onClick="javascript:deleteImage('image_over');"></td></tr>
	</table>
	
	</td>
	</tr>
{/if}
{else}
<tr>
<td width="25%" valign="top" align="right">Menu Image:<br /><small>64K Max (this node only)</small></td>
<td><input type="file" name="image_out"><br /><small>[ NOTE: Menu images are only visible when the 'Explorer' option is enabled for the Tree. ]</small></td>
</tr>

{if $properties.image_out ne '' and $properties.image_out ne 'none'}
	<tr>
	<td align="right" nowrap valign="top">Current Image: {* [ <a href="?remove={$properties.id}">remove</a> ] *}</td>
	<td>
	
	<table border="0" cellpadding="2" cellspacing="0">
	<tr><td><img src="{imgsrc table=$smarty.const.MENUITEMS_TABLE field=image_out id=$properties.id}" alt=""></td>
	<td valign="top"><input type="button" value="Remove" onClick="javascript:deleteImage('image_out');"></td></tr>
	</table>
	
	</td>
	</tr>
{/if}
{/if}

{assign var=sameLevelNote value=" <small>(equal for all items of the same level)</small>"}

<tr><td align="right">Roll-over Style: </td><td><select name="over_style"><option value=''>(none)</option>{html_options values=$styleList output=$styleList selected=$properties.over_style}</select>{$sameLevelNote}</td></tr>

<tr><td align="right">Roll-out Style: </td><td><select name="out_style"><option value=''>(none)</option>{html_options values=$styleList output=$styleList selected=$properties.out_style}</select>{$sameLevelNote}</td></tr>

<tr><td class="normal" align="right" nowrap>Roll-out BG Color: </td><td>
<input type="text" name="out_color" size=10 value="{$properties.out_color}"> <a href="javascript:TCP.popup(document.forms[0].elements['out_color'], 1)"><img src="{$docroot}images/color.gif" border="0" alt=""></a>{$sameLevelNote}</td></tr>

<tr><td class="normal" align="right" nowrap>Roll-over BG Color: </td><td>
<input type="text" name="over_color" size="10" value="{$properties.over_color}"> <a href="javascript:TCP.popup(document.forms[0].elements['over_color'], 1)"><img src="{$docroot}images/color.gif" border="0" alt=""></a>{$sameLevelNote}</td></tr>

<tr><td class="normal" align="right">Visibility: </td><td>
<a href="javascript:launchCentered('{$docroot}manage/editRestriction.php?resource_type=menuitem&amp;resource_id={$properties.id}',{$help.width},{$help.height},'{$help.options}');">Edit Visibility</a>

{* border properties do not apply for tree menu *}
<!--
<tr><td class="normal" align="right">Borders: </td><td>
<input type="text" name="borders" size="10" value="{$properties.borders|default:'[1,1,1,1]'}"> [Left,Top,Right,Bottom] in pixels</td></tr>
-->

{/if}

<tr><td class="normal" colspan="2">

<input type="hidden" name="submitAdvancedPropertiesForm" value="1">
<input type="hidden" name="id" value="{$properties.id}">
<input type="hidden" name="deleteImageType" value="">

<input type="button" value="Submit Updates" onClick="javascript:doSubmit();"></td></tr>

<tr><td class="normal" colspan="2">&nbsp;</td></tr>

<tr><td colspan="2" class="normal">{$logoutLink} {$pathway}</td></tr>

</table>

</form>