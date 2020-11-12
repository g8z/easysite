{if $canAccess}
    <table border=0 cellpadding=0 cellspacing=0 width=100% height=100%>
    <tr>
    <td valign=middle align=center>
    
    
    {if $link}
    <a {$target} href="{$link}">
    {/if}
    
    <img name=img border=0 src='{imgsrc  table=$table field=$field id=$id}'>
    
    {if $link}
    </a>
    {/if}
    
    {if $newWindow neq 1}
    <br /><br />
    <input type=button name=back_button value='<< Back' onclick="javascript: history.go(-1);">
    {/if}
    
    
    </td>
    </tr>
    </table>
{else}
    You can not access this file directly.
{/if}