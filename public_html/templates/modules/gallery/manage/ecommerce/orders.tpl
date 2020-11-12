{include file=modules/gallery/navigation.tpl}

<table class=normal cellspacing="1" cellpadding="1">

<tr><td>&nbsp;</td></tr>
<tr><td class=subtitle colspan=2>Orders Manager</td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td>This tool allows you to edit and view customer order information. You may also create more advanced reports from these orders using the <a href={$docroot}manage/reports.php>Reports Tool</a>.</td></tr>
<tr><td>&nbsp;</td></tr>

{if $orders}

	<tr><td width="100%">
		<table cellpadding="0" cellspacing="0" class="normal" width="100%">
		<tr>
			<td align="left">{$navigation}</td>
			<td align="right">Items Per Page: <select name="perPage">{html_options values=$perPageArray output=$perPageArray selected=$perPage}</select></td>
		</tr>
		</table>
	</td></tr>
	
	<tr><td>

	<table class="normal" width="100%">
	<tr>
	
	{foreach from=$headers item=header}
	<th bgcolor="#333333" align="left" style="color: #DDDDDD">{$header.title}</a>&nbsp;
	
		{if $header.sort_symbol eq '&#9650;'}
			<span style="color: yellow">&#9650;</span>
		{else}
			<a href="{$header.url}&direction=0" style="color: #DDDDDD; text-decoration:none;" title="Reorder">&#9650;</a>
		{/if}
		
		{if $header.sort_symbol eq '&#9660;'}
			<span style="color: yellow">&#9660;</span>
		{else}
			<a href="{$header.url}&direction=1" style="color: #DDDDDD; text-decoration:none;" title="Reorder">&#9660;</a>
		{/if}
		
	</th>
	{/foreach}
	
	<th bgcolor="#333333" style="color: #DDDDDD">&nbsp;</th>
	</tr>

    {foreach item=order from=$orders name=iterator}
    
    {if $smarty.foreach.iterator.iteration is odd}
    	 {assign var=rowColor value=$gallery.oddRowColor|default:"#EEEEFF"}
    {else}
   	 	 {assign var=rowColor value=$gallery.evenRowColor|default:"#EEEEEE"}
    {/if}
    
	<tr><td bgcolor="{$rowColor}">{$order.first_name} {$order.last_name}</td><td bgcolor="{$rowColor}" align="right">{$order.total_amount|gallery_price}</td><td bgcolor="{$rowColor}" align="center">{$order.created|es_date}</td><td bgcolor="{$rowColor}" align="center">{$order.status}</td><td width=160 nowrap bgcolor="{$rowColor}">[ <a href="orderContents.php?id={$order.id}">Details</a> ] [ <a href="editOrder.php?id={$order.id}">Edit</a> ] [ <a href="orders.php?action=delete_order&id={$order.id}" onclick="javascript: return confirm( 'Are you really want to delete this order?' );">Delete</a> ]</td></tr>
	{/foreach}
	
	</table>
	
	</td></tr>
{else}
	<tr><td colspan=2>No orders have been recorded.</td></tr>
{/if}

</table>