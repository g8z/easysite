{literal}

<script type="text/javascript">
<!--
function send( current ) {
    document.navForm.current.value = current;
    document.navForm.submit();
    return false;
}
-->
</script>

{/literal}

<form name="navForm" method="post" action="{$action}">

{foreach from=$pagePostData item=post}
<input type="hidden" name="{$post.name}" value="{$post.value}">
{/foreach}

<input type="hidden" name="current" value="{$current}">
<input type="hidden" name="prev" value="{$current}">

<table width="100%" class="normal" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td valign="middle" colspan="4">
        {if $canPrev}
            <a href="#" onclick="javascript: return send( 1 );">First</a> &nbsp;
            <a href="#" onclick="javascript: return send( {$current-1} );">&#9668;</a>
        {else}
            First &nbsp;
            &#9668;
        {/if}
        
        {foreach from=$pages item=page}
        
        {if $page.current}
            {$page.number}
        {else}
            <a href="#" onclick="javascript: return send( {$page.number} );">{$page.number}</a>
        {/if}
        
        {/foreach}
        
        {if $canNext}
            <a href="#" onclick="javascript: return send( {$current+1} );">&#9658;</a> &nbsp;
            <a href="#" onclick="javascript: return send( {$pagesCount} );">Last</a>
        {else}
            &#9658; &nbsp;
            Last
        {/if}
        
        </td>
     </tr>
     
     {* Items per Page, Total Pages bar temporarily disabled *}
     {*
     <tr><td colspan="4">&nbsp;&nbsp;&nbsp;</td></tr>
     
     <tr>
	<td nowrap width="10%">Items per Page: {if $displayPerPage eq ""}{$perPage}{/if}</td>
	<td>
        {if $displayPerPage}
        	<select name="perPage" onchange="javascript:document.navForm.submit();">{html_options values=$perPageList output=$perPageList selected=$perPage}</select>
        {else}
        	<input type="hidden" name="perPage" value="{$perPage}">
        {/if}
        </td>
        <td>&nbsp;</td>
        <td width=80%>Total Pages: {$pagesCount}</td>
    </tr>
    *}
    
</table>
</form>