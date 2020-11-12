<script type="text/javascript">
<!--
var popupImage = new Object();

// create image references for any potential popup images
{foreach name=sections item=section from=$data}
popupImage[{$section.id}] = new Image();
popupImage[{$section.id}].src = '{imgsrc table=$smarty.const.SECTIONS_TABLE field=img_large id=$section.id}';
{/foreach}

function getLargeImage( id ) {ldelim}

	var width = popupImage[id].width + 50;
	var height = popupImage[id].height + 50;
	
	launchCentered('{$docroot}viewLargeImage.php?mode=page&id=' + id, width, height, 'scrollbars,resizable');

{rdelim}
//-->
</script>

{* loop through all available sections, printing data for each *}
<table border=0 cellpadding="{$settings.visible.cellpadding}" cellspacing="0" width=100%>

	{foreach name=sections key=key item=section from=$data}

		{assign var=id value=$section.id}

		<tr><td class="{$section.style}">

		{if $section.img_thumb ne "" and $section.img_thumb ne 'none'}
			
			{if $section.img_large eq "" or $section.img_large eq 'none'}
			
				{if $section.img_link}<a {if $section.img_link|replace:"javascript:":"" eq $section.img_link}target={$section.link_target|default:'_blank'}{/if} href="{$section.img_link}">{/if}<img border="0" alt="{$section.img_alt}" align="{$section.img_anchor|default:left}" src="{imgsrc table=$smarty.const.SECTIONS_TABLE field=img_thumb id=$section.id}" {* style information for this embedded image - this is not in <style> tag because whether we choose right or left pad depends on the image anchor *} style="margin-bottom:{$settings.visible.image_pad_bottom}; margin-top:{$settings.visible.image_pad_top}; {if $section.img_anchor eq 'right'}margin-left:{$settings.visible.image_pad_left};{/if} {if $section.img_anchor eq 'left' or $section.img_anchor eq ''}margin-right:{$settings.visible.image_pad_right}px;{/if}">{if $section.img_link}</a>{/if}
			
			{else}
		
				<a href="javascript:getLargeImage({$section.id});"><img alt="{$section.img_alt|default:'Click to Enlarge'}" align="{$section.img_anchor|default:left}" border="0" src="{imgsrc table=$smarty.const.SECTIONS_TABLE field=img_thumb id=$section.id}" {* style information for this embedded image - this is not in <style> tag because whether we choose right or left pad depends on the image anchor *} style="margin-bottom:{$settings.visible.image_pad_bottom}; margin-top:{$settings.visible.image_pad_top}; {if $section.img_anchor eq 'right'}margin-left:{$settings.visible.image_pad_left};{/if}  {if $section.img_anchor eq 'left' or $section.img_anchor eq ''}margin-right:{$settings.visible.image_pad_right}px;{/if}"></a>
			
			{/if}
		{/if}
		
		{if $section.content ne ""}
		{$section.content|replace:"<!--numvisitors-->":$numvisitors|replace:"<!--lastupdate-->":$lastupdate|replace:"<!--admin-->":$adminPath}
		{/if}
		
		</td></tr>
		
		{if $settings.visible.cellspacing > 0}
		<tr><td><img src="{$docroot}images/spacer.gif" width="1" height="{$settings.visible.cellspacing}" alt=""></td></tr>
		{/if}
		
	{/foreach}

</table>
