<table class=normal width=100% cellpadding=0 cellspacing=0 border=0>
<tr><td class=normal>{$report.header}</td></tr>

{if $reportSettings.paginate eq 'yes' and ($reportSettings.location_navigation eq 'top and bottom' or $reportSettings.location_navigation eq 'top only')}
<tr><td class=normal align={$report.orientation}>{$navigation}</td></tr>
{/if}
</table>

<table class=normal width=100% cellpadding=0 cellspacing=0 border=0>


{foreach item=row from=$output name=iterator}

{if $smarty.foreach.iterator.iteration is odd}
	 {assign var=rowColor value=$reportSettings.oddRowColor}
	 {assign var=defaultRowColor value='#EEEEFF'}
{else}
	 {assign var=rowColor value=$reportSettings.evenRowColor}
	 {assign var=defaultRowColor value='#CCCCCC'}
{/if}

	    
{if $row.isGroupTitle}
    <tr>
    <td colspan={$headersCount}>
    <span class="{$row.style}" style="padding-left: {$row.indent}">
    <b>{$row.title}</b>
    </span>
    </td>
    </tr>
{else}
    <tr bgcolor="{$rowColor|default:$defaultRowColor}">
	    
    {if $reportSettings.makeEditable eq 'yes' and $row._editable_ eq 1}
    <td align=center nowrap width=50 style="background-color: transparent" valign="top">
       <a href="?action=editRecord&sub_id={$row.submission_id}&id={$report.id}&form_id={$report.resource}"><img src="{$docroot}images/edit.png" border=0></a>  
       <a href="#" onclick="javascript: if (confirm( 'Are you sure want to delete this record?' )) document.location.href='?action=deleteRecord&sub_id={$row.submission_id}&id={$report.id}&form_id={$report.resource}&start={$smarty.request.start}&set={$smarty.request.set}'; else return false;"><img src="{$docroot}images/drop.png" border=0></a>
    </td>
    {/if}

	    <td width="100%">{$row.content}</td>
    </tr>
{/if}

{foreachelse}

<tr><td>{$reportSettings.noSubmissionsMessage|default:"There are no submissions yet for this form, or the submissions have been cleared."}</td></tr>

{/foreach}

 
{if $reportSettings.paginate eq 'yes' and ($reportSettings.location_navigation eq 'top and bottom' or $reportSettings.location_navigation eq 'bottom only')}
<tr><td class=normal align={$report.orientation}>{$navigation}</td></tr>
{/if}

</table>
    
<table class=normal width=100% cellpadding=0 cellspacing=0 border=0>

<tr><td class=normal>{$report.footer}</td></tr>
    
</table>