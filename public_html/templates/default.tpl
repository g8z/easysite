<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<!--
EasySite Content Management System for PHP
(c) 2002 - 2004, Darren G. Gates
A layer-based system for creating and updating websites
see http://www.tufat.com/ for more information
-->

<META name="keywords" content="{$metaKeywords|default:$settings.metaKeywords}">
<META name="description" content="{$metaDescription|default:$settings.metaDescription}">
<title>{$title|default:$settings.title}</title>

<script type="text/javascript" src="{$docroot}javascript/functions.js"></script>
<script type="text/javascript" src="{$docroot}temp/{$skin_id}_{$smarty.session.site}_system.js?{$smarty.now}"></script>

{* these files are cached and read from the temp folder *}
{* original source is located in the templates dir *}
<link rel="stylesheet" type="text/css" href="{$docroot}temp/{$skin_id}_{$smarty.session.site}_areaStyles.css?{$cssLastChange}" />

{* menu initialization *}
{include file="menus/menu_init.tpl"}

<style type="text/css">
{foreach name=layers item=layer from=$layerData}
#layer_{$layer.id} 
{ldelim}
	position : absolute;
	left     : {$layer._left|default:'0'};
	top      : {$layer.top|default:'0'}; 
	height   : {$layer.height|default:'auto'}; 
	width    : {$layer.width|default:'0'}; 
	z-index  : {$layer.zorder|default:'0'};
{rdelim}
#layer_{$layer.id}_image
{ldelim}
	margin-bottom:{$settings.visible.image_pad_bottom};
	margin-top:{$settings.visible.image_pad_top};
	{if $layer.img_anchor}float: {$layer.img_anchor};{/if}
	{if $layer.img_anchor eq 'right'}margin-left:{$settings.visible.image_pad_left};{/if} 
	{if $layer.img_anchor eq 'left'}margin-right:{$settings.visible.image_pad_right}px;{/if}
{rdelim}
{/foreach}
</style>

</head>

{literal}
<body onload="javascript: if ( navigator.appVersion.indexOf('MSIE')==-1 ) {document.getElementById('visible').style.border='0px;'; document.getElementById('a8').style.border='0px;';}">
{/literal}


{* start of body section *}

<table style="position: relative; width: {$settings.visible.width}; margin: 0px {if $settings.visible.auto_center eq 'yes'}auto{/if};  {if $settings.visible.height eq 'screen'}height: 100%;{/if}" cellpadding="0" cellspacing="0" border="0">
<tr><td valign="top">

<div id="visible">
<div style="margin-bottom:-1px"><img src="about:blank" width="0" height="0" alt=""></div>

<div id="main">

  <div id="content">
  
    {* show error if install.php file exists *}
    {if $installFileError}{include file="installError.tpl"}{/if}

    {include file="$bodyTemplate"}
    
  </div>

{if $settings.footer.parent eq 'visible'}
</div>
{/if}

{* footer *}
<div id="footer" class="{$settings.footer.style|default:'normal'}">{$settings.footer.content|nl2br}</div>
<div id="a7_clearer"></div>

{if $settings.footer.parent eq 'main'}
</div>
{/if}


{* top color bar *}
<div id="a5"></div>
{* left color bar *}
<div id="a8"></div>
{* bottom color bar *}
<div id="a7"></div>
{* right color bar *}
<div id="a6"></div>

<div id="a1"></div>
<div id="a2"></div>
<div id="a3"></div>
<div id="a4"></div>

{* global layers for this site key *}
{foreach name=layers item=layer from=$layerData}

<div id="layer_{$layer.id}">
	<table cellpadding="0" cellspacing="{$layer.padding}" width="100%" {if $layer.bgcolor ne ""}bgcolor="{$layer.bgcolor}"{/if} {if $layer.height}height="{$layer.height}"{/if} class="{$layer.style}">	
	<tr><td align="{$layer.align}" valign="{$layer.valign}">
		{if $layer.img_thumb ne "" and $layer.img_thumb ne 'none'}

			<div id="layer_{$layer.id}_image">
			{if $layer.img_large ne "" and $layer.img_large ne 'none'}
				<a href="javascript:getLayerLargeImage({$layer.id});">
				  <img alt="{$layer.img_alt|default:'Click to Enlarge'}" border="0" src="{imgsrc table=$smarty.const.LAYERS_TABLE field=img_thumb id=$layer.id}">
				</a>
			{else}
				{if $layer.img_link}<a {if $layer.img_link|replace:"javascript:":"" eq $layer.img_link}target={$layer.link_target|default:'_blank'}{/if} href="{$layer.img_link}">{/if}
				<img border="0"  alt="{$layer.img_alt}" src="{imgsrc table=$smarty.const.LAYERS_TABLE field=img_thumb id=$layer.id}">
				{if $layer.img_link}</a>{/if}
			{/if}
			</div>
		{/if}
		{$layer.content}
	</td></tr></table>
</div>
{/foreach}
{* end of global layers for this site key *}


{include file="menus/menu_draw.tpl"}
</div>

</td></tr></table>
{* end of body section *}

{if $treeMenuExists}
<script type="text/javascript">
	RedrawAllTrees();
</script>
{/if}

</body>
</html>
