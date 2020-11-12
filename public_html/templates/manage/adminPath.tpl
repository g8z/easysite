{if $path}
{foreach from=$path item=pathItem}

{*
	{if $currentZone neq $pathItem.zone}
	<a href="{$pathItem.url}">{$pathItem.title}</a> / 
	{else}
	{$pathItem.title} / 
	{/if}
*}	
	<a href="{$pathItem.url}">{$pathItem.title}</a> / 
	
{/foreach}

{/if}