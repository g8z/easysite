{* real estate category template *}

{include file=modules/gallery/navigation.tpl}

<p class=subtitle>Edit Gallery Images</p>

{if $data}

	now showing the images that were found here!

{else}

	{include file=modules/gallery/manage/search.tpl}

{/if}