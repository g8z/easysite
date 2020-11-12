{include file=modules/gallery/navigation.tpl}

{if $messages}
<br />
{foreach from=$messages item=message}
<div class="normal" style="color: red;">{$message[0]}</div>
{/foreach}
{/if}

{if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery and $smarty.session.cm_auth.cm_gallery_add_images}

{literal}
<script language="Javascript">
function addFromFolder() {
    var formObj = document.mainForm;
    
    if ( confirm( 'Are you sure? This will add ALL images in this directory and/or sub-directories, which could take time if you have many images.' ) ) {
        formObj.galleryAction.value = 'add_from_folder';
        formObj.submit();
    }
}
</script>
{/literal}
<br />
<form enctype="multipart/form-data" name=mainForm method=post>
<input type=hidden name=galleryAction value="upload_process">
<table border=0 cellpadding=1 cellspacing=0 class=normal>
    
	<tr><td>Upload to:</td><td>
    <select name=category>{if !$categories}<option value="0">- No Categories Defined -</option>{else}{html_options options=$categories}{/if}</select>
  
    {if $noCategories}
    [ no categories have been defined ]
    {/if}
  
    </td></tr>
    
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan=2 class=subtitle>Upload Items by Image & Title</td></tr>
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan=2>To add items you must provide a title and image. If no image is specified, then the system default image will be used. {if $gdInstalled and $gallery.createThumb eq 'yes'}The system will attempt to create thumbnails for the new items from these uploaded images.{/if}</td></tr>
    <tr><td colspan=2>&nbsp;</td></tr>
    
    <tr><td nowrap>Images to upload:</td><td>
    
    <select onchange="javascript:document.mainForm.action.value='change_upload_count'; document.mainForm.submit();" name=upload_count>{html_options options=$uploadCounts selected=$count}</select>
    
    </td></tr>
    
    {section loop=$count+1 name=current start=1}
    
    <tr><td colspan=2><img src="{$docroot}images/spacer.gif" width=1 height=5></td></tr>
    
    <tr><td>Image {$uploadCounts[current]}:</td><td><input type=file name="image_{$uploadCounts[current]}" size=30></td></tr>
    
    <tr><td>Title:</td><td><input type=text name="title_{$uploadCounts[current]}" size=50></td></tr>
    
    {/section}
    
    <tr><td colspan=2><input type=submit name=upload value="Upload"></td></tr>
    
    {* Add from folder feature has been temporarily disabled because it could be a security risk *}
    {*
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan=2 class=subtitle>Add from folder</td></tr>
    
    <tr><td valign=top>Folder:</td><td><input type=text name=folder value="{$fullPath}" size=50><br />
    <input type=checkbox name=subFolder value="1" checked>Include SubFolders</input></td>
    
    </tr>
    <tr><td colspan=2>
        <input type=button name=add value="Add From Folder" onClick="javascript:addFromFolder();">
    </td></tr>
    *}
    
</table>

</form>

{else}
<br />
<div class=normal>You have not permissions to upload images.</div>
{/if}