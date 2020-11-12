{foreach from=$attributes item=attr}
<tr>
  <td width=10><input type="checkbox" name="{$price_id}_attributes[]" value="{$attr.id}" {if $attr.checked}checked{/if}></td>
  <td>{$attr.name}:</td>
  <td width="100%">
	{if $attr.type eq 'number'}
		<input type=text name="{$price_id}_attr_{$attr.id}" size=5 value="{$attr.value1}">
	{elseif $attr.type eq 'single-text' or $attr.type eq 'multi-text'}
		<input type=text name="{$price_id}_attr_{$attr.id}" value="{$attr.value1}">
	{elseif $attr.type eq 'date'}
		{html_select_date prefix=`$price_id`_attr_`$attr.id`_ start_year=-10 end_year=+10 time=0000-00-00 day_extra="><option value=''>Day</option" month_extra="><option value=''>Month</option" year_extra="><option value=''>Year</option"}
	{elseif  'list'|strpos:$attr.type eq 0}
		{list key=$attr.listName name=`$price_id`_attr_`$attr.id` selected=$attr.value1}
	{/if}
  </td>
</tr>
{/foreach}
<tr><td colspan="3">Quantity in Stock: <input type="text" name="{$price_id}_quantity" value="{$price.quantity}" size="5"></td></tr>
<tr><td colspan="3"><input type="radio" name="{$price_id}_price_type" value="fixed" {if $price.type eq 'fixed' || !$price_id}checked{/if}>Set Price To: <input type="text" name="{$price_id}_fixed_price" size="5" value="{$price.fixed_price}"></td></tr>
<tr><td colspan="3"><input type="radio" name="{$price_id}_price_type" value="not_fixed" {if $price.type eq 'not_fixed'}checked{/if}><select name="{$price_id}_delta_type">{html_options values=$deltaValues output=$deltaTitles selected=$price.delta_type}</select> Base Price by <input type="text" name="{$price_id}_delta_price" size="5" value="{$price.delta_price}"> <select name="{$price_id}_delta_item">{html_options values=$deltaItemOptions output=$deltaItemOptions selected=$price.delta_item}</select></td></tr>
<tr><td colspan=3>&nbsp;</td></tr>
<tr><td colspan=3>
    <input type=button name=save value="{if $price_id}Update All{else}Add Pricing{/if}" onClick="javascript:submitForm();"> 
    <input type=reset value="Reset All"> 
    {if $price_id}<input type=button onclick="javascript: if ( confirm( 'Are you sure you want to delete this price option?' ) ) {ldelim} document.editPricing.deleteId.value={$price_id}; submitForm(); {rdelim}; " value="Delete"> {/if}
    <input type=button name="cancelButton" value="Cancel" onclick="javascript: document.location.href='{$prevLocation}'"> 
</td></tr>
<tr><td colspan=3>&nbsp;</td></tr>
