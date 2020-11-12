{include file=modules/gallery/navigation.tpl}
<br />
<form name=images method=post name=mainForm>
<input type=hidden name=galleryAction value="search_images">
<table class=normal>
    <tr><td colspan=2 class="subtitle">Search Items to Edit</td></tr>
    <tr><td>Category: </td>
        <td nowrap><select name=cat_id><option value='0'>- Any Category -</option>{html_options options=$searchCategories}</select>&nbsp;
            <input type=checkbox name=subCategories value=1 checked>Include Subcategories
        </td>
    </tr>
    <tr><td>Image Title Contains: </td><td><input type=text name=title size=29></td></tr>
    <tr><td>Description Contains: </td><td><input type=text name=description size=29></td></tr>
    <tr><td>Added After:</td><td>{html_select_date prefix=Start_ time=$minDate}</td></tr>
    
    <tr><td>Added Before:</td><td>{html_select_date prefix=End_}</td></tr>
    
    {if $ecommerceFields}
    {foreach from=$ecommerceFields item=field}
    	<tr><td {if $field.type eq 'list'}valign="top"{/if}>{$field.name}{if $field.type eq 'list'}<br /><small>leave un-selected to search all</small>{/if}</td><td>
	    {if $field.type eq 'number'}
	    	Between <input type=text name="custom_{$field.id}_start" size=5> and <input type=text name="custom_{$field.id}_end" size=5>
	    {elseif $field.type eq 'single-text' or $field.type eq 'multi-text'}
	    	<input type=text name="custom_{$field.id}">
	    {elseif $field.type eq 'date'}
	    	{html_select_date prefix=custom_`$field.id`_ start_year=-10 end_year=+10 time=0000-00-00 day_extra="><option value=''>Day</option" month_extra="><option value=''>Month</option" year_extra="><option value=''>Year</option"}
	    {elseif $field.type eq 'list'}
	    	{list key=$field.listName name=custom_`$field.id` extra="multiple"}
	    {/if}
	    </td></tr>
    {/foreach}
    {/if}
    
    <tr><td>Order By:</td><td><select name=order>{html_options values=$orderValues output=$orderTitles}</select> <select name="direction">{html_options values=$direction output=$direction}</select></td></tr>
    <tr><td>Results Per Page:</td><td><select name=perPage>{html_options values=$perPageList output=$perPageList selected=50}</select></td></tr>
    
    <tr><td>&nbsp;</td><td><input type=submit name=search value=Display></td></tr>
</table>
</form>