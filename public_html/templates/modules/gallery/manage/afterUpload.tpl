{include file=modules/gallery/navigation.tpl}
<br />
<span class=normal>
{section name=message loop=$count}
{$messages[message][0]}<br />
{/section}
</span>

<br />
<span class="normal">
You may now <a href="items.php?galleryAction=search_images&searchId={$searchId}">specify additional attributes</a> for these items, like price and quality, if you are using this module in e-commerce mode, or the image description and order, if you are using this module in standard gallery mode.
</span>