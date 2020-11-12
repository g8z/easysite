{* special js functions for this page only *}
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
<form action=shipping.php method="POST" enctype="multipart/form-data" name="editAttributes">
<input type=hidden name=formIsSubmitted value=0>
<input type=hidden name=deleteId value=0>

<table class=normal cellspacing="1" cellpadding="1">

<tr><td class=subtitle colspan=2>Shipping Options</td></tr>
<tr><td colspan=2>&nbsp;</td></td>

<tr><td colspan=2>If one or more of your products require shipment, you may specify the available shipping methods and prices here. If your product is an electronic good or service which does not require shipping, you should specify that in the product options page.</td></tr>
<tr><td colspan=2>&nbsp;</td></td>

<tr><td colspan=2><b>Add options</b></td></td>
<tr><td width="20%">Name: </td><td><input type=text name="ship_name"></td></tr>
<tr><td>Price: </td><td nowrap><input type=text size=5 name="ship_price"></td></tr>
<tr><td>Shipping Time: </td><td nowrap><input type=text size=5 name="ship_period">&nbsp;<select name="ship_p_item">{html_options values=$shipValues output=$shipTitles}</select></td></tr>

</td></tr>

</table>

{if $options}

	<table class=normal cellpadding="1" cellspacing="1">
	
	<tr><td colspan=5>&nbsp;</td></td>
	<tr><td colspan=5><b>Edit existing attributes</b></td></td>

	<tr><th align="left">Name</th><th align="left">Price</th><th align="left">Time</th><th align="left">D/W</th><th align="left">Visible</th><th>&nbsp;</th></tr>
	
	{foreach item=ship from=$options}
	<tr><td><input type=text name="ship_name_{$ship.id}" value="{$ship.name}"></td><td><input type=text size=5 name="ship_price_{$ship.id}" value="{$ship.price}"></td><td><input type=text size=5 name="ship_period_{$ship.id}" value="{$ship.period}"></td><td><select name="ship_p_item_{$ship.id}">{html_options values=$shipValues output=$shipTitles selected=$ship.p_item}</select></td><td align="center"><input type="checkbox" name="ship_visible_{$ship.id}" value=1 {if $ship.visible}checked{/if}></td><td><a href="#" onclick="javascript: if ( confirm( 'Are you sure you want to delete this shipping option?' ) ) {ldelim} document.editAttributes.deleteId.value={$ship.id}; submitForm(); {rdelim}; ">Delete</a></td></tr>
	{/foreach}
	</table>

{/if}

<table class=normal cellspacing="2" cellpadding="2">

<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2>
    <input type=button name=save value="Submit All" onClick="javascript:submitForm();">
    
    <input type=reset value="Reset All">
</td></tr>

</table>

</form>