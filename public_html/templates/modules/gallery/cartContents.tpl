<div id="cart_contents" style="position: relative;">
{if $cartContents}
<table class=normal cellspacing="{$gallery.cellSpacing|default:'2'}" cellpadding="{$gallery.cellPadding|default:'2'}" width="100%" border="0">
<tr>
  <th width=10% class="cartHeader">{$gallery.removeTitle|default:"Remove"}</th>
  <th align="left" class="cartHeader" width="{$gallery.titleWidth}">{$gallery.itemTitle|default:"Item"}</th>
  {if $gallery.showAttributes eq 'yes'}
  <th align="center" class="cartHeader">Attributes</th>
  {/if}
  <th width="10%" class="cartHeader">{$gallery.quantityTitle|default:"Quantity"}</th>
  <th width="10%" align="right" class="cartHeader">{$gallery.priceTitle|default:"Price"}</th>
</tr>

{foreach item=product from=$cartContents name=iterator}

{if $smarty.foreach.iterator.iteration is odd}
	{assign var=rowColor value=$gallery.oddRowColor|default:"#EEEEFF"}
{else}
	{assign var=rowColor value=$gallery.evenRowColor|default:"#EEEEEE"}
{/if}

<tr>
  
  <td align=center bgcolor='{$rowColor|default:"$defaultRowColor"}' valign="top">
    <input type="checkbox" name="deleteItem[]" id="deleteItem[]" value="{$product.cart_id}">
  </td>
  
  <td bgcolor='{$rowColor|default:"$defaultRowColor"}' valign="top">
  
  	{capture name="title"}
  	<tr><td align="{$gallery.titleAlign|default:'left'}">{if $gallery.titleLink neq 'no'}<a href="{$docroot}modules/gallery/displayImage.php?id={$product.id}&category={$product.cat_id}">{$product.title}</a>{else}{$product.title}{/if}</td></tr>
  	{/capture}
  	
  	{if $gallery.showThumbnail neq 'no'}
  	{capture name="thumbnail"}
  	<tr><td><img src="{$product.thumbnail}" {if $createThumb eq 'no'}width="{$gallery.thumbnailWidth}"{/if}></td></tr>
  	{/capture}
  	{/if}
  	
  	<table class="normal" cellspacing="1">
  	{if $gallery.titleTPosition|default:'below' eq 'above'}{$smarty.capture.title}{/if}
  	{if $smarty.capture.thumbnail}{$smarty.capture.thumbnail}{/if}
  	{if $gallery.titleTPosition|default:'below' eq 'below'}{$smarty.capture.title}{/if}
  	</table>
  	
  </td>
  
  {if $gallery.showAttributes eq 'yes'}
  <td bgcolor='{$rowColor|default:"$defaultRowColor"}' valign="top">
  <table cellspacing="1" class="{$gallery.styleAttributes|default:'normal'}">
  {foreach from=$product.attributes item=att}
  {if $att.title}<tr><td><b>{$att.title}: </b></td><td>{$att.value}</td></tr>{/if}
  {/foreach}
  </table>
  </td>
  {/if}
  
  <td bgcolor='{$rowColor|default:"$defaultRowColor"}' valign="top">
    <input type=text size=3 name="count_{$product.cart_id}" value="{$product.count}">
  </td>
  
  <td align="right" bgcolor='{$rowColor|default:"$defaultRowColor"}' nowrap valign="top">
    <b>{$product.price|gallery_price}</b>
  </td>
  
</tr>
{/foreach}

<tr><td colspan="4">&nbsp;</td></tr>

{if $gallery.showAttributes eq 'yes'}
{assign var="clspn" value="4"}
{else}
{assign var="clspn" value="3"}
{/if}

{if $gallery.showSubtotal neq 'no'}
<tr>
  <td align=right colspan="{$clspn}" class="{$gallery.styleSubtotal|default:'normal'}">
    {$gallery.subtotalTitle|default:"Sub-Total:"}
  </td>
  
  <td align="right">
    {$subtotal|gallery_price}
  </td>
</tr>
{/if}

{if $gallery.showDiscount neq 'no'}
<tr>
  <td align=right colspan="{$clspn}" class="{$gallery.styleDiscount|default:'normal'}">
    {$gallery.discountTitle|default:"Discount (\$value%):"|replace:"\$value":$discountPercent}
  </td>
  <td align="right" class="{$gallery.styleDicount|default:'normal'}">
    {$discountPrice|gallery_price}
  </td>
</tr>
{/if}

{if $gallery.showTax neq 'no'}
<tr>
  <td align=right colspan="{$clspn}" class="{$gallery.styleTax|default:'normal'}">
    {$gallery.taxTitle|default:"Tax (\$value%):"|replace:"\$value":$gallery.tax}
  </td>
  <td align="right" class="{$gallery.styleTax|default:'normal'}">
    {$tax|gallery_price}</td>
  </tr>
{/if}

<tr>
  <td align=right colspan="{$clspn}" class="{$gallery.styleGrandtotal|default:'normal'}">
    {$gallery.grandTotalTitle|default:"Grand Total:"}
  </td>
  <td align="right" class="{$gallery.styleGrandtotal|default:'normal'}">
    {$totalPrice|gallery_price}
  </td>
</tr>

</table>

{else}
<div class="normal">There are no items in your shopping cart.</div>
{/if}
</div>