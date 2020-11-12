{* special js functions for this page only *}
{literal}
<script language="Javascript">
<!--

{/literal}
{foreach name=sections item=field from=$fields}
fieldValue{$field.id} = new Array ( {$field.values} );
fieldTitle{$field.id} = new Array ( {$field.titles} );
{/foreach}
{literal}


function setConditionValues( condVal, condId, selCond ) {
    var theForm = document.reports;
    var opt;
    var item;
    var options = eval( 'theForm.condition_'+condId+'.options' );

    options.length = 0;
    for ( var i=0; i<condVal.length; i++ ) {
        opt = new Option( condVal[i], condVal[i] );

        options.length++;
        options[i] = opt;

        if ( condVal[i] == selCond ) {
            options[i].selected = true;
        }

    }

}

function activateSelect( condId, fieldId, defaultValue ) {
    var theForm = document.reports;

    toggleT('div_date_'+condId,'h');
    toggleT('div_text_'+condId,'h');
    toggleT('div_multiple_'+condId,'s');

    setFieldValues( eval( 'fieldValue'+fieldId ), eval( 'fieldTitle'+fieldId ), condId, defaultValue );
}

function activateEdit( condId, value ) {
    var theForm = document.reports;
    toggleT('div_date_'+condId,'h');
    toggleT('div_text_'+condId,'s');
    toggleT('div_multiple_'+condId,'h');
}

function activateDate( condId ) {
    var theForm = document.reports;
    toggleT('div_date_'+condId,'s');
    toggleT('div_text_'+condId,'h');
    toggleT('div_multiple_'+condId,'h');

    var date_value = eval( 'theForm.date_value_'+condId+'.value' );

    var d = date_value.split( '-' );
    if ( !d[0] ) d[0] = 1;
    if ( !d[1] ) d[1] = 1;
    if ( !d[2] ) d[2] = 1;

    var objMonth = eval( 'theForm.Date_'+condId+'_Month' );
    var objDay = eval( 'theForm.Date_'+condId+'_Day' );
    var objYear = eval( 'theForm.Date_'+condId+'_Year' );

    for ( var i=0; i<objYear.length; i++ ) {
        if ( objYear.options[i].value == d[0] )
            objYear.options[i].selected = true;
    }

    objMonth.options[d[1]-1].selected = true;
    objDay.options[d[2]-1].selected = true;

    setDateValues( condId );
}

function setDateValues( condId ) {
    var theForm = document.reports;
    var objMonth = eval( 'theForm.Date_'+condId+'_Month' );
    var objDay = eval( 'theForm.Date_'+condId+'_Day' );
    var objYear = eval( 'theForm.Date_'+condId+'_Year' );
    value = objYear.value+'-'+objMonth.value+'-'+objDay.value;

    eval( 'theForm.date_value_'+condId+'.value = \'' + value + '\'' );

}

function changeValue( condId, selCond, defaultValue ) {

    var theForm = document.reports;
    var fieldId = eval( 'theForm.section_id_'+condId+'.value' );
    var checkConds = new Array( 'checked', 'unchecked' );
    var commonConds = new Array( '>', '<', '=', '!=', '>=', '<=' );
    var stringConds = new Array( '>', '<', '=', '!=', '>=', '<=', 'starts with', 'contains', 'ends with' );

    var fieldTypes = new Array();
    {/literal}

    {foreach name=sections item=field from=$fieldTypes}
    fieldTypes['{$field.id}'] = '{$field.field_type}';
    {/foreach}

    {literal}

    if ( fieldTypes[fieldId] ) {
    if ( fieldTypes[fieldId] == 'checkbox' ) {
        toggleT('div_date_'+condId,'h');
        toggleT('div_text_'+condId,'h');
        toggleT('div_multiple_'+condId,'h');
        setConditionValues( checkConds, condId, selCond );
    }
    else if ( fieldTypes[fieldId] == 'radio' || fieldTypes[fieldId] == 'select' || fieldTypes[fieldId].match(/^modcat/) ) {

	    activateSelect( condId, fieldId, defaultValue );
        setConditionValues( commonConds, condId, selCond );

    }
    else if ( fieldTypes[fieldId] == 'date' ) {
    	activateDate( condId );
        setConditionValues( commonConds, condId, selCond );
    }
    else {
	    activateEdit( condId, defaultValue );
        setConditionValues( stringConds, condId, selCond );
    }
  }
}

function setFieldValues( condVal, condTitle, condId, selCond ) {
    var theForm = document.reports;
    var opt;
    var item;

    var options = eval( 'theForm.list_values_'+condId+'.options' );

    options.length = 0;

    for ( var i = 0; i < condVal.length; i++ ) {
        opt = new Option( condTitle[i], condVal[i] );

        options.length++;
        options[i] = opt;

        if ( condVal[i] == selCond ) {
            options[i].selected = true;
        }
    }
}

function changeReport() {
var theForm = document.reports;
    theForm.id.value=theForm.savedReports.value;
    theForm.action.value='';
    theForm.submit();

}


function changeForm() {
var theForm = document.reports;

    if ( !theForm.name.value ) {
        alert( 'Please enter a name for this report.' )
        return false;
    }
    theForm.submit();
}

function submitForm() {
    var theForm = document.reports;

    // validation here

    theForm.submit();
}

function deleteSection( sectId ) {
    var theForm = document.reports;

    theForm.action.value='deleteCondition';
    theForm.deleteSectionVar.value=sectId;

    submitForm();
}


function addNewGroup() {
    var theForm = document.reports;

    theForm.action.value='newGroupForm';

    submitForm();
}

function displayFieldTitle( areaName, title ) {
    var theForm = document.reports;

    eval( 'theForm.'+areaName+'.value += \'{'+title+'}\'' );

}


function deleteGroup( groupId ) {
    var theForm = document.reports;

    theForm.action.value='deleteGroup';
    theForm.deleteGroupVar.value=groupId;

    submitForm();
}

function bumpGroup( groupId ) {
    var theForm = document.reports;

    theForm.action.value='bumpGroup';
    theForm.bumpGroupVar.value=groupId;

    submitForm();
}


function createReportForm() {
    var theForm = document.reports;

    theForm.action.value='createReportForm';

    submitForm();
}
//-->
</script>
{/literal}

<form action=reports.php method="POST" enctype="multipart/form-data" name="reports">

<input type=hidden name=action value="save">
<input type=hidden name=id value="{$report.id}">
<input type=hidden name=deleteSectionVar value="">
<input type=hidden name=deleteGroupVar value="">
<input type=hidden name=bumpGroupVar value="">


<table border=0 cellpadding=0 cellspacing=3 class=normal width=100%>


    <tr>
    <td class=normal colspan=2>
    {$logoutLink} {$pathway}
    </td>
    </tr>

    <tr><td colspan=2>&nbsp;</td></tr>

    <tr>
    <td class=title colspan=2>Reports</td>
    </tr>

    <tr><td colspan=2>&nbsp;</td></tr>

    <tr><td colspan=2>
    <table cellspacing="1" cellpadding="3" class="normal" border="0">
    <tr>
    <td>Saved Reports:</td><td> <select name="savedReports" onchange="javascript: changeReport();">{html_options output=$reportTitles values=$reportValues selected=$report.id}</select>   {if $report.id}[ <a href="reports.php?action=viewHtml&id={$report.id}" target="_blank">Run Report</a> ]{/if}</td>
    </tr>

    {if $report.id}
    <tr><td colspan=2>&nbsp;</td></tr>

    <tr><td colspan=2><input type=button onclick="javascript: submitForm();" name=submit_button value="Save"> <input type=button name=delete_button value="Delete" onclick="javascript: if ( confirm('Are sure you want to delete this report?') ) document.location.href='reports.php?action=delete&id={$report.id}';"> <input type=button name=edit_settings value="Edit Settings" onclick="javascript: document.location.href='editSettings.php?reportID={$report.id}';"> <input type=button name=edit_fields value="Edit Fields" onclick="javascript: document.location.href='reports.php?action=editFields&id={$report.id}';"> <input type=button name=edit_layout value="Edit Layout" onclick="javascript: document.location.href='reports.php?action=editLayout&id={$report.id}';"> <input type=button name=embed_reports value="Embed Reports" onclick="javascript: document.location.href='reports.php?action=embedReports&id={$report.id}';"></td></tr>
    {/if}

    <tr><td colspan=2>&nbsp;</td></tr>

    <tr>
        <td class="subtitle" colspan=2>{if $report.id}Edit Report: {$report.name}{else}Create a New Report{/if}</td>
    </tr>

    <tr><td colspan=2>&nbsp;</td></tr>

    <tr><td>Report Name:</td><td> <input type=text name=name value="{$report.name}" size="30">  <a href="javascript:launchCentered('{$help.url}?type=report_name',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td></tr>

    <tr><td>Base this report on:</td><td> <select name="resource" onchange="javascript: changeForm();">{html_options output=$formTitles values=$formIds selected=$report.resource}</select>  <a href="javascript:launchCentered('{$help.url}?type=report_data_source',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td></tr>
    </table>
    </td></tr>

    <tr><td colspan=2>&nbsp;</td></tr>

    <tr><td colspan=2>You must choose the "generate submission reports" option in the Form Manager to create a report from a from. [ <a href="editForms.php">Open Form Manager</a> ]</td></tr>

    {if $report.id && $report.resource}

    <tr><td colspan=2>&nbsp;</td></tr>

    <tr>
        <td class="subtitle" colspan=2>Sorting & Grouping</td>
    </tr>

    <tr><td colspan=2>&nbsp;</td></tr>

    <tr><td colspan=2>There are {$countGroups} level(s) of grouping currently defined for this report. A "group section" allows you to define summary data for your report. It usually makes the most sense to group to group only by the top 1 or 2 fields.</td></tr>

    <tr><td colspan=2>&nbsp;</td></tr>

    <tr><td colspan=2><input type=button name=add_new_group value='Add New Sort or Group' onclick="javascript: addNewGroup();"></td></tr>

    {foreach name=sections item=section from=$reportGroups}
    <tr><td colspan=2>&nbsp;</td></tr>

    <tr><td colspan=2>
        <table class=normal>
            <tr><td>Field:</td><td><select name=field_id_{$section.id}>{html_options output=$fieldTitles values=$fieldIds selected=$section.field_id}</select>&nbsp;&nbsp;<input type="checkbox" name=do_group_{$section.id} value=1 {if $section.do_group}checked{/if}> Group by this field  <a href="javascript:launchCentered('{$help.url}?type=report_sorting_grouping',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td></tr>
            <tr><td>Position:</td><td><select name=position_{$section.id}>{html_options options=$positionOptions selected=$section.position}</select></td></tr>
            <tr><td>Sort type:</td><td><select name=sort_type_{$section.id}>{html_options options=$orderValues selected=$section.sort_type}</select>  <a href="javascript:launchCentered('{$help.url}?type=report_sort_type',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td></tr>
            <tr><td>Style:</td><td><select name="style_{$section.id}">{html_options values=$styleList output=$styleList selected=$section.style}</select></td></tr>
            <tr><td>Indent:</td><td><input type=text name="indent_{$section.id}" value="{$section.indent}" size=5>  <a href="javascript:launchCentered('{$help.url}?type=report_indented_levels',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td></tr>
            <tr><td>Evaluate sum and average for ths field:</td><td><select name=sum_field_id_{$section.id}>{html_options output=$fieldTitles values=$fieldIds selected=$section.sum_field_id}</select>  <a href="javascript:launchCentered('{$help.url}?type=report_calculations',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td></tr>

            <tr><td valign=top>Layout (HTML):</td><td>
                <table class=normal cellpadding=0 cellspacing="0">
                <tr>
                    <td valign=top>
                        <textarea name="layout_{$section.id}" cols=55 rows=5>{$section.layout}</textarea>
                    </td><td valign=top style="padding-left: 5px;">
                        Available fields (Clickable):<br />
                        <a href="#" onclick="javascript: displayFieldTitle( 'layout_{$section.id}', 'Grouped Field' ); return false;">{ldelim}Grouped Field{rdelim}</a><br />
                        <a href="#" onclick="javascript: displayFieldTitle( 'layout_{$section.id}', 'Count' ); return false;">{ldelim}Count{rdelim}</a>
                        <a href="#" onclick="javascript: displayFieldTitle( 'layout_{$section.id}', 'Sum' ); return false;">{ldelim}Sum{rdelim}</a>
                        <a href="#" onclick="javascript: displayFieldTitle( 'layout_{$section.id}', 'Average' ); return false;">{ldelim}Average{rdelim}</a><br />
                    </td>
                </tr>
                </table>
            </td></tr>
        </table>
    </td></tr>

    <tr><td colspan=2><input type=submit name=submit_all value='Submit All'> <input type=button name=delete_group value="Delete" onclick="javascript: deleteGroup( '{$section.id}' );">{if $smarty.foreach.sections.iteration neq 1} <input type=button name=bump_up value="Bump Up" onclick="javascript: bumpGroup( '{$section.id}' );">{/if}</td></tr>
    <tr><td colspan=2><hr width=100% noshade width=1 color="#0000FF"></td></tr>
    {/foreach}

    <tr><td colspan=2>&nbsp;</td></tr>

    <tr>
        <td class="subtitle" colspan=2>Filter conditions  <a href="javascript:launchCentered('{$help.url}?type=report_filters',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td>
    </tr>

    <tr><td colspan=2>&nbsp;</td></tr>

    <tr><td colspan=2>Only show data which satisfies the following conditions:</td></tr>

    <tr><td colspan=2>&nbsp;</td></tr>

    <tr><td colspan=2>

    <table border=0 cellpadding=1 cellspacing=0 class=normal>

    {foreach name=sections item=section from=$reportConditions}

    {if $smarty.foreach.sections.iteration eq 2}
    <tr><td colspan=3><br /><b>Edit Current Conditions:</b></td></tr>
    {elseif $smarty.foreach.sections.iteration eq 1}
    <tr><td colspan=3><b>Add New Condition:</b></td></tr>
    {/if}

        <tr><td height=50 colspan=3>
        <table cellspasing="3" cellpadding="0" class="normal">
        <tr><td>
        {if $smarty.foreach.sections.iteration neq 1}<input type=button name=delete_section_{$section.id} value=' X ' onclick="javascript: deleteSection( '{$section.id}');">{/if}
        <select name=section_id_{$section.id} onchange="javascript:changeValue( '{$section.id}', '{$section.condition}', '{$section.value}' );">
        {html_options output=$fieldTitles values=$fieldIds selected=$section.section_id}
        </select>
		<select name=condition_{$section.id}>
		{html_options output=$conditions values=$conditions selected=$section.condition}
		</select>
        </td>
        <td nowrap valign="top">
		<span id="div_text_{$section.id}" style="visibility:visible;position:absolute;">
		<input type=text name=value_{$section.id} value="{$section.value}" size=30><br />
		<input type=checkbox name="case_sensitive_{$section.id}" {if $section.case_sen}checked{/if} value=1> Case sensitive
		</span>
		<span id="div_date_{$section.id}" style="visibility:hidden;position:absolute;">
		{html_select_date start_year="-60" end_year="+10" time=0000-00-00 prefix="Date_`$section.id`_" all_extra="onchange=\"javascript: setDateValues( '`$section.id`' )\""}
		</span>
		<span id="div_multiple_{$section.id}" style="visibility:hidden;position:absolute;">
		<select name="list_values_{$section.id}">
		</select>
		</span>
		</td></tr></table>

        <input type=hidden name="date_value_{$section.id}" value='{$section.date_value}'>

        </td>

        </tr>

    {/foreach}

    <script language="javascript">
    {foreach name=sections item=section from=$reportConditions}
    changeValue( '{$section.id}', '{$section.condition}', '{$section.value}' );
    {/foreach}
    </script>

    </table>
    </td></tr>

    <tr><td colspan=2>&nbsp;</td></tr>

    <tr><td colspan=2><input type=button onclick="javascript: submitForm();" name=submit_button value="Save This Report">{if $report.id} <input type=button name=delete_button value="Delete This Report" onclick="javascript: if ( confirm('Are sure you want to delete this report?') ) document.location.href='reports.php?action=delete&id={$report.id}';">{/if}</td></tr>

    <tr><td colspan=2>&nbsp;</td></tr>

    <tr><td colspan=2>
        <table class=normal cellpadding="5">
        <tr>
            <td><span class=subtitle>Report Header</span> (HTML)  <a href="javascript:launchCentered('{$help.url}?type=report_header',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a><br /><textarea name=header rows=5 cols=40>{$report.header}</textarea></td>
            <td><span class=subtitle>Report Footer</span> (HTML)  <a href="javascript:launchCentered('{$help.url}?type=report_footer',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a><br /><textarea name=footer rows=5 cols=40>{$report.footer}</textarea></td>
        </tr>
        </table>
    </td></tr>
    {/if}

    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan=2><input type=button onclick="javascript: submitForm();" name=submit_button value="Save This Report">{if $report.id} <input type=button name=delete_button value="Delete This Report" onclick="javascript: if ( confirm('Are sure you want to delete this report?') ) document.location.href='reports.php?action=delete&id={$report.id}';">{/if}</td></tr>

    <tr><td colspan=2>&nbsp;</td></tr>

    <tr>
    <td class=normal colspan=2>
    {$logoutLink} {$pathway}
    </td>
    </tr>

</table>

</form>
