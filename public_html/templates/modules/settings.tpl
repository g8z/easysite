{if $hasAccess}

{literal}
<script language="javascript">
<!--
function submitForm() {
    if ( fieldsAreValid() ) {
        document.editSettings.formIsSubmitted.value = 1;
        document.editSettings.submit();
    }
}

function fieldsAreValid() {
    return true;
}

//-->
</script>
{/literal}


<form action="" method="POST" enctype="multipart/form-data" name="editSettings"> 
<table border=0 cellpadding=0 cellspacing=2 width=100% class=normal>

{* loop through all available sections, printing data for each *}

<tr><td colspan=2>&nbsp;</td></tr>

<tr><td colspan=2 class=subtitle>{$settingsTitle|default:"Settings"}</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
{if $settingsDesc}
<tr><td colspan=2>{$settingsDesc}</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
{/if}

{foreach name=settings key=i item=setting from=$defaultSettings}

{* determine the size of this field based on type *}

<tr>

{if $setting[1] neq 'title'}
<td width="{$firstWidth|default:'30%'}" valign=top>{$setting[0]}</td>

<td nowrap valign=top>
    {if $setting[1] eq 'textarea'}
        <textarea cols=50 rows=4 name="{$i}">{$data.$i.value|default:"$setting[2]"}</textarea>

    {elseif $setting[1] eq 'mail_content'}
    	</td></tr>
    	<tr><td colspan=2>
        <textarea cols=70 rows=15 name="{$i}">{$data.$i.value|default:"$setting[2]"}</textarea>
        
    {elseif $setting[1] eq 'text'}
        <input type=text name="{$i}" value="{$data.$i.value|default:$setting[2]}" size=
		{if $i eq 'mail_cc' or $i eq 'mail_subject' or $i eq 'caption'}30
		{else}20
		{/if}
		>

    {* same as style, but allow for none option *}
    {elseif $setting[1] eq 'style2'}
        <select name="{$i}">
        <option value="">(none)</option>
        {html_options values=$styleList output=$styleList selected=$data.$i.value|default:"$setting[2]"}
    </select>
    
    {elseif $setting[1] eq 'attributes'}
	    {foreach from=$attributes item=att key=k}
	    <input type="checkbox" name="attributes[]" value="{$k}" {if @$k|in_array:$data.$i.value}checked{/if}> {$att}<br />
	    {/foreach}
    {elseif $setting[1] eq 'pricingAttributes'}
	    {foreach from=$pricingAttributes item=att key=k}
	    <input type="checkbox" name="pricingAttributes[]" value="{$k}" {if @$k|in_array:$data.$i.value or (@$k|in_array:$setting[2] and !$data.$i.value)}checked{/if}> {$att}<br />
	    {/foreach}
    {elseif $setting[1] eq 'title_position'}
        <select name="{$i}">
        {html_options options=$titlePositionCombo selected=$data.$i.value|default:"$setting[2]"}
        </select>
    {elseif $setting[1] eq 'style'}
        <select name="{$i}">
        {html_options values=$styleList output=$styleList selected=$data.$i.value|default:"$setting[2]"}
        </select>
    {elseif $setting[1] eq 'align'}
        <select name="{$i}">
        {html_options values=$alignCombo output=$alignCombo selected=$data.$i.value|default:"$setting[2]"}
        </select>
    {elseif $setting[1] eq 'valign'}
        <select name="{$i}">
        {html_options values=$valignCombo output=$valignCombo selected=$data.$i.value|default:"$setting[2]"}
        </select>
    {elseif $setting[1] eq 'type'}
        <select name="{$i}" onchange="javascript:submitForm();">
        {html_options values=$typeCombo output=$typeCombo selected=$data.$i.value|default:"$setting[2]"}
        </select>
    {elseif $setting[1] eq 'font'}
        <select name="{$i}">
        {html_options values=$fontCombo output=$fontCombo selected=$data.$i.value|default:"$setting[2]"}
        </select>
    {elseif $setting[1] eq 'weight'}
        <select name="{$i}">
        {html_options values=$weightCombo output=$weightCombo selected=$data.$i.value|default:"$setting[2]"}
        </select>
    {elseif $setting[1] eq 'spaceUnit'}
        <select name="{$i}">
        {html_options values=$spaceUnitCombo output=$spaceUnitCombo selected=$data.$i.value|default:"$setting[2]"}
        </select>
    {elseif $setting[1] eq 'priceFormat'}
        <select name="{$i}">
        {html_options options=$priceFormatCombo selected=$data.$i.value|default:"$setting[2]"}
        </select>
    {elseif $setting[1] eq 'leftRight'}
        <select name="{$i}">
        {html_options values=$leftRightCombo output=$leftRightCombo selected=$data.$i.value|default:"$setting[2]"}
        </select>
    {elseif $setting[1] eq 'isThumb'}
        <select name="{$i}">
        {html_options values=$isThumb output=$isThumb selected=$data.$i.value|default:"$setting[2]"}
        </select>&nbsp;&nbsp;&nbsp;
        {if $gdInstalled}
            [ GD is installed. ]
        {else}
            [ GD is not installed. ]
        {/if}
    {elseif $setting[1] eq 'boolean'}
    <select name="{$i}">
    {html_options values=$booleanCombo output=$booleanCombo selected=$data.$i.value|default:"$setting[2]"}
    </select>
    {if $i eq 'emailReceipt'}
    &nbsp;&nbsp;[ <a href="settings.php?mode=email_receipt">Edit Settings</a> ]
    {/if}
            
    {elseif $setting[1] eq 'mail_format'}
    <select name="{$i}">
    {html_options values=$mformatValues output=$mformatTitles selected=$data.$i.value|default:"$setting[2]"}
    </select>
    
	{elseif $setting[1] eq 'position'}
		<select name="{$i}"">
		{html_options values=$positionCombo output=$positionCombo selected=$data.$i|default:"$setting[2]"}
		</select>
		
    {elseif $setting[1] eq 'page'}
        <select name="{$i}">
        {html_options values=$pageValues output=$pageTitles selected=$data.$i.value|default:"$setting[2]"}
        </select>
        
    {elseif $setting[1] eq 'price'}
        <select name="{$i}">
        {html_options values=$priceValues output=$priceTitles selected=$data.$i.value|default:"$setting[2]"}
        </select>
        
    {elseif $setting[1] eq 'shipping'}
        <select name="{$i}">
        {html_options values=$shipValues output=$shipTitles selected=$data.$i.value|default:"$setting[2]"}
        </select>
        &nbsp;&nbsp;[ <a href="ecommerce/shipping.php">Edit Settings</a> ]
        
    {elseif $setting[1] eq 'currency'}
        <select name="{$i}">
        {html_options values=$currencyCombo output=$currencyCombo selected=$data.$i.value|default:"$setting[2]"}
        </select>
        
    {elseif $setting[1] eq 'gateway'}
        <select name="{$i}" onchange="javascript: submitForm();">
        {html_options values=$gatewayValues output=$gatewayTitles selected=$data.$i.value|default:"$setting[2]"}
        </select>
        {if $i eq 'paymentGateway'}
        &nbsp;&nbsp;[ <a href="settings.php?mode=gateway">Edit Settings</a> ]
        {/if}
        
    {elseif $setting[1] eq 'image'}
        <input type=file name="{$i}" size="30">
        {if $data.$i.value}
        <tr><td valign="top"><input type=hidden name="remove_{$i}"><input type=button name=removeButton value="Remove" onClick='javascript: document.editSettings.remove_{$i}.value="1"; submitForm();'></td><td>
        Current: {$imagePaths.$i}<br />
        {img table=$smarty.const.MODULESETTINGS_TABLE field=value id=$data.$i.id maxWidth=100 maxHeight=70}
        {/if}
        
    {else}
        <input type=text 

        size=
        {if $setting[1] eq 'text'}
        30
        {elseif $setting[1] eq 'color'}
        10
        {else}
        6
        {/if} 

        name="{$i}" value="{$data.$i.value|default:$setting[2]}">

        {if $setting[1] eq 'color'}
        <a href="javascript:TCP.popup(document.forms['editSettings'].elements['{$i}'], 1)"><img src="{$docroot}images/color.gif" border=0></a>
        {/if}
    {/if}
</td>
{else}
<td colspan=2>&nbsp;</td></tr>
<td colspan=2 class=subtitle>{$setting[0]}</td></tr>
<td colspan=2>&nbsp;</td>
{/if}
</tr>

{/foreach}

<tr><td colspan=2><input type=button onClick="javascript:submitForm();" value="Submit Updates"></td></tr>

<tr><td colspan=2>&nbsp;</td></tr>

<input type=hidden name=formIsSubmitted value="">

</table>
</form>
{else}
<br />
<div class=normal>You have not permissions to edit settings.</div>
{/if}

