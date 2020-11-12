{* special js functions for this page only *}
{literal}
<script language="Javascript">
<!--

function submitForm() {
    document.editPage.formIsSubmitted.value = 1;
    
    // submit the form
    document.editPage.submit();
}


function deleteSection(id) {
    if ( confirm( 'Are you sure? All of the items in this section will be PERMANENTLY removed!' ) ) {
        document.editPage.deleteSectionVar.value = id;
        submitForm();
    }
}


function bumpUpSection(id) {
    document.editPage.bumpUpSectionVar.value = id;
    submitForm();
}



//-->
</script>
{/literal}


{* loop through all available sections, printing data for each *}
<form action=editRadioGroups.php method="POST" enctype="multipart/form-data" name="editPage">
<input type=hidden name=bumpUpSectionVar value="">
<input type=hidden name=deleteSectionVar value="">
<input type=hidden name=radioGroup value="{$radioGroup}">
<input type=hidden name=formIsSubmitted value="">
<table border=0 cellpadding=0 cellspacing=3 width=100% class=normal>

{foreach name=sections item=section from=$data}

{if $smarty.foreach.sections.iteration eq 1}

    <tr>
    <td class=normal>
    {$logoutLink} {$pathway}
    </td>
    </tr>
    
    <tr><td>&nbsp;</td></tr>

    <tr>
    <td class=subtitle>Add a New Radio Group</td>
    </tr>

    {elseif $smarty.foreach.sections.iteration eq 2}
    <tr><td><hr width='100%' noshade size=5 color="#FF0000"></td></tr>
    <tr>
    <td class=subtitle>Edit Current Radio Group</td>
    </tr>
    {/if}

    <tr>
    <td>
        <table border=0 cellpadding=1 cellspacing=0 class=normal>
            <tr>
            <td align=right>Label: </td><td><input type=text size=15 name=label_{$section.id} value="{$section.label}"></td>
            <td align=right>Value: </td><td><input type=text size=10 name=value_{$section.id} value="{$section.value}"></td>
            </tr>
            
            <tr>
            <td align=right>Orientation: </td>
            
            <td>
            <select name=orientation_{$section.id}>
            {html_options values="$orientationValues" output="$orientationLabels" selected=$section.orientation}
            </select>
            </td>
            
            <td align=right>Selected?</td><td><input type=radio name=selectedGroupItem value={$section.id} {if $section.selected eq 1}checked{/if}></td>
            </tr>
        </table>
    </td>
    </tr>

    <tr>
    <td>
    
        <input type=submit name={if $smarty.foreach.sections.iteration ne 1}masterSubmit{else}addNewItem{/if} value="Submit All" onClick="javascript:submitForm();">
        
        <input type=reset value="Reset All">
    
        {if $smarty.foreach.sections.iteration ne 1}
            <input type=button name=delete_{$section.id} value="Delete" onClick="javascript:deleteSection({$section.id});"> 
                    
            {if $files eq "" and $layers eq "" and $smarty.foreach.sections.iteration > 2}
                <input type=button name=bump_{$section.id} value="Bump Up" onClick="javascript:bumpUpSection({$section.id});">
            {/if}
        
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

</form>

</table>        