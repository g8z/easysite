<!-- for menu links search engine spider indexing -->
<div id='links{$menuId}' style="position: absolute; left:-5000px; top: 0px;">
{foreach name=links item=link from=$menuLinks}
{if $link}<a href="{$link}">{$link}</a><br />
{/if}
{/foreach}
</div>