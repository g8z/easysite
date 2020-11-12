{* special js functions for this page only *}
<script language="Javascript">
<!--


//-->
</script>

{* loop through all available sections, printing data for each *}
<form name="editPage">
<input type=hidden name=bumpUpSectionVar value="">
<input type=hidden name=deleteSectionVar value="">
<input type=hidden name=radioGroup value="{$radioGroup}">
<table border=0 cellpadding=0 cellspacing=3 width=100% class=normal>

    <tr>
    <td class=normal>
    {$logoutLink} {$userGuideLink}
    </td>
    </tr>
    
    <tr><td>&nbsp;</td></tr>

    <tr><td class=subtitle>Select {if $type eq 'list'}Values{else}Date{/if}</td></tr>
    {if $type eq 'list'}
    <tr><td>You can choose mutliple values by holding a Ctrl key and selected appropriate value.</td></tr>
    {/if}


    <tr><td>
        {if $type eq 'list'}
            <select name="values" multiple size="6">
            {html_options output="$choiceTitles" values="$choiceIds"}
            </select>
        {else}
    	    {html_select_date start_year="-60" end_year="+10" time=0000-00-00}
        {/if}
        <br /><input type=button name=ok value=" OK " onclick="javascript: sendValues();">
    </td></tr>
    <tr>
    
    <td class=normal>
    {$logoutLink} {$userGuideLink}
    </td>
    </tr>

</form>

</table>

<script language="javascript">

var theForm = document.editPage;

{if $type eq 'list'}


{else}
var value = new String();
var selTime = eval( 'opener.editPage.date_value_{$condId}.value' );

var formDate = new Date();
formDate.setTime( selTime * 1000 );

for ( var i=0; i<theForm.Date_Year.length; i++ ) {ldelim}
    if ( theForm.Date_Year.options[i].value == formDate.getYear() + 1900 )
        theForm.Date_Year.options[i].selected = true;
{rdelim}

var day = formDate.getDate() ? formDate.getDate()-1 : 0 ;

theForm.Date_Month.options[formDate.getMonth()].selected = true;
theForm.Date_Day.options[day].selected = true;
{/if}

</script>
