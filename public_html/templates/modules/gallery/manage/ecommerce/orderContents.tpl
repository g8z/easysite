{include file=modules/gallery/navigation.tpl}

<table class=normal cellspacing="1" cellpadding="1">

<tr><td>&nbsp;</td></tr>
<tr><td class=subtitle colspan=2>Order Details</td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td>

	<table class="normal">
	<tr><td colspan="2"><b>Number:</b> {$order.id}</td></tr>
	<tr><td><b>Created:</b> {$order.creation_date|es_date}</td><td>[ <a href="{$prevLocation}">Back to order list</a> ]</td></tr>
	<tr><td><b>Status:</b> {$order.status}</td><td>[ <a href="editOrder.php?id={$order.id}">Edit</a> ] [ <a href="orders.php?action=delete_order&id={$order.id}" onclick="javascript: return confirm( 'Are you really want to delete this order?' );">Delete</a> ]</td></tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr><td>
		<fieldset>
		<legend><b>User Details</b></legend>
		<table class="normal">
		<tr><td>Name:</td><td>{$order.first_name} {$order.last_name}</td></tr>
		<tr><td>E-Mail:</td><td><a href="mailto:{$order.email}">{$order.email}</a></td></tr>
		<tr><td>Address:</td><td>{if $order.address_2}{$order.address_2}, {/if} {$order.address_1}, {$order.city}, {if $order.state}{$order.state}, {/if}{$order.country}, {$order.zip}</td></tr>
		<tr><td>Phone:</td><td>{$order.phone|default:"n/a"}</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		</table>
		</fieldset>
	</td><td>
		<fieldset>
		<legend><b>Payment Info</b></legend>
		<table class="normal">
		<tr><td>Total Amount:</td><td>{$order.total_amount|gallery_price}</td></tr>
		<tr><td>Tax:</td><td>{$order.tax|gallery_price}</td></tr>
		<tr><td>Discount:</td><td>{$order.discount|gallery_price}</td></tr>
		<tr><td>Shipping Method:</td><td>{$order.shipping_name}({$order.shipping_price|gallery_price})</td></tr>
		<tr><td>Payment Method:</td><td>{$order.payment_method}</td></tr>
		</table>
		</fieldset>
	</td></tr>
	
	<tr><td colspan="2">
		{if $contents}
			
			<fieldset>
			<legend><b>Contents</b></legend>
			<table class="normal" width="100%">
		
		    {foreach item=product from=$contents name=iterator}
		    
		    {if $smarty.foreach.iterator.iteration is odd}
		    	 {assign var=rowColor value=$gallery.oddRowColor|default:"#FFFFFF"}
		    {else}
		   	 	 {assign var=rowColor value=$gallery.evenRowColor|default:"#EEEEEE"}
		    {/if}
		    
			<tr><td bgcolor="{$rowColor}" width="70%"><a href="{$docroot}modules/gallery/displayImage.php?id={$product.item_id}&category={$product.category}">{$product.item_title}</a></td><td bgcolor="{$rowColor}" align="center">{$product.item_count} item(s) x {$product.item_price|gallery_price}</td></tr>
			{/foreach}
			
			</table>
			</fieldset>
			
		{else}
		There is no content for this order.
		{/if}
	</td></tr>
	</table>
	
</td></tr>

</table>