{literal}
<script language="javascript">
<!--

function checkForm() {
	
	var theForm = document.checkout;
	
	{/literal}
	{foreach from=$fields key=k item=field}
	{if $field.required && $field.visible}
	
	if ( theForm.{$k} && eval( 'theForm.{$k}.value' ) == '' ) {ldelim}
		alert( 'Plese enter your {$field.title}' );
		return false;
	{rdelim}
	
	{if $k eq 'email'}
		if ( !isValidEmail( theForm.email.value ) ) {ldelim}
			alert( 'Please enter valid e-mail address.' );
			return false;
		{rdelim}
	{/if}
	
	{/if}
	{/foreach}
	{literal}	
	
	submitForm();
	
}

function submitForm() {
	var theForm = document.checkout;
	
	theForm.formIsSubmitted.value = 1;
	theForm.submit();
	
	return true;
}

-->
</script>
{/literal}


<form method="POST" name="checkout" style="margin: 0px;">
<input type=hidden name=formIsSubmitted value=0>
<input type=hidden name=id value="{$vars.id}">
<input type=hidden name=backAction value="{$smarty.get.backAction}">

<table class=normal cellspacing="2" cellpadding="2" width="80%">

{if $edit}
<tr><td><b>Number:</b></td><td>{$vars.id}</td></tr>
<tr><td><b>Paymet Transaction Id:</b></td><td>{if $vars.txn_id}{$vars.txn_id}{else}Not Available{/if}</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
{/if}


{capture name="first_name"}<input type=text name=first_name value="{$vars.first_name}">{/capture}
{capture name="last_name"}<input type=text name=last_name value="{$vars.last_name}">{/capture}
{capture name="email"}<input type=text name=email value="{$vars.email}">{/capture}
{capture name="phone"}<input type=text name=phone value="{$vars.phone}">{/capture}
{capture name="address_1"}<input type=text name=address_1 value="{$vars.address_1}">{/capture}
{capture name="address_2"}<input type=text name=address_2 value="{$vars.address_2}">{/capture}
{capture name="city"}<input type=text name=city value="{$vars.city}">{/capture}
{capture name="states"}{list key=states selected=$vars.state}{/capture}
{capture name="countries"}{list key=countries selected=$vars.country}{/capture}
{capture name="zip"}<input type=text name=zip size=5 value="{$vars.zip}">{/capture}

{capture name="shipping_method"}
{if $sFields.shipping_method.shipping eq 'allow_choose'}
<select name=shipping_method>{html_options options=$shipOptions selected=$vars.shipping_method}</select>
{else}
{if $edit}{assign var="m" value=$vars.shipping_method}{else}{assign var="m" value=$sFields.shipping_method.shipping}{/if}
{$shipOptions.$m}<input type="hidden" name="shipping_method" value="{$m}">
{/if}
{/capture}

{capture name="payment_method"}
{if $payment eq 'allow_choose'}
<select name=payment_method>{html_options options=$paymentOptions selected=$vars.payment_method}</select>
{else}
{if $edit}{assign var="m" value=$vars.payment_method}{else}{assign var="m" value=$payment}{/if}
{$paymentOptions.$m}<input type="hidden" name="payment_method" value="{$m}">
{/if}
{/capture}

<tr><td colspan=2><b>Personal Info:</b></td></tr>

{foreach from=$pFields key=k item=field}
{assign var="field" value=$field}
{assign var="html" value=$smarty.capture.$k}
{include file="modules/gallery/fieldBit.tpl"}
{/foreach}

<tr><td colspan=2>&nbsp;</td></tr>

{if $require_shipping}

<tr><td colspan=2><b>Shipping Address:</b></td></tr>

{foreach from=$sFields key=k item=field}
{assign var="field" value=$field}
{assign var="html" value=$smarty.capture.$k}
{include file="modules/gallery/fieldBit.tpl"}
{/foreach}

{if $sFields.shipping_method.visible|default:'yes' eq 'no'}
{if $edit}{assign var="m" value=$vars.shipping_method}{else}{assign var="m" value=$sFields.shipping_method.shipping}{/if}
<input type="hidden" name="shipping_method" value="{$m}">
{/if}

<tr><td colspan=2>&nbsp;</td></tr>

{/if}

{if $payment neq 'skip'}
<tr><td valign="top">Payment Method:</td><td>{$smarty.capture.payment_method}</td></tr>
{/if}

{if $edit}
<tr><td>Status:</td><td><select name=status>{html_options values=$statuses output=$statuses selected=$vars.status}</select></td></tr>
{/if}

<tr><td colspan=2>&nbsp;</td></tr>

<tr><td colspan=2><input type=button name=cancel value="<< Cancel" onclick="javascript: document.location.href='{$prevLocation}'">&nbsp;<input type=button name=continue value="Continue >>" onclick="javascript: checkForm();"></td></tr>

</table>

</form>