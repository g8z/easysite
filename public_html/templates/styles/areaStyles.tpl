
html, body
{ldelim}
  margin: 0px;
  padding: 0px;
  height: 100%;
  min-height: 100%;
  {if $settings.visible.auto_center eq 'yes'}text-align: center;{/if}
{rdelim}


* html #visible
{ldelim}
	height: 100%;
	{if $smarty.server.HTTP_USER_AGENT|replace:"Mozilla":"" neq $smarty.server.HTTP_USER_AGENT}
		{* this is a mozilla-based browser *}
		overflow: hidden;
	{else}
		overflow: visible;
	{/if}
{rdelim}


body 
{ldelim}
	{assign var="s" value=$settings.screen}
	{include file="styles/areaBit.tpl"}
{rdelim}

* html #visible
{ldelim}
	height: 100%;
{rdelim}

#visible 
{ldelim}
	margin: 0px;
	padding: 0px;
	position: relative;
	min-height: 100%;
	height: {if $settings.visible.height eq 'screen'}100%{else}inherit{/if};
	text-align: left;
	width: 100%;
	
	{assign var="s" value=$settings.visible}
	{include file="styles/areaBit.tpl"}
{rdelim}



#main 
{ldelim}
	position: relative;
	margin-left: {$settings.main.left}px;
	margin-top: {$settings.main.top}px;
	width:{$settings.main.width};
	z-index: {$settings.main.zindex};
{rdelim}

div#content
{ldelim}
	position: relative;
	padding: {$settings.main.padding}px;
	min-height:{$settings.main.height};
	
	{assign var="s" value=$settings.main}
	{include file="styles/areaBit.tpl"}
{rdelim}

* html #content 
{ldelim}
	height:{$settings.main.height};
{rdelim}


#a7_clearer
{ldelim}
	position: relative;
	font-size: 0px;
	height: {$settings.footer.margin_bottom};
{rdelim}



#footer
{ldelim}
	position: relative;
	margin-top: {$settings.footer.margin_top|default:'0'};
	margin-left: {$settings.footer.margin_left|default:'0'};
	margin-right: {$settings.footer.margin_right|default:'0'};
	padding: {$settings.footer.padding}px;
	min-height: {$settings.footer.height};
	text-align: {$settings.footer.align|default:'center'};
	{assign var="s" value=$settings.footer}
	{include file="styles/areaBit.tpl"}
{rdelim}

* html #footer
{ldelim}
	height: {$settings.footer.height};
{rdelim}

#a1, #a2, #a3, #a4, #a5, #a6, #a7, #a8
{ldelim}
	position: absolute; 
{rdelim}

#a8, #a6 
{ldelim}
	min-height: 100%;
	height: 100%;
	top: 0px;
{rdelim}

#a5 /* top color bar */
{ldelim}
	top: 0px; 
	width: 100%;
	font-size: 0px; /* for IE */
	height: {$settings.a5.height};
	{assign var="s" value=$settings.a5}
	{include file="styles/areaBit.tpl"}
{rdelim}

#a6 /* right color bar */
{ldelim}
	right: 0px; 
	width: {$settings.a6.width};
	{assign var="s" value=$settings.a6}
	{include file="styles/areaBit.tpl"}
{rdelim}

#a7 /* bottom color bar */
{ldelim}
	font-size: 0px; /* for IE */
	bottom: -1px; 
	width: 100%;
	height: {$settings.a7.height};
	{assign var="s" value=$settings.a7}
	{include file="styles/areaBit.tpl"}
{rdelim}


#a8 /* left color bar */
{ldelim}
	left: 0px;
	width: {$settings.a8.width};
	{assign var="s" value=$settings.a8}
	{include file="styles/areaBit.tpl"}
{rdelim}

#a1 /* top-left corner */
{ldelim}
	top: 0px;
	left: 0px;
	{if $settings.a1.width neq ""}width: {$settings.a1.width};{/if}
	{if $settings.a1.height neq ""}height: {$settings.a1.height};{/if}
	{assign var="s" value=$settings.a1}
	{include file="styles/areaBit.tpl"}
{rdelim}

#a2 /* top-right corner */
{ldelim}
	top: 0px;
	right: 0px;
	width: {$settings.a2.width};
	height: {$settings.a2.height};
	{assign var="s" value=$settings.a2}
	{include file="styles/areaBit.tpl"}
{rdelim}

#a3 /* bottom-right corner */
{ldelim}
	bottom: -1px;
	right: 0px;
	width: {$settings.a3.width};
	height: {$settings.a3.height};
	{assign var="s" value=$settings.a3}
	{include file="styles/areaBit.tpl"}
{rdelim}

#a4 /* bottom-left corner */
{ldelim}
	bottom: -1px;
	left: 0px;
	width: {$settings.a4.width};
	height: {$settings.a4.height};
	{assign var="s" value=$settings.a4}
	{include file="styles/areaBit.tpl"}
{rdelim}

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
font-weight: normal;
{/if}
{rdelim}

{if $cssStyle.name neq 'a' and $cssStyle.name neq 'a:hover'}a{$cssStyle.name}:hover {ldelim}
text-decoration: underline;	
{rdelim}

{/if}
{/foreach}
{* end of style dumping *}
