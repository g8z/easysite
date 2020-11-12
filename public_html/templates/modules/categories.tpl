{* shared template for category administration *}

<form method=post action="">

<table border=0 width=100% cellpadding=1 cellspacing=0 class=normal>
    
   
    <tr><td><table border=0 cellpadding=1 cellspacing=0 class=normal>
    
    {assign var=found value=1}
    
    {foreach from=$data item=aLine}
        <tr>
        {foreach from=$aLine item=aCell}
            {if $aCell == ""}
                <td></td>
            {else}
                <td class=normal nowrap>
                <table cellpadding=0 cellspacing=0 border=0>
                <tr>
                <td nowrap class=normal>
                <input type=text size=15  name="title_{$aCell.id}" value="{$aCell.title}">
                {if !$aCell.noedit}
                    <input onClick="return confirm( 'Are you SURE? this will permanently delete this category, all sub-categories, and all items within the categories and sub-categories.' );" type=submit name=kill_{$aCell.id} value=" x ">
                {/if}
                {if $aCell.higherItemExists}
                    <input type=submit name=bump_{$aCell.id} value=" ^ ">
                {/if}
                {if !$aCell.noedit}
                    {if $aCell.level > $startLevel}
                        <input type=submit name=shift_{$aCell.id} value=" < ">
                    {/if}
                    {if !$aCell.first}
                        <input type=submit name=unshift_{$aCell.id}  value=" > ">
                    {/if}
                    <input type=submit name=insert_{$aCell.id}  value=" * ">
                {/if}
                
                {if $aCell.customLink}
                	{$aCell.customLink}
                {/if}
                </td><td></td>
                </tr>
                </table>
            {/if}
        {/foreach}
        </tr>
    {foreachelse}
    {assign var=found value=0}
    {/foreach}
    
    </table></td></tr>
    
    {if $found}
        <tr><td colspan={$span}><input type=submit name=ok value="Update All Categories"></td></tr>
    {else}
        <tr><td colspan={$span}><input type=submit name=insert_ value="Add The First Category"></td></tr>
    {/if}

</table>

</form>
