{* special js functions for this page only *}
{literal}
<script language="Javascript">
<!--


function displayFieldTitle( areaName, title ) {
    var theForm = document.reports;
    
    eval( 'theForm.'+areaName+'.value += \'{'+title+'}\'' );
    
}

function changeLayout() {
    var theForm = document.reports;
    
    theForm.action.value = 'changeLayout';
    theForm.submit();
}

//-->
</script>
{/literal}

<form action=reports.php method="POST" enctype="multipart/form-data" name="reports">

<input type=hidden name=action value="saveLayout">
<input type=hidden name=id value="{$report.id}">


<table border=0 cellpadding=0 cellspacing=3 class=normal>


    <tr><td class=normal colspan=2>{$logoutLink} {$pathway}</td></tr>
    
    <tr><td colspan=2>&nbsp;</td></tr>

    <tr>
    <td class=title colspan=2>Edit Report Layout</td>
    </tr>
    
    <tr><td colspan=2>&nbsp;</td></tr>
    
    <tr><td colspan=2>A report is table of data organized in rows and columns. Each row of this table corresponds to one submission of the form that this report is based on. You may do a simple column report, or specify an advanced layout for each row of the report. To use this feature you must have a strong knowledge of HTML.
    
    <tr><td colspan=2>&nbsp;</td></tr>
    
    <tr><td colspan="2">Report Layout:&nbsp;&nbsp;&nbsp;&nbsp;<select name=advanced_layout onchange="javascript: changeLayout();">{html_options options=$layoutOptions selected=$report.advanced_layout}</select></td></tr>
    <tr><td colspan=2>&nbsp;</td></tr>

    {if $report.advanced_layout}
        <tr><td colspan=2>
        <table class=normal cellpadding=5>
        <tr>
            <td valign=top>
                <textarea name="layout_template" cols=60 rows=5>{$report.layout_template}</textarea>
            </td><td valign=top>
                Available fields (Clickable):  <a href="javascript:launchCentered('{$help.url}?type=report_clickable_fields',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a><br />
                {foreach name=sections item=section from=$clickableFieldTitles}
                    {* skip first iteration *}
                    <a href="#" onclick="javascript: displayFieldTitle( 'layout_template', '{$section|addslashes}' ); return false;">{ldelim}{$section}{rdelim}</a><br />
                {/foreach}
            </td>
        </tr>
        </table><br />
        
        {literal}
        Suppose that you have three fields available to you, {First Name}, {Last Name} and {E-Mail Address} based on website feedback form. To create a report which lists these names and email addresses you might use this layout:<br /><br />
        &lt;b&gt;{First Name}, {Last Name}&lt;/b&gt; at &lt;a href="{E-Mail Address}"&gt;{E-Mail Address}&lt;/a&gt;<br /><br />
        The resulting report might look like this:<br /><br />
        <b>Gates, Bill</b> at <font style="text-decoration:underline; color:blue; font-wight:bold">billgates@microsoft.com</font><br />
        <b>Ellison, Larry</b> at <font style="text-decoration:underline; color:blue; font-wight:bold">larrye@oracle.com</font><br />
        <b>Fiorina, Carly</b> at <font style="text-decoration:underline; color:blue; font-wight:bold">carly@hp.com</font><br />
        {/literal}
        </td></tr>
        
	    <tr><td colspan=2>&nbsp;</td></tr>
    {else}
        <!--<tr><td colspan=2>Editing Field Titles</td></tr>-->
        
    {/if}
    
    <tr><td colspan=2><input type=submit name=submit_button value="Submit"> <input type=reset name=reset_button value="Reset"></td></tr>
    
    <tr><td colspan=2>&nbsp;</td></tr>
    
    <tr>
    <td class=normal colspan=2>
    {$logoutLink} {$pathway}
    </td>
    </tr>
    
</table>

</form>
 