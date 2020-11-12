{literal}
<script language="javascript">
<!--

function submitLink( value ) {
	var theForm = document.editOptions;
	
	theForm.action.value=value;
	theForm.submit();
	//submitForm();
	
	return false;
	
}

function submitForm() {
	var theForm = document.editOptions;
	
	theForm.isFormSubmitted.value=1;
	theForm.submit();
}

-->
</script>
{/literal}

{include file=modules/gallery/navigation.tpl}

{if $type neq ''}

<br />
<form action="editDisplayOptions.php" name="editOptions" method="POST">
<input type=hidden name=action value="">
<input type=hidden name=isFormSubmitted value="0">
<input type=hidden name=type value="{$type}">
<input type=hidden name=editFieldId value="{$editField.id}">

<table cellpadding="0" cellspacing="0" class="normal">
<tr><td class="subtitle">{$title}</td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td>
	<table cellpadding="5" class="normal">
	<tr><td>
	{* display colored table *}
	<table bgcolor="black" cellpadding="2" height=100 width=100 class="normal">
	<tr><td colspan=3 align=center bgcolor="#F47A20">&nbsp;</td></tr>
	<tr height=50%><td bgcolor="#4B60AC" width=20%>&nbsp;</td><td bgcolor="#D7D8DB" width=20%>Image</td><td bgcolor="#B7C142" width=20%>&nbsp;</td></tr>
	<tr><td colspan=3 align=center bgcolor="#2F6F42">&nbsp;</td></tr>
	</table> 
	</td>
	<td valign="top">{$description}</td></tr>
	</table>
</td></tr>

<tr><td>&nbsp;</td></tr>

{if $editField.id}
{* display field edit form *}
<tr><td><b>Edit Field Named "{$editField.name}"</b></td></tr>

<tr><td>
<table class="normal">
<tr>
	<td>Visible: </td><td><select name="visible_{$editField.id}">{html_options values=$visibleValues output=$visibleTitles selected=$editField.visible}</select>&nbsp;&nbsp;</td>
	<td>Section: </td><td><select name="section_{$editField.id}">{html_options values=$sectionValues output=$sectionValues selected=$editField.section}</select>&nbsp;&nbsp;</td>
	<td>Align: </td><td><select name="align_{$editField.id}">{html_options values=$alignValues output=$alignValues selected=$editField.align}</select>&nbsp;&nbsp;</td>
	<td>Style: </td><td><select name="style_{$editField.id}">{html_options values=$styleValues output=$styleValues selected=$editField.style}</select>&nbsp;[ <a href="{$docroot}manage/editStyles.php">Style Tool</a> ]</td>
</tr>
<tr><td>Layout: </td><td colspan="7"><input type="text" name="layout_{$editField.id}" value="{$editField.layout}" size="40"></td></tr>
<tr><td colspan="7">&nbsp;</td></tr>
<tr><td colspan=7><input type=button name=save value='Save' onclick="javascript: submitForm();">&nbsp;<input type=reset name=clear value='Reset'></td></tr>
</table>

</td></tr>

<tr><td>&nbsp;</td></tr>
{/if}

<tr><td>
<table class="normal" border=0 cellpadding="3" cellspacing="1" width="100%" bgcolor="Black">

	
	{foreach item=row from=$fields.top}
	<tr><td bgcolor="#F47A20">
		<table class=normal width="100%" height="100%"><tr>
		{foreach item=field from=$row}
		{if $field|is_array}
			<td align="{$field.align}" bgcolor="#F47A20" class="{$field.style|default:'normal'}">
				<table class="normal"><tr>
				<td><a href="#" onclick="javascript:return submitLink( 'edit_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/edit.png" border=0></a>{if $field.canLeft}&nbsp;<a href="#" onclick="javascript:return submitLink( 'left_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/leftarrow.png" border=0></a>{/if}</td>
				<td align=center>{if $row.canUp}<a href="#" onclick="javascript:return submitLink( 'up_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/uparrow.png" border=0></a><br />{/if}{$field.name}{if $row.canDown}<br /><a href="#" onclick="javascript:return submitLink( 'down_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/downarrow.png" border=0></a>{/if}</td>
				{if $field.canRight}<td>&nbsp;<a href="#" onclick="javascript:return submitLink( 'right_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/rightarrow.png" border=0></a></td>{/if}
				</tr></table>
			</td>
		{/if}
		{/foreach}
		</tr></table>
	</td></tr>
	{/foreach}
	
	<tr><td align="center" width="100%" style="padding:0px;">
	
	<table class="normal" border=0 width="100%" cellpadding="0" cellspacing="0" height="100">
	<tr>
	
	{if $fields.left}
		<td width=25%>
		<table class="normal" border=0 width="100%" height="100%" bgcolor="Black" cellpadding="3" cellspacing="1">
		
		{*
		{foreach item=field from=$fields.left}
		<tr><td align="{$field.align}" valign="top" bgcolor="#4B60AC" class="{$field.style|default:'normal'}"><a href="#" onclick="javascript:return submitLink( 'edit_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/edit.png" border=0></a>{if $field.first neq 1}&nbsp;<a href="#" onclick="javascript:return submitLink( 'up_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/uparrow.png" border=0></a>{/if}{if $field.last neq 1}&nbsp;<a href="#" onclick="javascript:return submitLink( 'down_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/downarrow.png" border=0></a>{/if}&nbsp;{$field.name}</td></tr>
		{/foreach}
		*}
		
		{foreach item=row from=$fields.left}
		<tr><td bgcolor="#4B60AC">
			<table class=normal width="100%" height="100%"><tr>
			{foreach item=field from=$row}
			{if $field|is_array}
				<td align="{$field.align}" bgcolor="#4B60AC" class="{$field.style|default:'normal'}">
					<table class="normal"><tr>
					<td><a href="#" onclick="javascript:return submitLink( 'edit_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/edit.png" border=0></a>{if $field.canLeft}&nbsp;<a href="#" onclick="javascript:return submitLink( 'left_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/leftarrow.png" border=0></a>{/if}</td>
					<td align=center>{if $row.canUp}<a href="#" onclick="javascript:return submitLink( 'up_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/uparrow.png" border=0></a><br />{/if}{$field.name}{if $row.canDown}<br /><a href="#" onclick="javascript:return submitLink( 'down_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/downarrow.png" border=0></a>{/if}</td>
					{if $field.canRight}<td>&nbsp;<a href="#" onclick="javascript:return submitLink( 'right_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/rightarrow.png" border=0></a></td>{/if}
					</tr></table>
				</td>
			{/if}
			{/foreach}
			</tr></table>
		</td></tr>
		{/foreach}
			
		<tr bgcolor="#4B60AC"><td height="100%">&nbsp;</td></tr>
		</table>
		</td>
	{/if}
		
	<td bgcolor="#D7D8DB" height=100 width=50% align=center><img src="{$docroot}modules/gallery/images/item_image.gif" border=0></td>

	{if $fields.right}
		<td width=25%>
		<table class="normal" border=0 width="100%" height="100%" bgcolor="Black" cellpadding="3" cellspacing="1">
		
		{*
		{foreach item=field from=$fields.right}
		<tr><td align="{$field.align}" valign="top" bgcolor="#B7C142" class="{$field.style|default:'normal'}"><a href="#" onclick="javascript:return submitLink( 'edit_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/edit.png" border=0></a>{if $field.first neq 1}&nbsp;<a href="#" onclick="javascript:return submitLink( 'up_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/uparrow.png" border=0></a>{/if}{if $field.last neq 1}&nbsp;<a href="#" onclick="javascript:return submitLink( 'down_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/downarrow.png" border=0></a>{/if}&nbsp;{$field.name}</td></tr>
		{/foreach}
		*}
		{foreach item=row from=$fields.right}
		<tr><td bgcolor="#B7C142">
			<table class=normal width="100%" height="100%"><tr>
			{foreach item=field from=$row}
			{if $field|is_array}
				<td align="{$field.align}" bgcolor="#B7C142" class="{$field.style|default:'normal'}">
					<table class="normal"><tr>
					<td><a href="#" onclick="javascript:return submitLink( 'edit_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/edit.png" border=0></a>{if $field.canLeft}&nbsp;<a href="#" onclick="javascript:return submitLink( 'left_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/leftarrow.png" border=0></a>{/if}</td>
					<td align=center>{if $row.canUp}<a href="#" onclick="javascript:return submitLink( 'up_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/uparrow.png" border=0></a><br />{/if}{$field.name}{if $row.canDown}<br /><a href="#" onclick="javascript:return submitLink( 'down_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/downarrow.png" border=0></a>{/if}</td>
					{if $field.canRight}<td>&nbsp;<a href="#" onclick="javascript:return submitLink( 'right_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/rightarrow.png" border=0></a></td>{/if}
					</tr></table>
				</td>
			{/if}
			{/foreach}
			</tr></table>
		</td></tr>
		{/foreach}
				
		<tr height="100%" bgcolor="#B7C142"><td>&nbsp;</td></tr>
		</table>
		</td>
	{/if}
		
	</tr></table>
	</td></tr>
	
	{foreach item=row from=$fields.bottom}
	<tr><td bgcolor="#2F6F42">
		<table class=normal width="100%" height="100%"><tr>
		{foreach item=field from=$row}
		{if $field|is_array}
			<td align="{$field.align}" bgcolor="#2F6F42" class="{$field.style|default:'normal'}">
				<table class="normal"><tr>
				<td><a href="#" onclick="javascript:return submitLink( 'edit_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/edit.png" border=0></a>{if $field.canLeft}&nbsp;<a href="#" onclick="javascript:return submitLink( 'left_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/leftarrow.png" border=0></a>{/if}</td>
				<td align=center>{if $row.canUp}<a href="#" onclick="javascript:return submitLink( 'up_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/uparrow.png" border=0></a><br />{/if}{$field.name}{if $row.canDown}<br /><a href="#" onclick="javascript:return submitLink( 'down_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/downarrow.png" border=0></a>{/if}</td>
				{if $field.canRight}<td>&nbsp;<a href="#" onclick="javascript:return submitLink( 'right_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/rightarrow.png" border=0></a></td>{/if}
				</tr></table>
			</td>
		{/if}
		{/foreach}
		</tr></table>
	</td></tr>
	{/foreach}
	
</table>
	            		
<tr><td>&nbsp;</td></tr>
<tr><td><b>Invisible Fields:</b></td></tr>
{foreach item=field from=$fields.invisible}
{if $field.name}
<tr><td><a href="#" onclick="javascript:return submitLink( 'edit_field_{$field.id}' );"><img src="{$docroot}modules/gallery/images/edit.png" border=0></a>&nbsp;{$field.name}</td></tr>
{/if}
{foreachelse}
<tr><td>All Fields are Visible</td></tr>
{/foreach}


</td></tr>

</table>
</form>

{else}
<table cellpadding="0" cellspacing="0" class="normal">
<tr><td>&nbsp;</td></tr>
<tr><td class="subtitle">Select Options to Edit</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><a href="editDisplayOptions.php?type=cat_thumb">Thumbnail Category Options</a></td></tr>
<tr><td><a href="editDisplayOptions.php?type=pr_thumb">Thumbnail Image/Product Options</a></td></tr>
<tr><td><a href="editDisplayOptions.php?type=full">Full Image/Product Page Options</a></td></tr>
{if $gallery.useEcommerce eq 'yes'}
<tr><td><a href="settings.php?mode=shopping_cart">Shopping Cart</a></td></tr>
<tr><td><a href="editCheckout.php">Checkout Page</a></td></tr>
{/if}
</table>
{/if}