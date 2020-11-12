{* popup image browser *}

<title>Image Viewer</title>

<div align=center>

<span class=normal>[ <a href="javascript:close();">Close Window</a> ]</span>

{if $isNavigable}
<p class=normal>{$navigation}</p>
{/if}

<p class=normal><img src="{imgsrc id=$data.id table=$smarty.const.MODULEOBJECTS_TABLE field=data}"></p>

{if $data.title}
<p class=normal>{$data.title}<p>
{/if}

{if $data.description}
<p class=normal>{$data.description}</p>
{/if}

</div>