
var layerPopupImage = new Object();

// create image references for any potential popup images
{foreach name=sections item=section from=$layerData}
{if $section.img_large ne ""}
layerPopupImage[{$section.id}] = new Image();
layerPopupImage[{$section.id}].src = '{imgsrc table=$smarty.const.LAYERS_TABLE field=img_large id=$section.id}';
{/if}
{/foreach}

function getLayerLargeImage( id ) {ldelim}

	var w = layerPopupImage[id].width + 50;
	var h = layerPopupImage[id].height + 50;
	
	launchCentered('{$docroot}viewLargeImage.php?mode=layer&id=' + id, w, h, 'scrollbars,resizable');

{rdelim}


{* check for any polls that we should popup *}
{foreach item=p from=$activePolls}
launchCentered( '{$docroot}{$smarty.const.MODULES_DIR}/poll/poll.php?id={$p.id}', {$p.width|default:600}, {$p.height|default:500}, 'resizable,scrollbars' );
{/foreach}
{* end of check for polls *}