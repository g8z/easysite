{* dump the styles onto the page *}
{foreach name=cssStyles item=cssStyle from=$cssStyles}

{$cssStyle.name} {ldelim}
{if $cssStyle.font neq '--'}
font-family: {$cssStyle.font};
{/if}
{if $cssStyle.size}
font-size: {$cssStyle.size}pt;
{/if}
color: {$cssStyle.color};
{if $cssStyle.bg_color ne ""}
background-color: {$cssStyle.bg_color};
{/if}
{if $cssStyle.italic ne 0}
font-style: italic;	
{else}
font-style: normal;
{/if}
{if $cssStyle.underline ne 0}
text-decoration: underline;	
{else}
text-decoration: none;
{/if}
{if $cssStyle.bold ne 0}
font-weight: bold;	
{else}
font-weight: none;
{/if}
{rdelim}

{if $cssStyle.name neq 'a' and $cssStyle.name neq 'a:hover'}a.{$cssStyle.name}:hover {ldelim}
text-decoration: underline;	
{rdelim}

{/if}
{/foreach}
{* end of style dumping *}