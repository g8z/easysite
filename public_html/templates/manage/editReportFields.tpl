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
<input type=hidden name=action value="saveFields">

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
    <table border=0 cellpadding=1 cellspacing=0 class=normal>

    <tr><th width=100>Field</th><th>Display Header</th><th width=100>Visible</th></tr>
    {foreach name=sections item=section from=$reportFields}
    
    <tr><td>{$section.title}</td><td><input type=text name="field_display_title_{$section.field_id}" value="{$section.display_title}"></td><td align=center><input type="checkbox" name="field_visible_{$section.field_id}" value=1 {if $section.visible}checked{/if}></td></tr>
    {if $section.field_type eq 'image'}
    <tr><td>&nbsp;</td><td>
                <input type=checkbox name="field_use_link_{$section.field_id}" value=1 {if $section.use_link}checked{/if}>Use this image as link to:<br />
                    &nbsp;&nbsp;&nbsp;<input type=radio name="field_link_type_{$section.field_id}" value=url {if $section.link neq '' and $section.link neq 'images' and $section.link neq 'full_image'}checked{/if}> URL: <input type=text name=field_link_{$section.field_id} {if $section.link neq '' and $section.link neq 'images' and $section.link neq 'full_image'} value="{$section.link}"{/if}>  <a href="#" onclick="javascript: popup( document.reportFields.field_link_{$section.field_id} ); return false;">Insert Field ...</a><br />
                    &nbsp;&nbsp;&nbsp;<input type=radio name="field_link_type_{$section.field_id}" value=full_image {if $section.link neq '' and $section.link eq 'full_image'}checked{/if}> Full Sized Image<br />
                    &nbsp;&nbsp;&nbsp;<input type=radio name="field_link_type_{$section.field_id}" value=images {if $section.link neq '' and $section.link eq 'images'}checked{/if}> All images in the record (gallery view)<br />
                    &nbsp;&nbsp;&nbsp;Target: <select name="field_target_{$section.field_id}">{html_options values=$targetOptions output=$targetOptions selected=$section.target}</select><br /><br />
    </td><td>&nbsp;</td></tr>
    {/if}
    
    {/foreach}
    
    </table>
    
</td></tr>

<tr><td><input type=submit name=submit_button value="Submit All"> <input type=reset value="Reset All"></td></tr>

</table>

</form>