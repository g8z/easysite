{include file=modules/gallery/navigation.tpl}<br />

<form action="editCheckout.php" name="editCheckout" method="POST">
<input type="hidden" name="isFormSubmitted" value="1">

<p class="subtitle">Checkout Settings</p>

<table class="normal">
<tr>
  <td><b>Field</b></td>
  <td><b>Visible</b></td>
  <td><b>Required</b></td>
</tr>

{foreach from=$fields item=field key=k}
{if $k neq 'require_shipping'}
<tr>
  <td><input type="text" name="fields[{$k}][title]" value="{$field.title}"></td>
  {if $k eq 'payment_method'}
  <td colspan="2"><select name="fields[{$k}][payment]">{html_options options=$paymentOptions selected=$field.payment}</select></td>
  {elseif $k eq 'shipping_method'}
  <td colspan="2"><select name="fields[{$k}][shipping]">{html_options values=$shipValues output=$shipTitles selected=$field.shipping}</select></td>
  </td><tr><td>Show Shipping Method</td><td> <select name="fields[{$k}][show_shipping]">{html_options options=$boolean selected=$field.show_shipping}</select></td>
  {else}
  <td><select name="fields[{$k}][visible]">{html_options options=$boolean selected=$field.visible}</select></td>
  <td><select name="fields[{$k}][required]">{html_options options=$boolean selected=$field.required}</select></td>
  {/if}
</tr>
{else}
<tr><td colspan="3">&nbsp;</td></tr>
<tr><td>{$field.title}</td><td colspan="2"><select name="fields[{$k}][require]">{html_options options=$boolean selected=$field.require}</select></td></tr>
<tr><td colspan="3">&nbsp;</td></tr>
{/if}
{/foreach}


<tr><td colspan="3">&nbsp;</td></tr>

<tr><td colspan="3"><input type="submit" name="submitButton" value="Submit"> <input type="reset" name="resetButton" value="Reset"> <input type="button" name="cancelButton" value="Cancel" onclick="javascript: document.location.href='{$prevLocation}';"></td></tr>

</table>

</form>