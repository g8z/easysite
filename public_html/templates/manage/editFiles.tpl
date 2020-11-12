{* special js functions for this page only *}
{literal}
<script language="Javascript">
<!--

function deleteSection(id) {
    if ( confirm( 'Are you sure? This file will be PERMANENTLY removed!' ) ) {
        document.editPage.deleteSectionVar.value = id;
        submitForm();
    }
}


function isValid() {
    var theForm = document.editPage;
    var formElements = theForm.elements;
    var numElements = theForm.elements.length;
    
    for ( var i = 0; i < numElements; i++ ) {
        var elemName = theForm.elements[i].name;
        var elemValue = theForm.elements[i].value.trim();
        
        if ( elemName.indexOf( "file_data_" ) != -1 && elemValue != "" ) {
            // assumes that the hidden input field to hold the 
            // path is IMMEDIATELY after the file field!
            theForm.elements[i + 1].value = elemValue;
            i++
        }
        
    }
       
    return true;
}



function submitForm() {
    // determine paths for all image uploads
    if ( !isValid() )
        return false;
    
    document.editPage.formIsSubmitted.value = 1;
    
    // submit the form
    document.editPage.submit();
}
//-->
</script>
{/literal}



{* loop through all available sections, printing data for each *}
<form action=editFiles.php method="POST" enctype="multipart/form-data" name="editPage">

<input type=hidden name=deleteSectionVar value="">
<input type=hidden name=site_key value="{$site_key}">
<input type=hidden name=formIsSubmitted value="">
<input type=hidden name=skin_id value="{$skin_id}">

<table border=0 cellpadding=0 cellspacing=3 width=100% class=normal>

{foreach name=sections item=section from=$data}

{* if first iteration, then display the 'Add' title *}

{if $smarty.foreach.sections.iteration eq 1}

    <tr>
    <td class=normal>
    {$logoutLink} {$pathway}
    </td>
    </tr>
    
    <tr><td>&nbsp;</td></tr>
    
        <tr>
        <td>
        <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr><td class=title>Edit Files</td>

        
        </tr>
        </table>
        </td>
        </tr>

		<tr><td>Depending on your server's settings, there may be a maximum upload size limit in place. For most servers, the size limit is about 1000 KB (1 MB). You may increase this by using a larger max_allowed_packet setting in your database configuration.</td></tr>

		<tr><td>&nbsp;</td></tr>
    <tr>
    <td class=subtitle>Add a New File</td>
    </tr>
{elseif $smarty.foreach.sections.iteration eq 2}
    <tr><td><hr width='100%' noshade size=5 color="#FF0000"></td></tr>
    <tr>
    <td class=subtitle>Edit Current Files</td>
    </tr>
{/if}
  
    <tr><td>
    <table border=0 cellpadding=1 cellspacing=0 class=normal>
	    {if $section.shared}
	    <tr><td colspan="2"><b>[ Shared ]</b></td></tr>
	    {/if}
	    
        <tr>
        <td nowrap align=right>File to Upload: </td>
        <td nowrap><input type=file name="file_data_{$section.id}"> 
        <a href="javascript:launchCentered('{$help.url}?type=file',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
        <input type=hidden size=30 name=file_data_path_{$section.id} value=""> {* ({$max} KB max) *}
        </td>
        </tr>
        
        <tr>
        <td nowrap align=right>Download Name: </td>
        <td><input size=30 type=text name="download_name_{$section.id}" value="{$section.download_name}"> 
        <a href="javascript:launchCentered('{$help.url}?type=download_name',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
        </td>
        </tr>
        
        {* only display the following items if we actually have a file to download *}
        {if $smarty.foreach.sections.iteration ne 1}
            <tr>
            {* this is automatically generated *}
            <td align=right>Download Link: </td>
            <td><a target=_blank href="{$objectPath}&id={$section.id}">{$objectPath}&id={$section.id}</a></td>
            </tr>

            <tr>
            <td align=right valign=top>Local File Path: </td>
            <td><small><i>{$section.file_data_path|default:"(no file present)"}</small></i>
            </td>
            </tr>
            
             <tr>
             <td align=right valign=top># Downloads: </td>
             <td>{$section.counter|default:"0"}</i>
             </td>
            </tr>
        {/if}

        </table>
    
    </td>
    </tr>
    
    <tr>
    <td>
    
        <input type=submit name={if $smarty.foreach.sections.iteration ne 1}masterSubmit{else}addNewItem{/if} value="Submit All" onClick="javascript:submitForm();">
        
        <input type=reset value="Reset All">
    
        {if $smarty.foreach.sections.iteration ne 1}
            <input type=button name=delete_{$section.id} value="Delete" onClick="javascript:deleteSection({$section.id});"> 
        {/if}
        

    </td>
    </tr>

    <tr><td><hr style='width: 100%' noshade width=1 color="#0000FF"></td></tr>

{/foreach}

    <tr>
    <td class=normal>
    {$logoutLink} {$pathway}
    </td>
    </tr>

</table>    

</form>
    
    