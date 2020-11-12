{* special js functions for this page only *}
{literal}
<script language="Javascript">
<!--

function deleteSection( groupId ) {
    var theForm = document.fiterOverrides;
    
    theForm.action.value='delete';
    theForm.deleteSectionVar.value=groupId;
    
    theForm.submit();
}

// -->
</script>
{/literal}

<table border=0 cellpadding=0 cellspacing=3 width=100% class=normal>
<form action=editOverrides.php method="POST" enctype="multipart/form-data" name="fiterOverrides">
<input type=hidden name=deleteSectionVar value="">
<input type=hidden name=form_id value="{$formId}">
<input type=hidden name=action value="update">

<tr><td class=normal>{$logoutLink} {$pathway}</td></tr>

<tr><td>&nbsp;</td></tr>
    
<tr><td class=title>Report Search Filters</td></tr>
<tr><td class=subtitle>for the form called "{$formTitle}"</td></tr>
    
<tr><td>&nbsp;</td></tr>
    
<tr><td>You may use this form as a "search form" for the report that it redirects to. This means that the form inputs will OVERRIDE the report filters which have been set in the report tool. For example, if a report filter is "price > 100", and the user inputs a value for the "price" field, then upon submission of the form, the report will use the user's input as the NEW price filter: price > {ldelim}user input{rdelim}.</td></tr>

<tr><td>&nbsp;</td></tr>
    
<tr><td>
    <table border=0 cellpadding=1 cellspacing=0 class=normal>

    {foreach name=sections item=section from=$filterOverrides}
    
        {if $smarty.foreach.sections.iteration eq 2}
        <tr><td colspan=2 class=subtitle><br />Edit Current Filter:</td></tr>
        <tr><td colspan=2>Display report data</td></tr>
        {elseif $smarty.foreach.sections.iteration eq 1}
        <tr><td colspan=2 class=subtitle>Add New Filter</td></tr>
        <tr><td colspan=2>Display report data where...</td></tr>
        {/if}
        
        <tr><td valign=top>
        {if $smarty.foreach.sections.iteration neq 1}<input type=button name=delete_section_{$section.id} value=' X ' onclick="javascript: deleteSection( '{$section.id}');">{/if}
        <select name=report_field_id_{$section.id}>
        {html_options output=$reportFieldTitles values=$reportFieldIds selected=$section.report_field_id}
        </select>&nbsp;
        
        </td><td valign=top>
        
        <select name=condition_{$section.id}>
        {html_options output=$conditions values=$conditions selected=$section.condition}
        </select>&nbsp;
        
        <select name=section_id_{$section.id}>
        {html_options output=$formFieldTitles values=$formFieldIds selected=$section.section_id}
        </select> 
        </td></tr>
        
        <tr><td>&nbsp;</td><td><input type="checkbox" name="skip_empty_{$section.id}" value=1 {if $section.skip_empty|default:"1"}checked{/if}> Skip this condition if user did not enter any value</td></tr>
        <tr><td>&nbsp;</td><td><input type="checkbox" name="allow_case_{$section.id}" value=1 {if $section.allow_case}checked{/if}> Allow user to select if this condition is case sencitive or not.</td></tr>
        <tr><td colspan=2>&nbsp;</td></tr>
    {/foreach}
    
    </table>
    
</td></tr>
    
 <tr><td>&nbsp;</td></tr>
    
<tr><td><input type=submit name=submit_button value="Submit All"> <input type=reset value="Reset All"></td></tr>
    
</table>

</form>