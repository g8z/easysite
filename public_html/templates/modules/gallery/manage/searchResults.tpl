{include file=modules/gallery/navigation.tpl}

{literal}
<script language="javascript">
<!--

function submitLink( action ) {
    document.mainForm.galleryAction.value = action;
    document.mainForm.submit();
    return true;
}
function selectAll( allBox ) {
    
    var el = document.mainForm.elements;
    var length = el.length;

    for ( i=0; i<length; i++) {
        if ( el[i].name.substr(0, 2) == 'ch' ) {
            el[i].checked = allBox.checked;
        }
    }
}

-->
</script>
{/literal}

<form name=mainForm method=post action=items.php>

{foreach from=$prevPost item=post}
<input type=hidden name={$post.name} value={$post.value}>
{/foreach}

<input type=hidden name=galleryAction value=''>

<span class="normal">{$navigation}</span><br />

<table width="100%" cellspacing=0 cellpadding=3 class=normal>
<tr bgcolor="#cccccc">
<td>
{if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery and $smarty.session.cm_auth.cm_gallery_delete_images}
<input type=checkbox name=all value=1 onclick="javascript:selectAll( this );"></td>
{/if}
<th>Title</th><th>Category</th><th>Action</th>
</tr>

{if $images_list}
    {$images_list}
{else}
    <tr bgcolor="#eeeeee"><td colspan=4>No Images Found.</td></tr>
{/if}

</table>

{if $images_list and $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery and $smarty.session.cm_auth.cm_gallery_delete_images}
    <input type=button name=delete value="Delete Selected" onclick="javascript: if ( confirm( 'Are you really want to delete selected images?' ) ) submitLink( 'delete_selected' ); ">
{/if}
</form>
