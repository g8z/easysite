{literal}
<script language="Javascript">
<!--

function displayFieldTitle( title ) {
    var theForm = document.reportGroups;
    
    theForm.layout.value += '{' + title + '}';
    
}


//-->
</script>
{/literal}

<form action=reports.php method="POST" enctype="multipart/form-data" name="reportGroups">

<input type=hidden name=action value="addNewGroup">
<input type=hidden name=id value="{$report.id}">
<input type=hidden name=resource value="{$report.resource}">
<table class=normal>
    <tr><td class=normal colspan=2>{$logoutLink} {$pathway}</td></tr>
    
    <tr><td colspan=2>&nbsp;</td></tr>

    <tr><td class=title colspan=2>Field Groups</td></tr>
    <tr><td class="subtitle" colspan=2>for the report named "{$report.name}"</td></tr>
    
    <tr><td colspan=2>&nbsp;</td></tr>
    
    <tr><td colspan=2>You can create report groups for sorting and grouping data. There can be evaluated count of rows, sum, average of particular field within the group.</td></tr>

    <tr><td colspan=2>&nbsp;</td></tr>

    <tr><td>Field:</td><td><select name=field_id>{html_options output=$fieldTitles values=$fieldIds}</select>&nbsp;&nbsp;<input type="checkbox" name=do_group value=1> Group by this field  <a href="javascript:launchCentered('{$help.url}?type=report_sorting_grouping',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td></tr>
    <tr><td>Position:</td><td><select name=position>{html_options options=$positionOptions}</select></td></tr>
    <tr><td>Sort type:</td><td><select name=sort_type>{html_options options=$orderValues}</select>  <a href="javascript:launchCentered('{$help.url}?type=report_sort_type',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td></tr>
    <tr><td>Style:</td><td><select name="style">{html_options values=$styleList output=$styleList}</select></td></tr>
    <tr><td>Indent (pixels):</td><td><input type=text name="indent" value="0" size=5>  <a href="javascript:launchCentered('{$help.url}?type=report_indented_levels',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td></tr>
    <tr><td>Evaluate sum and average for this field:</td><td><select name=sum_field_id>{html_options output=$fieldTitles values=$fieldIds}</select>  <a href="javascript:launchCentered('{$help.url}?type=report_calculations',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td></tr>
    
    <tr><td valign=top>Layout (HTML):</td><td>
        <table class=normal cellpadding=0 cellspacing="0">
        <tr>
            <td valign=top>
                <textarea name="layout" cols=55 rows=5></textarea>
            </td><td valign=top style="padding-left: 5px;">
                Available fields (Clickable):<br />
                <a href="#" onclick="javascript: displayFieldTitle( 'Grouped Field' ); return false;">{ldelim}Grouped Field{rdelim}</a><br />
                <a href="#" onclick="javascript: displayFieldTitle( 'Count' ); return false;">{ldelim}Count{rdelim}</a> 
                <a href="#" onclick="javascript: displayFieldTitle( 'Sum' ); return false;">{ldelim}Sum{rdelim}</a> 
                <a href="#" onclick="javascript: displayFieldTitle( 'Average' ); return false;">{ldelim}Average{rdelim}</a><br />
            </td>
        </tr>
        </table>
    </td></tr>
    
    <tr><td colspan=2>&nbsp;</td></tr>
    
    <tr><td colspan=2><input type=submit name=save value='Add Group'> <input type=button name=cancel value='Cancel' onclick="javascript: document.location.href='{$reportReturnLink}'"></td></tr>
</table>
</form>