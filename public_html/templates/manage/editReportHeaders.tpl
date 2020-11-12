{* special js functions for this page only *}
{literal}
<script language="Javascript">
<!--

function deleteField( sectId ) {
    var theForm = document.reportFields;
    
    if ( confirm( 'Are you sure want to delete this field?')) {
        theForm.action.value='deleteField';
        theForm.deleteSectionVar.value=sectId;
        
        theForm.submit();
    }
    else {
        return false;
    }
}



function popup( field ) {
	var width = 400;
	var height = 500;

	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;

    win = window.open( 'reports.php?action=fieldSelection&id={/literal}{$reportId}{literal}', null, "top="+top+",left="+left+",width="+width+",height="+height+",status=yes,scrollbars=yes,resizable=yes,dependent=yes", true );
    win.opener = window;
    win.opener.field = field;
}

// -->
</script>
{/literal}

<table border=0 cellpadding=0 cellspacing=3 width=100% class=normal>
<form action=reports.php method="POST" enctype="multipart/form-data" name="reportFields">
<input type=hidden name=deleteSectionVar value="">
<input type=hidden name=id value="{$reportId}">
<input type=hidden name=action value="saveUserFields">

<tr><td class=normal>{$logoutLink} {$pathway}</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td class=title>Edit Report Fields</td></tr>
<tr><td class=subtitle>for the report called "{$report.name}"</td></tr>
    
<tr><td>&nbsp;</td></tr>
    
<tr><td colspan=2 class=normal>[ <a href="?action=editFields&id={$reportId}">Edit Basic Fields</a> ]  [ <a href="?action=editUserFields&id={$reportId}">Edit User-Defined Fields</a> ]</td></tr>

<tr><td>&nbsp;</td></tr>
    
<tr><td>You can add new fields containing custom content including content from report resource fields.</td></tr>

<tr><td>&nbsp;</td></tr>
    
<tr><td>
    <table border=0 cellpadding=2 cellspacing=2 class=normal>

    {foreach name=sections item=section from=$customFields}
    
        {if $smarty.foreach.sections.iteration eq 2}
        <tr><td colspan=2 class=subtitle><br />Edit Custom Fields:</td></tr>
        <tr><td colspan="2"><hr style='width: 100%' noshade width=1 color="#0000FF"></td></tr>
        {elseif $smarty.foreach.sections.iteration eq 1}
        <tr><td colspan=2 class=subtitle>Add New Field:</td></tr>
        {/if}

        <tr><td>Field Title:</td><td><input type=text name=field_title_{$section.id} value="{$section.title}"></td></tr>
        <tr>
            <td valign="top">Content (HTML):</td>
            <td valign="top">
                
                <table cellpadding="0" cellspacing="0" class=normal>
                <tr>
                    <td><textarea name=field_content_{$section.id} cols=40 rows=3>{$section.content_template}</textarea></td>
                    <td valign="top">&nbsp;<a href="#" onclick="javascript: popup( document.reportFields.field_content_{$section.id} ); return false;">Insert Field ...</a></td>
                </tr>
                </table><br />
            
                <input type=checkbox name=field_visible_{$section.id} value=1 {if $section.visible eq 1 or $smarty.foreach.sections.iteration eq 1}checked{/if}>Visible in simple data-dump layout<br />
                <input type=checkbox name=field_use_link_{$section.id} value=1 {if $section.use_link}checked{/if}>Use this field as link to:<br />
                    &nbsp;&nbsp;&nbsp;<input type=radio name=field_link_type_{$section.id} value=url {if $section.link neq '' and $section.link neq 'images'}checked{/if}> URL: <input type=text name=field_link_{$section.id} {if $section.link neq '' and $section.link neq 'images'} value="{$section.link}"{/if}>  <a href="#" onclick="javascript: popup( document.reportFields.field_link_{$section.id} ); return false;">Insert Field ...</a><br />
                    &nbsp;&nbsp;&nbsp;<input type=radio name=field_link_type_{$section.id} value=images {if $section.link neq '' and $section.link eq 'images'}checked{/if}> All images in the record (gallery view)<br />
                    &nbsp;&nbsp;&nbsp;Target: <select name=field_target_{$section.id}>{html_options values=$targetOptions output=$targetOptions selected=$section.target}</select>
            </td>
        </tr>
        
        <tr><td colspan="2">&nbsp;</td></tr>
        
        <tr><td colspan=2><input type=submit name=submit_button value="Submit All"> <input type=reset value="Reset All">{if $smarty.foreach.sections.iteration neq 1} <input type=button name=delete_field_{$section.id} value="Delete" onclick="javascript: return deleteField( '{$section.id}' );">{/if}</td></tr>
    {/foreach}
    
    </table>
    
</td></tr>
    
</table>

</form>