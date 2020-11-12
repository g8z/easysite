<table class=normal width=100% cellpadding=0 cellspacing=0 border=0>
    <tr><td class=normal>{$report.header}</td></tr>
    
    {if $reportSettings.paginate eq 'yes' and ($reportSettings.location_navigation eq 'top and bottom' or $reportSettings.location_navigation eq 'top only')}
    <tr><td class=normal align={$report.orientation}>{$navigation}</td></tr>
    {/if}

    {if $numSubmissions gt 0}
    
    {if $settings.smarty_date}
    	{assign var=dateFormat value=$settings.smarty_date}
    {else}
    	{assign var=dateFormat value="%m/%d/%y"}
    {/if}
    
    <tr><td>
    
	    <table border=0 cellpadding=3 cellspacing=1 width=100% class=normal>
	    
	    {* print the headers *}
	    
	    {if $reportSettings.makeEditable eq 'yes' and $containEditable eq 1}
	    <tr style="height: 0px; font-size: 1px;"><td>&nbsp;</td><td colspan="{$headersCount}" width="100%">&nbsp;</td><tr>
	    {/if}
	    
	    <tr>
	    
	    {if $reportSettings.makeEditable eq 'yes' and $containEditable eq 1}
	    <td width=10 bgcolor='{$reportSettings.headerColor|default:"#FFFFFF"}'>&nbsp;</td>
	    {/if}
	    
	    {foreach item=header from=$headers}
	    {if $outputFields.$header.visible}<td valign=bottom class='{$reportSettings.headerStyle|default:"subtitle"}' bgcolor='{$reportSettings.headerColor|default:"#FFFFFF"}'>{$outputFields.$header.display_title}</td>{/if}
	    {/foreach}
	    </tr>
	    
	    {* print the form submission data *}
	    
	    
	    {foreach key=id name=iterator item=row from=$data}
	    
	    {if $smarty.foreach.iterator.iteration is odd}
	    	 {assign var=rowColor value=$reportSettings.oddRowColor}
	   	 {assign var=defaultRowColor value='#EEEEFF'}
	    {else}
	   	 {assign var=rowColor value=$reportSettings.evenRowColor}
	   	 {assign var=defaultRowColor value='#CCCCCC'}
	    {/if}
	    
	    <tr {if $row.isGroupTitle neq 1}bgcolor="{$rowColor|default:$defaultRowColor}"{/if}>
	    
	    {if $reportSettings.makeEditable eq 'yes'}
	    <td align=center nowrap width=50>
    	   {if $row._editable_ eq 1}
	       <a href="?action=editRecord&sub_id={$row.submission_id}&id={$report.id}&form_id={$report.resource}"><img src="{$docroot}images/edit.png" border=0></a>  
	       <a href="#" onclick="javascript: if (confirm( 'Are you sure want to delete this record?' )) document.location.href='?action=deleteRecord&sub_id={$row.submission_id}&id={$report.id}&form_id={$report.resource}&start={$smarty.request.start}&set={$smarty.request.set}'; else return false;"><img src="{$docroot}images/drop.png" border=0></a>
	       {else}
	       &nbsp;
	       {/if}
	    </td>
	    {/if}
	    
	    {if $row.isGroupTitle neq 1}
    	    {foreach item=header from=$headers}
    	    {if $outputFields.$header.visible}
    	    	<td>{$row.$header|nl2br}</td>
    	    {/if}
    	    {/foreach}
	    {else}
    	    <td colspan={$headersCount}>
    	    <span class={$row.style} style="padding-left: {$row.indent}">
            <b>{$row.title}</b>
            </span>
            </td>
	    {/if}
	    
	    </tr>
	    {/foreach}
	    
	    </table>

    </td></tr>
    {else}
    <tr><td class=normal>{$reportSettings.noSubmissionsMessage|default:"There are no submissions yet for this form, or the submissions have been cleared."}</td></tr>
    {/if}
 
    {if $reportSettings.paginate eq 'yes' and ($reportSettings.location_navigation eq 'top and bottom' or $reportSettings.location_navigation eq 'bottom only')}
    <tr><td class=normal align={$report.orientation}>{$navigation}</td></tr>
    {/if}
    
    <tr><td class=normal>{$report.footer}</td></tr>
</table>