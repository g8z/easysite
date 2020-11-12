{include file=modules/gallery/navigation.tpl}
<br />

{literal}
<script language="Javascript">
<!--

function submitForm() {
	var theForm = document.editPricing;
	
	theForm.formIsSubmitted.value=1;
	theForm.submit();
	
	return true;
}

//-->
</script>
{/literal}
<form action=editPricing.php method="POST" enctype="multipart/form-data" name="editPricing">
<input type=hidden name=formIsSubmitted value=1>
<input type=hidden name=addPrice value=0>
<input type=hidden name=item_id value="{$item_id}">
<input type=hidden name=deleteId value=0>

<table class=normal cellspacing="1" cellpadding="1" border=0>

<tr><td class=subtitle colspan=3>Specify Pricing Options</td></tr>
<tr><td colspan=3>&nbsp;</td></td>

<tr><td colspan=3>You may specify different prices for the product based on attribute values.</td></tr>
<tr><td colspan=3>&nbsp;</td></tr>
</table>

<table class=normal cellspacing="1" cellpadding="1" border=0>
<tr><td colspan=3><b>Add Price</b></td></td>
{assign var="attributes" value=$attributes}
{assign var="price_id" value="0"}
{assign var="price" value="0"}
{include file="modules/gallery/manage/ecommerce/pricingBit.tpl}


{if $prices}
<tr><td colspan=3>&nbsp;</td></tr>
<tr><td colspan=3><b>Edit Price</b></td></td>
{foreach from=$prices item=price}
{assign var="attributes" value=$price.attributes}
{assign var="price_id" value=$price.id}
{assign var="price" value=$price}
{include file="modules/gallery/manage/ecommerce/pricingBit.tpl}
{/foreach}
{/if}



</table>