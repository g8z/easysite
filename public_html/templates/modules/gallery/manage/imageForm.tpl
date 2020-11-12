{if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery and $smarty.session.cm_auth.cm_gallery_edit_images}
<form name=mainForm method=post action="{$prevLocation}" enctype="multipart/form-data">
<input type=hidden name=galleryAction value="save_image_{$image.id}">

{include file=modules/gallery/navigation.tpl}<br />

<table cellpadding=1 cellspacing=1 border=0 class=normal>

	<tr><td colspan=3 class=subtitle>Enter Image Data {if $image.name} for {$image.name}{/if}</td></tr>
	<tr><td colspan=3>&nbsp;</td></tr>
	<tr>
		<td valign=top>
		{if $image.id}
		<img src=
		"{imgsrc table=$smarty.const.IMAGES_TABLE field=img_thumb id=$image.id}" style="margin-right:8px;" 
		{if $createThumb eq 'no'}width='{$width}'{/if}>
		{else}&nbsp;
		{/if}
		</td>
		
		<td colspan=2 align=left>
		
		<table cellpadding=1 cellspacing=3 border=0 class=normal>
		<tr>
		  <td align=right valign="top">Change Large Image: </td>
		  <td valign="top"><input type="file" name="iimg_large">{if $createThumb}<br /><input type="checkbox" name="overrideThumb" checked value="1"><label for="overrideThumb">Re-Create Thumbnail Image (will override input below).{/if}</td>
		</tr>
		{if $createThumb}
		<tr>
		  <td align="right" valign="top">Change Thumbnail Image: </td>
		  <td valign="top"><input type="file" name="iimg_thumb"></td>
		</tr>
		{/if}
		<tr>
		  <td align=right valign="top">Category: </td>
		  <!--<td><select name=icat_id>{html_options options=$categories selected=$image.cat_id}</select></td>-->
		  <td>
		  {foreach from=$categories item=cat}
		    <div style="margin-left: {$cat.level}0px">
		    <input type="checkbox" name="icat_ids[]" value="{$cat.id}" {if @$cat.id|in_array:$image.cat_ids}checked{/if}> {$cat.origName}
		    </div>
		  {/foreach}
		  <br />
		  </td>
		  <td></td>
		</tr>
		<tr>
		  <td align=right>Title: </td>
		  <td><input type=text name=ititle value="{$image.title}" size=30></td>
		</tr>
		<!--
		<tr>
		  <td align=right>Order: </td>
		  <td><input type="text" name="iorder" value="{$image._order}" size="3"></td>
		</tr>
		-->
		<tr>
		  <td valign=top align=right>Description: </td>
		  <td valign=top><textarea name=idescription rows=5 cols=50>{$image.description}</textarea></td>
		</tr>
		
		{if $gallery.useEcommerce eq 'yes'}
		
			{* display ecommerce fields *}
			
			<tr>
			  <td align=right valign="top">Base Price: <br /><small>additional pricing options can be configured</small></td>
			  <td valign="top">
			    <input type=text size=5 name="iprice" value="{$image.price}"> &nbsp;
			    <input type="checkbox" name="useCategoryPrice" value="1" {if $image.use_cat_price}checked{/if}><label for="useCategoryPrice">Use Category Default Price</label>
			    {if $attributes}<br /><a href="{$docroot}modules/gallery/manage/ecommerce/editPricing.php?item_id={$image.id}">Configure Price Based on Attributes</a>{/if}
			  </td>
			</tr>
			<tr>
			  <td align=right>Quantity in Stock: </td>
			  <td><input size=5 type=text name="iquantity" value="{$image.quantity}"></td>
			</tr>
			
			{* display user-defined attributes *}
			
			{foreach item=attr from=$attributes}
			<tr>
			  <td align="right"{if $attr.type|strpos:'list' neq false || $attr.type eq 'multi-text'} valign="top"{/if}>{$attr.name}{if $attr.measurement}, {$attr.measurement}{/if}: </td>
			  <td>
			  {if $attr.type eq 'number'}
				  <input type=text size=5 name="attr_{$attr.id}" value="{$attr.value}">
			  {elseif $attr.type eq 'single-text'}
				  <input type=text name="attr_{$attr.id}" value="{$attr.value}">
			  {elseif $attr.type eq 'multi-text'}
				  <textarea name="attr_{$attr.id}" rows=5 cols=50>{$attr.value}</textarea>
			  {elseif $attr.type eq 'date'}
				  {html_select_date prefix=attr_`$attr.id`_ day_value_format="%02d" time=$attr.value start_year="-60" end_year="+10" day_extra='><option value="00">Day</option' month_extra='><option value="00">Month</option' year_extra='><option value="0000">Year</option'}
			  {else}
				  {list name="attr_`$attr.id`[]" key=$attr.listName extra="multiple" selected=$attr.value|unserialize}
			  {/if} 
			  <input type="checkbox" name="attr_{$attr.id}_default" {if $attr.use_default}checked{/if} value="1"> Use Default Value
			  </td>
			</tr>
			{/foreach}
			
			<tr><td colspan=2>&nbsp;</td></tr>
			{* 
			<tr><td colspan=2 nowrap align="center"></td></tr>
			<tr><td colspan=2>&nbsp;</td></tr>
			*}
		{/if}
		</table>
		
		</td>
	
	</tr>
	
	<tr><td colspan=3 nowrap>
	
	<input type=submit name=save value="Save"> 
	
	<!--
	<input type=submit name=delete value="Delete" onClick="return confirm('Are you sure?');"> 
	-->
	
	<input type=submit name=save value="Cancel"> 
	
	</td></tr>
</table>
</form>

{else}
<br />
<div class=normal>You have not permissions to edit images.</div>
{/if}
