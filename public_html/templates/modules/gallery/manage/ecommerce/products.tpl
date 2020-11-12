{* special js functions for this page only *}

<script language="JavaScript" src="{$docroot}libs/JSHttpRequest/JSHttpRequest.js"></script>

{literal}
<script language="JavaScript">
<!--

function doLoad(name,value) {
document.getElementById(name).innerHTML = 'loading...';

var req = new JSHttpRequest();
req.onreadystatechange = function() {
  if (req.readyState == 4) {
    document.getElementById(name).innerHTML = req.responseJS.output;
    document.getElementById('visible').style.border="1px";
    document.getElementById('visible').style.border='0px';
  }
}
req.caching = true;
req.open('GET', '{/literal}{$docroot}{literal}modules/gallery/helpers/loadField.php', true);
req.send({ n: name, v:value });
}


-->
</script>
{/literal}


{literal}
<script language="Javascript">
<!--

function submitForm() {
	var theForm = document.editAttributes;
	
	theForm.formIsSubmitted.value=1;
	theForm.submit();
	
	return true;
}

//-->
</script>
{/literal}
{include file=modules/gallery/navigation.tpl}

<br />
<form action=products.php method="POST" enctype="multipart/form-data" name="editAttributes">
<input type=hidden name=formIsSubmitted value=0>
<input type=hidden name=deleteId value=0>

<table class=normal cellspacing="1" cellpadding="1">

<tr><td class=subtitle colspan=2>Product Attributes</td></tr>
<tr><td colspan=2>&nbsp;</td></td>

<tr><td colspan=2>You may specify the attributes to display with your products. For example, if you are selling music CDs, you may wish to add attributes like "Album" and "Year Released". Two attributes, height and width, have been added for you, athough you can remove these if your products do not have height and width attributes. Hard-coded attributes are "price" and "quantity in stock".</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>

<tr><td colspan=2><b>Add attribute</b></td></td>
<tr><td>Name: </td><td><input type=text name="attr_name"></td></tr>
<tr><td nowrap>Measurement: </td><td nowrap><input type=text name="attr_measurement"> (e.g. "kg" for kilograms, "m" for meters)</td></tr>
<tr><td>Attribute Type: </td><td nowrap><select name="attr_type" onchange="doLoad('attr_default', this.value);">{html_options values=$typeValues output=$typeTitles}</select>&nbsp; [ <a href="{$docroot}manage/listEdit.php">Lists Tool</a> ]</td></tr>
<tr><td>Default Value: </td><td nowrap><div id="attr_default"><input type=text name="attr_default"></div></td></tr>


<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2>
    <input type=button name=save value="Submit All" onClick="javascript:submitForm();"> 
    <input type=reset value="Reset All"> 
    <input type=button name="cancelButton" value="Cancel" onclick="javascript: document.location.href='{$prevLocation}'"> 
</td></tr>

{if $attributes}
<tr><td colspan=2>&nbsp;</td></td>
<tr><td colspan=2><b>Edit existing attributes</b></td></td>

{foreach item=attr from=$attributes}
<tr>
  <td>Name: </td>
  <td><input type=text name="attr_name_{$attr.id}" value="{$attr.name}"></td>
</tr>
<tr>
  <td nowrap>Measurement: </td>
  <td><input type=text size=4 name="attr_measurement_{$attr.id}" value="{$attr.measurement}"></td>
</tr>
<tr>
  <td>Attribute Type: </td>
  <td nowrap><select name="attr_type_{$attr.id}" onchange="doLoad('attr_default_{$attr.id}', this.value);">{html_options values=$typeValues output=$typeTitles selected=$attr.type}</select></td>
</tr>
<tr>
  <td>Default Value: </td>
  <td><div id="attr_default_{$attr.id}">
	    {if $attr.type eq 'number'}
	    	<input type=text name="attr_default_{$attr.id}" size=5 value="{$attr._default}">
	    {elseif $attr.type eq 'single-text' or $attr.type eq 'multi-text'}
	    	<input type=text name="attr_default_{$attr.id}" value="{$attr._default}">
	    {elseif $attr.type eq 'date'}
	    	{html_select_date prefix=attr_default_`$attr.id`_ start_year=-10 end_year=+10 time=$attr._default}
	    {else}
	    	{list key=$attr.listName name="attr_default_`$attr.id`[]" extra="multiple" selected=$attr._default|unserialize}
	    {/if}  
  </div></td>
</tr>
<tr>
  <td>Visible: </td>
  <td><input type="checkbox" name="attr_visible_{$attr.id}" value=1 {if $attr.visible}checked{/if}></td>
</tr>
<tr><td colspan=2>
    <input type=button name=save value="Submit All" onClick="javascript:submitForm();"> 
    <input type=reset value="Reset All"> 
    <input type=button onclick="javascript: if ( confirm( 'Are you sure you want to delete this attribute?' ) ) {ldelim} document.editAttributes.deleteId.value={$attr.id}; submitForm(); {rdelim}; " value="Delete"> 
    <input type=button name="cancelButton" value="Cancel" onclick="javascript: document.location.href='{$prevLocation}'"> 
</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
{/foreach}
{/if}

</table>




</form>